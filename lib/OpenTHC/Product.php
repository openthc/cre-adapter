<?php
/**
 * OpenTHC Product Adapter
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class Product extends Base
{
	protected $_path = '/product';

	function search($filter=null)
	{
		if (!empty($filter)) {
			$this->_path .= '?' . http_build_query($filter);
		}

		$res = $this->_cre->get($url);
		return $res;
	}

	/**
	*/
	function create($obj)
	{
		$url = $this->_path;
		$res = $this->_cre->post($url, $obj);
		return $res;
	}

	/**
	*/
	function update($oid, $obj)
	{
		$url = sprintf('%s/%s', $this->_path, $oid);
		$res = $this->_cre->post($url, $obj);
		return $res;
	}

	/**
	 * Delete the Product
	 * @param [type] $oid [description]
	 * @param [type] $arg [description]
	 * @return [type] [description]
	 */
	function delete($oid, $arg=null)
	{
		$url = sprintf('%s/%s', $this->_path, $oid);
		$res = $this->_cre->delete($url, $arg);
		return $res;
	}

}
