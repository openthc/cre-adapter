<?php
/**
 * Vehicle in BioTrack
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\BioTrack;

class Vehicle extends \OpenTHC\CRE\BioTrack\Base
{
	/**
	 * @param array $opt Vehicle Description
	 *
	 * @todo Error Check Each Option
	 */
	function create(array $obj)
	{
		$arg = array(
			'action' => 'vehicle_add',
		);
		$arg = array_merge($arg, $obj);
		return $this->_client->_curl_exec($arg);
		// return [
		// 	'data' => 'success',
		// ];
	}

	/**
	 * @param string $vehicle ID
	 */
	function delete(string $oid)
	{
		$arg = array(
			'action' => 'vehicle_remove',
			'vehicle_id' => $vid,
		);

		return $this->_client->_curl_exec($arg);

	}

	/**
	 * @param string $oid Object ID
	 * @param array  $opt Vehicle Description
	 */
	function update(string $oid, array $obj)
	{
		$arg = array(
			'action' => 'vehicle_modify',
		);
		$arg = array_merge($arg, $obj);
		return $this->_client->curl_exec($arg);

	}
}
