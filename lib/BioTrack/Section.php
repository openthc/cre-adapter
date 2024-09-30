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
	 * Turns a BioTrack ID into our Type
	 */
	protected function to_internal_id(string $oid) : array
	{
		// if (preg_match('/^(I|P)([0-9a-f]+)$/', $oid, $m)) {
		return [ $x, '' ];
	}

	/**
	 * Turns a BioTrack ID into our Type
	 */
	protected function to_external_id(string $x) : string
	{
		return $x;
	}


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

		return $res;

		// $txn = 0;
		// if ($res['success']) {
		// 	$txn = $res['transactionid'];
		// }

		// if ($txn > 0) {

		// 	$res = null;

		// 	switch (strtoupper($obj['type'])) {
		// 	case 'INVENTORY':
		// 		$res = $this->_client->sync_inventory_room();
		// 		$res = $res['inventory_room'];
		// 		break;
		// 	case 'PLANT':
		// 		$res = $this->_client->sync_plant_room();
		// 		$res = $res['plant_room'];
		// 		break;
		// 	}

		// 	if (is_array($res)) {
		// 		foreach ($res as $rec) {
		// 			if ($rec['transactionid_original'] == $txn) {
		// 				if ($rec['name'] == $obj['name']) {
		// 					$rec['@id'] = $rec['roomid']; // Promote BT Internal
		// 					return array(
		// 						'data' => $rec,
		// 					);
		// 				}
		// 			}
		// 		}
		// 	}
		// }

		return [
			'code' => 200,
			'data' => $res,
			'meta' => [ 'note' => 'Unknown Failure [LBS-081]' ]
		];

	}

	/**
	 * [delete description]
	 * @param [type] $x [description]
	 * @return [type] [description]
	 */
	function delete(string $oid, $obj=[])
	{
		$L = $this->_client->getLicense();

		// Re-Patch Code/GUID
		if (preg_match('/^(I|P)([0-9a-f]+)$/', $oid, $m)) {
			$oid = $m[2];
			$oid = hexdec($oid);
			if (empty($obj['type'])) {
				$obj['type'] = $m[1];
			}
		}

		$res = [];

		switch (strtoupper($obj['type'])) {
		case 'I':
		case 'INVENTORY':
			$res = $this->_client->inventory_room_remove($L['guid'], $oid);
			break;
		case 'P':
		case 'PLANT':
			$res = $this->_client->plant_room_remove($L['guid'], $oid);
			break;
		}

		return $res;

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
		$res = [];
		switch (strtoupper($arg['type'])) {
		case 'INVENTORY':
			$res = $this->_client->inventory_room_modify(
				$oid,
				$arg['name'],
				$L['guid'],
				$arg['quarantine']
			);
			break;
		case 'PLANT':
			$res = $this->_client->plant_room_modify($id, $arg['name'], $L['guid']);
			break;
		}

		// $ret = array();
		// if ($res['success']) {
		// 	$ret = array(
		// 		'status' => 'success',
		// 		'result' => $obj,
		// 	);
		// } else {
		// 	$ret = array(
		// 		'status' => 'failure',
		// 		'result' => $res,
		// 	);
		// }

		return $res;
	}

	/**
	 *
	 */
	function sync($arg)
	{
		$ret = 0;
		$txn = $this->sync_inventory_room($arg);
		$ret = max($ret, $txn);
		$txn = $this->sync_plant_room($arg);
		$ret = max($ret, $txn);
		// Upate something?
		return $ret;
	}

	/**
	 *
	 */
	protected function sync_inventory_room(?array $arg=null)
	{
		$max = 0;

		$arg = $this->_client->_sync_object('sync_inventory_room');
		$res = $this->_client->_curl_exec($arg);
		$res = $res['inventory_room'];
		if (empty($res)) {
			return $max;
		}

		foreach ($res as $x) {

			// API Docs Say this will exist; sometimes it's empty /djb 20170707
			if (empty($x['roomid'])) {
				// syslog(LOG_DEBUG)
				continue;
			}

			// if (empty($x['location'])) {
			// 	continue;
			// 	//print_r($x);
			// 	//throw new \Exception("Inventory Section is Missing Location {$x['roomid']}, assuming default");
			// 	//syslog(LOG_ERR, "Inventory Section is Missing Location {$x['roomid']}, assuming default");
			// 	//$x['location'] = $l['code'];
			// }

			$x['type'] = 'Inventory';
			$this->sync_one($x);

			$max = max($max, $x['transactionid']);
		}

		return $max;

	}

	/**
	 *
	 */
	protected function sync_plant_room(?array $arg=null)
	{
		$max = 0;

		$arg = $this->_client->_sync_object('sync_plant_room');
		$res = $this->_client->_curl_exec($arg);
		$res = $res['plant_room'];
		if (empty($res)) {
			return $max;
		}

		foreach ($res as $x) {

			// API Docs Say this will exist; sometimes it's empty /djb 20170707
			if (empty($x['roomid'])) {
				continue;
			}

			// Skip Shitty data from BioTrack
			// if (empty($x['location'])) {
			// 	continue;
			// 	throw new \Exception("Plant Section is Missing Location {$x['roomid']}, assuming default");
			// 	syslog(LOG_ERR, "Plant Section is Missing Location {$x['roomid']}, assuming default");
			// }

			$x['type'] = 'Plant';
			$this->sync_one($x);

			$max = max($max, $x['transactionid']);

		}

		return $max;

	}


}
