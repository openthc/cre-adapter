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
				$oid,
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

}
