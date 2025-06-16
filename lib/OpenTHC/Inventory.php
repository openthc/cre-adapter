<?php
/**
 * Inventory Adapter
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class Inventory extends Base
{
	protected $_path = '/inventory';

	/**
	 * @param $oid Inventory Lot to Adjust
	 */
	function adjust(string $oid, $arg)
	{
		$url = sprintf('%s/%s/adjust', $this->_path, rawurlencode($oid));
		$res = $this->_cre->post($url, $arg);
		return $res;
	}

	/**
	 *
	 */
	function convert()
	{
		throw new \Exception('Not Implemented [ROL-0398]');
	}

	/**
	 * Convert stuff into this one?
	 *
	 * @param $source_spec
	 */
	function convertFrom()
	{

	}

	/**
	 * Convert this to something else?
	 *
	 * @param $output_spec
	 */
	function convertTo()
	{

	}

	/**
	 * Legacy Alias
	 */
	function destroy(string $oid, $arg)
	{
		return $this->delete($oid, $arg);
	}

}
