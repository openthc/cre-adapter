<?php
/**
*/

namespace OpenTHC\CRE\LeafData;

class Product extends \OpenTHC\CRE\LeafData\Base
{
	protected $_path = '/inventory_types';

	function create($x)
	{
		$arg = array('inventory_type' => array($x));
		$res = $this->_client->call('POST', '/inventory_types', $arg);
		return $res;
	}

	function delete($x)
	{
		$res = $this->_client->call('DELETE', sprintf('/inventory_types/%s', $x));
		return $res;
	}

	function update($x)
	{
		$res = $this->_client->call('POST', '/inventory_types/update', $x);
		return $res;
	}

	/**
		Sync this Object
	*/
	function sync($x, $m)
	{
		$rls = new RBE_LeafData_Sync($this->_client);
		$rlsx = new RBE_LeafData_Sync_Product($rls, $this->_client);
		$o = $this->one($x);
		$r = $rlsx->one($o, $m);
		return $r;
	}

}
