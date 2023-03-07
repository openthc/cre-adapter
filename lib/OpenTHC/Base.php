<?php
/**
 * Base Class for OpenTHC Data things
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class Base
{
	protected $_cre;

	protected $_path = '';

	function __construct($cre)
	{
		$this->_cre = $cre;
	}

	/**
	 * Create a Thing
	 *
	 * @param array Thing to Create
	 */
	function create($obj)
	{
		if (empty($this->_path)) {
			throw new \Exception('Invalid State [COB-029]');
		}

		$url = $this->_path;
		$res = $this->_cre->post($url, $obj);

		return $res;

	}

	/**
	 * Delete a Thing
	 *
	 * @param string $oid Thing Identifier
	 * @param null $arg [description]
	 * @return array OpenTHC Response
	 */
	function delete(string $oid, $arg=null)
	{
		if (empty($this->_path)) {
			throw new \Exception('Invalid State [COB-049]');
		}

		$url = sprintf('%s/%s', $this->_path, rawurlencode($oid));
		$ret = $this->_cre->delete($url, $arg);

		return $ret;

	}

	// @deprecated function from ages ago
	// function all($filter=null) { return $this->search($filter); }

	/**
	 * Search for Things
	 */
	function search($filter=null)
	{
		if (empty($this->_path)) {
			throw new \Exception('Invalid State [COB-065]');
		}

		$url = $this->_path;
		if (!empty($filter)) {
			$url.= '?' . http_build_query($filter);
		}
		$res = $this->_cre->get($url);

		return $res;

	}

	/**
	 * Get One Thing
	 */
	function single(string $oid)
	{
		if (empty($this->_path)) {
			throw new \Exception('Invalid State [COB-084]');
		}

		$url = sprintf('%s/%s', $this->_path, rawurlencode($oid));
		$ret = $this->_cre->get($url);

		return $ret;

	}

	/**
	 * Update a Thing
	 *
	 * @param array Thing ID
	 * @param array of Thing data
	 */
	function update(string $oid, $obj)
	{
		if (empty($this->_path)) {
			throw new \Exception('Invalid State [COB-103]');
		}

		if (empty($oid)) {
			throw new \Exception('Invalid Object ID [COB-110]');
		}

		$url = sprintf('%s/%s', $this->_path, rawurlencode($oid));
		$res = $this->_cre->post($url, $obj);

		return $res;
	}

}
