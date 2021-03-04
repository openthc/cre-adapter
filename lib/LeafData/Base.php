<?php
/**
 * Base Class for LeafData Objects
 */

namespace OpenTHC\CRE\LeafData;

class Base
{
	protected $_client;
	protected $_path = '';

	function __construct($c)
	{
		$this->_client = $c;
	}

	function delete($oid)
	{
		$res = $this->_client->call('DELETE', sprintf('%s/%s', $this->_path, $oid));
		return $res;
	}

	/**
	 * Search the Endpoint (GET)
	 */
	function search($arg=null)
	{
		if (empty($arg)) {
			$arg = [];
		}

		$url = sprintf('%s?%s', $this->_path, http_build_query($arg));
		$url = trim($url, '?');
		$res = $this->_client->call('GET', $url);

		return $res;
	}


	/**
	 * @param $x The GUID to GET
	 */
	function single($x)
	{
		$arg = http_build_query(array(
			'f_global_id' => $x,
		));
		$url = sprintf('%s?%s', $this->_path, $arg);
		$res = $this->_client->call('GET', $url);
		if ('success' == $res['status']) {
			$res = $res['result'];
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

}
