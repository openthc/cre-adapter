<?php
/**
 * B2C Sale Interface
*/

namespace OpenTHC\CRE\LeafData;

class B2C_Sale extends \OpenTHC\CRE\LeafData\Base
{
	protected $_path = '/sales';

	function create($x)
	{
		$arg = array('sale' => array($x));
		$res = $this->_client->call('POST', '/sales', $arg);
		return $res;
	}

}
