<?php
/**
 * Section Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class Section extends Base
{
	/**
	*/
	function search($filter=null)
	{
		$url = '/section';

		if (!empty($filter)) {
			$url.= '?' . http_build_query($filter);
		}

		$ret = $this->_cre->get($url);
		return $ret;
	}

	/**
	*/
	function single($oid)
	{
		$url = sprintf('/section/%s', rawurlencode($oid));
		$ret = $this->_cre->get($url);
		return $ret;
	}

	/**
	*/
	function create($obj)
	{
		$ret = $this->_cre->post('/section', $obj);
		return $ret;
	}

	/**
	 * Delete the Section
	 * @param [type] $oid [description]
	 * @param [type] $arg [description]
	 * @return [type] [description]
	 */
	function delete($oid, $arg=null)
	{
		$ret = $this->_cre->delete(sprintf('/section/%s', $oid));
		return $ret;
	}

	/**
	*/
	function update($oid, $obj)
	{
		$url = sprintf('/section/%s', rawurlencode($oid));
		$ret = $this->_cre->post($url, $obj);
		return $ret;
	}

}
