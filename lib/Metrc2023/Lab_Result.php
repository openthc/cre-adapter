<?php
/**
 * METRC Lab Result
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Metrc2023;

class Lab_Result extends \OpenTHC\CRE\Metrc2023\Base
{
	protected $_path = '/labtests/v1';

	/**
	 *
	 */
	function getStateList()
	{
		$url = sprintf('%s/states', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;

	}

	/**
	 *
	 */
	function getTypeList()
	{
		$url = sprintf('%s/types', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

	/**
	 *
	 */
	function commit(string $pkg)
	{
		// /labtests/v1/results/release
	}

	/**
	 *
	 */
	function create($obj)
	{
		$url = $this->_client->_make_url($this->_path . '/record');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

	/**
	 * Search for Lab Result for Specific Package
	 */
	function search(string $pkg)
	{
		$arg = [
			'packageId' => $pkg
		];
		$url = $this->_client->_make_url($this->_path . '/results', $arg);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);

		return $res;

	}

}
