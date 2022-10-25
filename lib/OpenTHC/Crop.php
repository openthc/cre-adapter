<?php
/**
 * OpenTHC Crop Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class Crop extends Base
{
	function search($filter=null)
	{
		$url = '/plant';

		if (!empty($filter)) {
			$url.= '?' . http_build_query($filter);
		}

		$res = $this->_cre->get($url);
		return $res;
	}

	function create($obj)
	{
		$url = '/plant';
		$res = $this->_cre->post($url, $obj);
		return $res;
	}

	function delete($oid, $arg=null)
	{
		$url = sprintf('/plant/%s', rawurlencode($oid));
		$res = $this->_cre->delete($url, array('json' => $arg));
		return $res;
	}

	function single($obj)
	{
		$url = sprintf('/plant/%s', rawurlencode($obj));
		$res = $this->_cre->get($url);
		return $res;
	}

	/**
		@param $pid The Plant ID
		@param $obj The Plant Attributes to Change
	*/
	function update($pid, $obj)
	{
		$url = sprintf('/plant/%s', rawurlencode($pid));
		$res = $this->_cre->post($url, $obj);
		return $res;
	}

	/**
	 * Down the rabbit hole
	 * @return [type] [description]
	 */
	function collect($pid=null, $obj=null)
	{
		$pc = new \OpenTHC\CRE\OpenTHC\Crop_Collect($this->_cre);
		if (empty($obj) && empty($pid)) return $pc;
		return $pc->plant($pid, $obj);
	}

	/**
	 * Down the rabbit hole
	 * @return [type] [description]
	 */
	function finish($pid, $obj)
	{
		$pc = new \OpenTHC\CRE\OpenTHC\Crop_Collect($this->_cre);
		return $pc->finish($pid, $obj);
	}

}
