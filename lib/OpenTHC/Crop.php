<?php
/**
 * OpenTHC Crop Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class Crop extends Base
{
	protected $_path = '/crop';

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
	 * Finish the Crop
	 * Either just marking it done because of harvest or other reason
	 */
	function finish($oid, $arg)
	{
		// $pc = new \OpenTHC\CRE\OpenTHC\Crop_Collect($this->_cre);
		// return $pc->finish($pid, $obj);
		$url = sprintf('%s/%s/finish', $this->_path, rawurlencode($oid));
		$res = $this->_cre->post($url, $arg);
		return $res;
	}

}
