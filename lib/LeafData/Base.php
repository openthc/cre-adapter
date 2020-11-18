<?php
/**
 * Base Class for LeafData
 */

namespace OpenTHC\CRE\LeafData;

class Base extends \OpenTHC\CRE\Base
{
	protected $_client;
	protected $_path = '';

	function __construct($c)
	{
		$this->_client = $c;
	}

	/**
		Find "all", need to maybe pass pages
	*/
	function all($arg=null)
	{
		if (empty($arg)) {
			$arg = array();
		}

		$url = sprintf('%s?%s', $this->_path, http_build_query($arg));
		$url = trim($url, '?');
		$res = $this->_client->call('GET', $url);
		return $res;
	}

	/**
		@param $x The GUID to GET
	*/
	function one($x)
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
