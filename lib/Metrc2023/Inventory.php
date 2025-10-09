<?php
/**
 * METRC Inventory
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Metrc2023;

class Inventory extends \OpenTHC\CRE\Metrc2023\Base
{
	protected $_path = '/packages/v2';

	/**
	 * Should this be on a Inventory_Adjust object?
	 */
	function getAdjustReasonList()
	{
		$url = $this->_client->_make_url('/packages/v2/adjust/reasons');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

	// 	$res = $this->packageTypeList();
	// function packageTypeList()
	function getTypeList()
	{
		$url = $this->_client->_make_url('/packages/v2/types');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;

	}

	/**
	 * Adjust the Unit of Measure on one or more items
	 */
	function adjust($arg)
	{
		$url = $this->_client->_make_url('/packages/v2/adjust');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, $arg);
		return $res;
	}


	/**
	 * Finish Array of Packages
	 */
	function finish($arg)
	{
		$url = $this->_client->_make_url('/packages/v2/finish');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, $arg);
		return $res;
	}

	/**
	 * Finish Undo for Array of Inventory
	 */
	function finish_undo($arg)
	{
		$url = $this->_client->_make_url('/packages/v2/unfinish');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, $arg);
		return $res;
	}

	/**
	 * Convert Inventory to Plant
	 */
	function plant($arg)
	{
		$url = $this->_client->_make_url('/packages/v2/create/plantings');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, $arg);
		return $res;
	}

	/**
	 * [search description]
	 * @return [type] [description]
	 */
	function search($arg=null)
	{
		$url = sprintf('%s/active', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		$res = $this->formatResponse($res);
		return $res;
	}

}
