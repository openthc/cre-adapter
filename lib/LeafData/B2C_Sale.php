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

	/**
		Sync this Object
	*/
	function sync($x, $m)
	{
		$rls = new RBE_LeafData_Sync($this->_client);
		$rlsx = new RBE_LeafData_Sync_Sale($rls, $this->_client);
		$o = $this->one($x);
		if (empty($o)) {
			throw new Exception('Failed to read Strain from LeafData [RLS-068]');
		}
		$r = $rlsx->one($o, $m);
		return $r;
	}

}
