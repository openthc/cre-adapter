<?php
/**
 * Crop Interface
 *
 * SPDX-License-Identifier: MIT
 *
 * @note You get a 401 Error on Plants when not allowed
 */

namespace OpenTHC\CRE\Metrc2023;

class Crop extends \OpenTHC\CRE\Metrc2023\Base
{
	protected $_path = '/plants/v1';

	function change($obj)
	{
		$url = $this->_client->_make_url('/plants/v1/changegrowthphases');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

	function create($obj)
	{
		$url = sprintf('%s/create/plantings', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

	/**
	 * Destroy a Crop
	 * @param [type] $p Plant ID
	 * @param [type] $opt Array
	 * @return [type] [description]
	 */
	// function destroy($p, $opt)
	function destroy($arg)
	{
		// if (empty($opt['date'])) {
		// 	$opt['date'] = date('Y-m-d');
		// }

		// $arg = array(
		// 	array(
		// 		'Id' => $p,
		// 		'ReasonNote' => sprintf('%s: %s', $opt['code'], $opt['note']),
		// 		'ActualDate' => $opt['date'],
		// 	)
		// );

		$url = $this->_client->_make_url('/plants/v1/destroyplants');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, $arg);
		return $res;
	}

	/**
	 * @param $arg Array of Crop Descriptors
	 */
	function move($arg)
	{
		$url = '/plants/v1/moveplants';
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, $arg);
		return $res;
	}

	/**
	 * Search All Crop
	 * @param $stat {id} or {label} or 'vegetative'*, 'flowering', 'onhold', 'inactive'
	 * @return [type] [description]
	 */
	function search($stat=null)
	{
		if (empty($stat)) {
			$stat = 'flowering';
		}

		$url = sprintf('/plants/v1/%s', $stat);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;

	}

}
