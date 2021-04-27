<?php
/**
 * METRC Lot
 */

namespace OpenTHC\CRE\Metrc;

class Lot extends \OpenTHC\CRE\Metrc\Base
{
	protected $_path = '/packages/v1';

	/**
	 *
	 */
	function adjust_reason_list()
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
		$res = $this->_client->curl_exec($req, $arg);
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

}
