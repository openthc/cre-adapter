<?php
/**
 * Product Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Metrc;

class Product extends \OpenTHC\CRE\Metrc\Base
{
	protected $_path = '/items/v1';

	/**
	 *
	 */
	function getTypeList()
	{
		$url = sprintf('%s/categories', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

	/**
	 * Returns a list of Items - Like 'Buds' or 'Plants' or something
	 * @param $path - Specific ID or 'active'*
	 */
	function search($stat=null)
	{
		if (empty($stat)) {
			$stat = 'active';
		}

		$url = sprintf('/items/v1/%s', $stat);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		if (!empty($res['data'])) {
			if (!empty($res['data']['Id'])) {
				// A Single Top-Level Object Was Returned
				// Promote to Array
				$res['data'] = array($res['data']);
			}
		}

		return $res;
	}

}
