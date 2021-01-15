<?php
/**
 * Crop Interface
 */

namespace OpenTHC\CRE\LeafData;

class Crop extends \OpenTHC\CRE\LeafData\Base
{
	protected $_path = '/plants';

	/**
	*/
	function create($x)
	{
		$arg = array('plant' => array(0 => $x));
		return $this->_client->call('POST', '/plants', $arg);
	}

	/**
	 * Delete the Specified Crop
	 */
	function delete($x)
	{
		$res = $this->_client->call('DELETE', sprintf('/plants/%s', $x));
		return $res;
	}

	/**
	 * Update the Specified Crop
	 */
	function update($x)
	{
		switch ($x['origin']) {
		case '':
		case 'inventory':
		case 'none':
			$x['origin'] = 'plant';
			break;
		}

		$arg = array('plant' => $x);

		$ret = $this->_client->call('POST', '/plants/update', $arg);

		return $ret;
	}

	/**
	 *
	 */
	function convert($arg)
	{
		$res = $this->_client->call('POST', '/move_plants_to_inventory', $arg);
		return $res;
	}

	/**
	 * Sync this Object
	 */
	function sync($x, $m)
	{
		if (is_string($x)) {
			$x = $this->one($x);
		}

		$rls = new RBE_LeafData_Sync($this->_client);
		$rlsx = new RBE_LeafData_Sync_Plant($rls, $this->_client);
		$r = $rlsx->one($x, $m);

		return $r;
	}

	/**
	*/
	function waste()
	{
		/*
			<select id="reason" class="form-control" name="reason">
				<option value="infestation">Infestation</option>
				<option value="quality control">Quality control</option>
				<option value="unhealthy">Unhealthy</option>
				<option value="mandated">LCB Mandated</option>
			</select>
		*/
	}
}
