<?php
/**
 * METRC Lot
 */

namespace OpenTHC\CRE\Metrc;

class Lot extends \OpenTHC\CRE\Metrc\Base
{
	protected $_path = '/packages/v1';

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
