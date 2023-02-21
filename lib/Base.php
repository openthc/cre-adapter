<?php
/**
 * A Base Class for an Compliance Reporting Engine ("CRE")
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE;

class Base
{
	protected $_api_base = '';
	protected $_api_host = '';

	protected $obj_list;

	protected $_cfg;
	protected $_err;
	protected $_err_text;
	protected $_inf;
	protected $_raw;
	protected $_req_head = [];
	protected $_res;

	protected $_tz;  // Timezone

	protected $_Company;

	protected $_License;

	const ENGINE = null;

	/**
	 * Array of Arguments
	 */
	function __construct(array $cfg)
	{
		if (empty($cfg)) {
			throw new \Exception('Invalid Parameters [LRB-037]');
		}

		if ( ! is_array($cfg)) {
			throw new \Exception('Invalid Parameters [LRB-041]');
		}

		$this->_cfg = $cfg;

		$this->_api_base = rtrim($cfg['server'], '/');

		if (empty($this->_api_host)) {
			$this->_api_host = parse_url($this->_api_base, PHP_URL_HOST);
		}

		// Timezone
		if (empty($cfg['tz'])) {
			$cfg['tz'] = date_default_timezone_get();
		}

		if (is_object($cfg['tz'])) {
			$this->_tz = $cfg['tz'];
		} elseif (is_string($cfg['tz'])) {
			$this->_tz = new \DateTimezone($cfg['tz']);
		}

	}


	/**
	 *
	 */
	function getConfig()
	{
		return $this->_cfg;
	}


	/**
	 *
	 */
	function getCompany()
	{
		return $this->_Company;
	}


	/**
	 *
	 */
	function getLicense()
	{
		return $this->_License;
	}

	/**
	 * Set License
	 * @param array $l License Data Array
	 */
	function setLicense($l)
	{
		if (is_array($l)) {
			// Perfect
		} elseif (is_object($l)) {
			$l = $l->toArray(); // Hope it has this routine!
		} elseif (is_string($l)) {
			$l = [
				'id' => $l,
				'code' => $l,
				'guid' => $l,
			];
		} else {
			throw new \Exception('Invalid Parameters [CLB-058]');
		}

		if (empty($l['id'])) {
			throw new \Exception('License Missing ID [CLB-062]');
		}

		if (empty($l['code'])) {
			throw new \Exception('License Missing CODE [CLB-066]');
		}

		if (empty($l['guid'])) {
			throw new \Exception('License Missing GUID [CLB-070]');
		}

		$this->_License = $l;

		return $this->_License;

	}

	/**
	 *
	 */
	function getObjectList()
	{
		if ( ! empty($this->obj_list)) {
			return $this->obj_list;
		}

		throw new \Exception('Not Implemented');
	}

	/**
	 * Everyone else should implement this
	 */
	function ping()
	{
		return [
			'code' => 501,
			'data' => null,
			'meta' => [ 'detail' => 'not implemented' ],
		];
	}

	/**
	 * GET Helper
	 */
	function get($url)
	{
		$req = $this->_curl_init($url);

		// Add Headers?
		$head = [];
		foreach ($this->_req_head as $k => $v) {
			$head[] = sprintf('%s: %s', $k, $v);
		}
		// if ('auto' != $type) {
		// 	$head[] = sprintf('content-type: %s', $type);
		// }
		curl_setopt($req, CURLOPT_HTTPHEADER, $head);

		$this->_raw = curl_exec($req);
		$this->_inf = curl_getinfo($req);
		$this->_res = json_decode($this->_raw, true);
		$this->_err = json_last_error();
		$this->_err_text = json_last_error_msg();

		return $this->_res;
	}

	/**
	 * POST Helper
	 */
	function post($url, $data, $type='auto')
	{
		$req = $this->_curl_init($url);

		curl_setopt($req, CURLOPT_POST, true);
		curl_setopt($req, CURLOPT_POSTFIELDS, $data);

		// Add Headers?
		$head = [];
		foreach ($this->_req_head as $k => $v) {
			$head[] = sprintf('%s: %s', $k, $v);
		}
		if ('auto' != $type) {
			$head[] = sprintf('content-type: %s', $type);
		}
		curl_setopt($req, CURLOPT_HTTPHEADER, $head);

		$this->_raw = curl_exec($req);
		$ret = json_decode($this->_raw, true);
		$this->_err = json_last_error();
		$this->_err_text = json_last_error_msg();

		return $ret;
	}

	/**
	 * Replicated from openthc/common
	 */
	function _curl_init($uri)
	{
		$req = curl_init($uri);

		curl_setopt($req, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

		// Booleans
		curl_setopt($req, CURLOPT_AUTOREFERER, true);
		curl_setopt($req, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($req, CURLOPT_COOKIESESSION, false);
		curl_setopt($req, CURLOPT_CRLF, false);
		curl_setopt($req, CURLOPT_FAILONERROR, false);
		curl_setopt($req, CURLOPT_FILETIME, true);
		curl_setopt($req, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($req, CURLOPT_FORBID_REUSE, false);
		curl_setopt($req, CURLOPT_FRESH_CONNECT, false);
		curl_setopt($req, CURLOPT_HEADER, false);
		curl_setopt($req, CURLOPT_NETRC, false);
		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($req, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($req, CURLINFO_HEADER_OUT,true);

		// curl_setopt($req, CURLOPT_BUFFERSIZE, 16384);
		curl_setopt($req, CURLOPT_CONNECTTIMEOUT, 240);
		curl_setopt($req, CURLOPT_MAXREDIRS, 0);
		// curl_setopt($req, CURLOPT_SSL_VERIFYHOST, 0);
		// curl_setopt($req, CURLOPT_SSLVERSION, 3); // 2, 3 or GnuTLS
		curl_setopt($req, CURLOPT_TIMEOUT, 600);

		curl_setopt($req, CURLOPT_USERAGENT, 'OpenTHC/v420.22.297');

		return $req;
	}

	/**
	 * Normalize record data array and return a hash
	 * @param array $a Data Object to create hash from
	 * @return string sha256 hash
	 */
	static function objHash($obj, $key_list=[])
	{
		if ( ! is_array($obj)) {
			if (is_object($obj)) {
				if (method_exists($obj, 'toArray')) {
					$obj = $obj->toArray();
				} else {
					$obj = json_decode(json_encode($obj), true);
				}
			}
		}

		$rec = [];

		if (empty($key_list)) {
			$key_list = array_keys($obj);
		}

		foreach ($key_list as $k) {
			$rec[$k] = $obj[$k];
		}

		$rec = self::ksort_r($rec);

		return hash('sha256', json_encode($rec));

	}

	// Legacy Name
	static function recHash($obj, $key_list=[])
	{
		return self::objHash($obj, $key_list);
	}

	/*
	 * Key-Sort Array, Recursively
	 * replicated from openthc/common
	 */
	static function ksort_r($a)
	{
		static $depth = 0;

		foreach ($a as $k => $v) {
			if (is_array($v)) {
				$a[$k] = self::ksort_r($v);
			}
		}

		ksort($a);

		return $a;
	}

}
