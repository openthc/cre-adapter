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
	 * Should this be on a Lot_Delta object?
	 */
	function getAdjustReasonList()
	{
		$url = $this->_client->_make_url('/packages/v1/adjust/reasons');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

	/**
	 * Adjust the Unit of Measure on one or more items
	 * @see https://api-or.metrc.com/Documentation#Packages.post_packages_v1_adjust
	 */
	function adjust($arg)
	{
		$url = $this->_client->_make_url('/packages/v1/adjust');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, $arg);
		return $res;
	}


	/**
	 * Finish Array of Packages
	 */
	function finish($arg)
	{
		$url = $this->_client->_make_url('/packages/v1/finish');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, $arg);
		return $res;
	}

	/**
	 * Finish Undo for Array of Lots
	 */
	function finish_undo($arg)
	{
		$url = $this->_client->_make_url('/packages/v1/unfinish');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, $arg);
		return $res;
	}

	/**
	 * Convert Lot to Plant
	 */
	function plant($arg)
	{
		$url = $this->_client->_make_url('/packages/v1/create/plantings');
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
		return $res;
	}

}
