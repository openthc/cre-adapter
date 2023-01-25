<?php
/**
 * Section in BioTrack as Room
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\BioTrack;

class Section extends \OpenTHC\CRE\BioTrack\Base
{
	/**
	 */
	function single($x)
	{
		throw new \Exception('Not Implemented');
	}

	/**
	 *
	 */
	function create($obj)
	{
		$L = $this->_client->getLicense();

		$arg = array(
			'id' => intval(crc32($obj['name']) & ~ 0x80000000),
			'name' => trim($obj['name']),
			'location' => $L['guid'],
			'quarantine' => ($obj['quarantine'] == 1 ? '1' : '0'),
		);

		// Create
		$res = null;
		switch (strtoupper($obj['type'])) {
		case 'INVENTORY':
			$res = $this->_client->inventory_room_add($arg);
			break;
		case 'PLANT':
			$res = $this->_client->plant_room_add($arg);
			break;
		default:
			throw new \Exception('Invalid Section Type [RBZ#032]');
		}

		$txn = 0;
		if ($res['success']) {
			$txn = $res['transactionid'];
		}

		if ($txn > 0) {

			$res = null;

			switch (strtoupper($obj['type'])) {
			case 'INVENTORY':
				$res = $this->_client->sync_inventory_room();
				$res = $res['inventory_room'];
				break;
			case 'PLANT':
				$res = $this->_client->sync_plant_room();
				$res = $res['plant_room'];
				break;
			}

			if (is_array($res)) {
				foreach ($res as $rec) {
					if ($rec['transactionid_original'] == $txn) {
						if ($rec['name'] == $obj['name']) {
							$rec['@id'] = $rec['roomid']; // Promote BT Internal
							return array(
								'data' => $rec,
							);
						}
					}
				}
			}
		}

		return [
			'data' => null,
			'meta' => [ 'note' => 'Unknown Failure [LBS-081]' ]
		];

	}

	/**
	 * [delete description]
	 * @param [type] $x [description]
	 * @return [type] [description]
	 */
	function delete($obj)
	{
		$ret = null;

		$L = new License($obj['license_id']);

		// Re-Patch Code/GUID
		$oid = null;
		if (preg_match('/^(I|P)([0-9a-f]+)$/', $obj['guid'], $m)) {
			$oid = $m[2];
			$oid = hexdec($oid);
		}

		switch (strtoupper($obj['type'])) {
		case 'INVENTORY':
			$ret = $this->_client->inventory_room_remove($L['guid'], $oid);
			break;
		case 'PLANT':
			$ret = $this->_client->plant_room_remove($L['guid'], $oid);
			break;
		}

		// $ret['data'] = (1 == $ret['success'] ? 'success' : 'failure' );

		return [
			'data' => (1 == $ret['success']),
			'meta' => [],
		];

	}

	/**
	 *
	 */
	function update($oid, $arg)
	{
		$oid = preg_replace('/^(I|P)0+/', null, $oid);
		$arg['name'] = trim($arg['name']);
		$arg['quarantine'] = intval($arg['quarantine']);

		$L = $this->_client->getLicense();

		// Update
		$res = array();
		switch ($arg['type']) {
		case 'Inventory':

			$res = $this->_client->inventory_room_modify(
				$id,
				$arg['name'],
				$L['guid'],
				$arg['quarantine']
			);

			break;
		case 'Plant':
			$res = $this->_client->plant_room_modify($id, $arg['name'], $L['guid']);
			break;
		}

		$ret = array();
		if ($res['success']) {
			$ret = array(
				'status' => 'success',
				'result' => $obj,
			);
		} else {
			$ret = array(
				'status' => 'failure',
				'result' => $res,
			);
		}

		return $ret;
	}

	/**
	 * Sync this Object
	 */
	function sync()
	{
		$ret = 0;
		$txn = $this->sync_inventory_room();
		$ret = max($ret, $txn);
		$txn = $this->sync_plant_room();
		$ret = max($ret, $txn);
		// Upate something?
		return $ret;
	}

	protected function sync_inventory_room()
	{
		$arg = $this->_client->_sync_object('sync_inventory_room');
		$res = $this->_client->_curl_exec($arg);
		$res = $res['inventory_room'];
		if (empty($res)) {
			return(0);
		}

		foreach ($res as $x) {

			// API Docs Say this will exist; sometimes it's empty /djb 20170707
			if (empty($x['roomid'])) {
				continue;
			}

			$max = max($max, $x['transactionid']);

			// if (empty($x['location'])) {
			// 	continue;
			// 	//print_r($x);
			// 	//throw new \Exception("Inventory Section is Missing Location {$x['roomid']}, assuming default");
			// 	//syslog(LOG_ERR, "Inventory Section is Missing Location {$x['roomid']}, assuming default");
			// 	//$x['location'] = $l['code'];
			// }

			$this->import($x, 'Inventory');
		}

	}

	protected function sync_plant_room()
	{
		$arg = $this->_client->_sync_object('sync_plant_room');
		$res = $this->_client->_curl_exec($arg);
		$res = $res['plant_room'];
		if (empty($res)) {
			return(0);
		}

		foreach ($res as $x) {

			// API Docs Say this will exist; sometimes it's empty /djb 20170707
			if (empty($x['roomid'])) {
				continue;
			}

			$max = max($max, $x['transactionid']);

			// Skip Shitty data from BioTrack
			// if (empty($x['location'])) {
			// 	continue;
			// 	throw new \Exception("Plant Section is Missing Location {$x['roomid']}, assuming default");
			// 	syslog(LOG_ERR, "Plant Section is Missing Location {$x['roomid']}, assuming default");
			// }

			$this->import($x, 'Plant');

		}

	}

	protected function import($x, $type_r)
	{
		$rid = sprintf('%s%08x', substr($type_r, 0, 1), $x['roomid']);
		$sql = 'SELECT * FROM room WHERE type = ? AND guid = ?';
		$arg = array($type_r, $rid);
		$chk = SQL::fetch_row($sql, $arg);

		$R = null;

		if (empty($chk)) {

			$L = License::findByGUID($x['location']);
			if (empty($L['id'])) {
				$L = new License();
				$L['code'] = $x['location'];
				$L['guid'] = $x['location'];
				$L['name'] = '-unknown-section-';
				$L['type'] = 'Unknown Section';
				$L['hash'] = '-';
				$L->setFlag(License::FLAG_MINE);
				$L->save();
			}

			$R = new Room();
			$R['guid'] = $rid;
			$R['license_id'] = $L['id'];
			$R['type'] = $type_r;

		} else {
			$R = new Room($chk);
		}

		$R['name'] = $x['name'];

		if (!empty($x['deleted'])) {
			$R->setFlag(Room::FLAG_DELETED);
		} else {
			$R->delFlag(Room::FLAG_DELETED);
		}

		if (!empty($x['quarantine'])) {
			$R->setFlag(Room::FLAG_QUARANTINE);
		} else {
			$R->delFlag(Room::FLAG_QUARANTINE);
		}

		if (empty($R['id'])) {
			$R->setMeta($x, 'Section/Created via Sync');
		} else {
			$R->setMeta($x, 'Section/Updated via Sync');
		}

		$R->save();

	}

}
