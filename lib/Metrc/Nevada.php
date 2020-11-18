<?php
/**
 * Franwell / Metrc Interface for Nevada
 */

namespace OpenTHC\CRE\Metrc;

class Nevada extends \OpenTHC\CRE\Metrc
{
	protected $_api_base = 'https://api-nv.metrc.com';

	protected $_epoch = '2018-01-01';

	static function getObjectList()
	{
		$obj_list = self::$obj_list;
		unset($obj_list['transfer']);
		return $obj_list;
	}

	function setTestMode()
	{
		throw new Exception('Sandbox Not Supported by METRC in Nevada');
	}
}
