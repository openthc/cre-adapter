<?php
/**
 * Transfer Interface
*/

namespace OpenTHC\CRE\LeafData;

class B2B_Sale extends \OpenTHC\CRE\LeafData\Base
{
	protected $_path = '/inventory_transfers';

	function create($x)
	{
		$arg = array('inventory_transfer' => array($x));
		$res = $this->_client->call('POST', '/inventory_transfers', $arg);
		return $res;
	}

	function delete()
	{
		// NO-OP
		return false;
	}

	function update($arg)
	{
		$res = $this->_client->call('POST', '/inventory_transfers/update', $arg);
		return $res;
	}

	/**
		Sync this Object
		@param $x Object GUID
		@param $m Message for Sync-Log
	*/
	function sync($x, $m)
	{
		$try_idx = 0;
		$try_max = 3;

		do {
			$try_idx++;

			$rls = new RBE_LeafData_Sync($this->_client);
			$rlsx = new RBE_LeafData_Sync_Transfer($rls, $this->_client);
			$o = $this->one($x);
			if (!empty($o)) {
				$r = $rlsx->one($o, $m);
				return $r;
			}
			// $r = $rlsx->one($o, $m);
		} while ($try_idx < $try_max);

		throw new Exception(sprintf('Cannot Sync Transfer "%s" [RLT-045]', $x));


	}

	function addDriver() { }

	/**
		@param $guid GUID
		@param $q Quantity from Lot to Add
		@param $price - Full Price
		@param $for_extract = false - to indicate if this is for extraction

	*/
	function addLot($guid, $q, $price, $extract=false, $sample=null, $sample_type=null, $retest=null)
	{
		$this->_transfer_lot_list[] = array(

		);

	}

	/**
		@param $g The Global ID of the Manifest
	*/
	function getItems($g)
	{
		$res = $this->_client->call('GET', '/inventory_transfers?f_global_id=' . $g);
		return $res;
	}

	/**
		@param $m I think it's the Manifest ID
	*/
	function setInTransit($m)
	{
		$data = array(
			'global_id' => $m,
		);
		$res = $this->_client->call('POST', '/inventory_transfers/api_in_transit', $data);
		return $res;
	}

	function receive($arg)
	{
		$res = $this->_client->call('POST', '/inventory_transfers/api_receive', $arg);
		return $res;
	}

	/**
	*/
	function void($m)
	{
		$arg = array('global_id' => $m);
		$res = $this->_client->call('POST', '/inventory_transfers/void', $arg);
		return $res;
	}

}
