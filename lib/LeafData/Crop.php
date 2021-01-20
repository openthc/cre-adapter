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
