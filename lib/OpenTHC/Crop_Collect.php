<?php
/**
 * OpenTHC Crop Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class Crop_Collect extends Base
{
	/**
	 *
	 */
	function commit($pcid, $obj)
	{
		$url = sprintf('/plant-collect/%s/commit', rawurlencode($pcid));
		$res = $this->_cre->post($url, $obj);
		return $res;
	}

	/**
	 *
	 */
	function plant($pid, $obj)
	{
		$url = sprintf('/crop/%s/collect', rawurlencode($pid));
		$res = $this->_cre->post($url, $obj);
		return $res;
	}

	/**
	 *
	 */
	function wet()
	{

	}

	/**
	 *
	 */
	function dry()
	{

	}

	function finish()
	{

	}

}
