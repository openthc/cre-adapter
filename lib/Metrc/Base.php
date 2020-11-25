<?php
/**
 * Base Class for METRC objects
 */

namespace OpenTHC\CRE\Metrc;

class Base extends \OpenTHC\CRE\Base
{
	protected $_client;
	protected $_License;
	protected $_path = '';

	function __construct($c)
	{
		$this->_client = $c;
		$this->_License = $c->getLicense();
	}

	/**
	 * Find "all", need to maybe pass pages
	 */
	function search($arg=null)
	{
		if (empty($arg)) {
			$arg = [];
		}
		$url = sprintf('%s', $this->_path);
		$url = $this->_client->_make_url($url, $arg);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

	/**
	 * Create one of the the OBJECT
	 */
	function create($obj)
	{
		$url = sprintf('%s/create', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

	/**
	 * @param $x The GUID to GET
	 */
	function single($x) {

		$url = sprintf('%s/%s', $this->_path, $x);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, $arg);

		// var_dump($url);
		// var_dump($res);

		//$url = sprintf('%s?%s', $this->_path, $arg);
		if (200 == $res['code']) {
			$res = $res['data'];
			if (!empty($res['data'])) {
				$res = $res['data'];
				if (is_array($res)) {
					if (1 == count($res)) {
						return $res[0];
					}
				}
			}
		}

		return null;
	}

	/**
	 * Update OBJECT
	 * @param $obj Object Descriptor
	 */
	function update($obj)
	{
		$url = sprintf('%s/update', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

}
