<?php
/**
 * Product Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Metrc2023;

class Product extends \OpenTHC\CRE\Metrc2023\Base
{
	protected $_path = '/items/v2';

	/**
	 *
	 */
	function getTypeList()
	{
		$url = sprintf('%s/categories', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		$res = $this->formatResponse($res);
		return $res;
	}

	function addPhoto($name, $data)
	{
		$arg = [];
		$arg['FileName'] = $name;
		$arg['EncodedImageBase64'] = $data;

		$url = sprintf('%s/photo', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $arg ]);
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

		$url = sprintf('%s/%s', $this->_path, $stat);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		$res = $this->formatResponse($res);
		return $res;
	}

}
