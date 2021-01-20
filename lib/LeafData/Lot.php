<?php
/**
 * An Inventory Lot
 */

namespace OpenTHC\CRE\LeafData;

class Lot extends \OpenTHC\CRE\LeafData\Base
{
	protected $_path = '/inventories';

	function create($x)
	{
		$arg = array('inventory' => array($x));
		$res = $this->_client->call('POST', '/inventories', $arg);
		return $res;
	}

	function update($x)
	{
		$arg = array('inventory' => $x);
		$res = $this->_client->call('POST', '/inventories/update', $arg);
		return $res;
	}

	function delete($x)
	{
		$res = $this->_client->call('DELETE', sprintf('/inventories/%s', $x));
		return $res;
	}

	function convert($x)
	{
		$arg = array('conversion' => $x);
		$res = $this->_client->call('POST', '/conversions/create', $arg);
		return $res;
	}

	function split($x)
	{
		$res = $this->_client->call('POST', '/split_inventory', $x);
		return $res;
	}

	/**
		@param $inv Inventory Global ID
		@param $qty Crop Count to Create
		@todo rename to converToCrop()
	*/
	function plant($arg)
	{
		$res = $this->_client->call('POST', '/move_inventory_to_plants', $arg);
		return $res;
	}

}
