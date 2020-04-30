<?php
/**
 * A Base Class for an RBE
 */

namespace OpenTHC\CRE\Adapter;

class Base
{
	protected $_c; // Guzzle Connection;

	protected $_err;
	protected $_inf; // @deprecated
	protected $_raw;
	protected $_req_head = [];
	protected $_res;

	protected $_License;

	const ENGINE = null;

	/**
	 * Array of Arguments
	 */
	function __construct($cfg)
	{
		if (empty($cfg['host'])) {
			$cfg['host'] = parse_url($cfg['server'], PHP_URL_HOST);
		}

		$jar = new \GuzzleHttp\Cookie\CookieJar();

		if (!empty($cfg['cookie'])) {
			$c = new \GuzzleHttp\Cookie\SetCookie(array(
				'Domain' => $cfg['host'],
				'Name' => $cfg['cookie']['name'],
				'Value' => $cfg['cookie']['value'],
				'Secure' => true,
				'HttpOnly' => true,
			));
			$jar->setCookie($c);
		}

		$cfg = array(
			'base_uri' => $cfg['server'],
			'allow_redirects' => false,
			'cookies' => $jar,
			'headers' => array(
				'user-agent' => 'OpenTHC/420.20.121',
			),
			'http_errors' => false,
			'verify' => false,
		);

		$this->_c = new \GuzzleHttp\Client($cfg);

	}

	/**
	 * Get Configuration of a Specific Engine
	 */
	function getConfig($key)
	{
		$dir = __DIR__;
		$dir = dirname($dir);

		$ini_file = sprintf('%s/etc/cre.ini', $dir);
		$ini_data = parse_ini_file($ini_file, true, INI_SCANNER_RAW);

		$cfg = $ini_data[$key];

		return $cfg;
	}

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
			throw new \Exception('Invalid Parameters [LRB#066]');
		}

		if (empty($l['id'])) {
			throw new \Exception('License Missing ID');
		}

		if (empty($l['code'])) {
			throw new \Exception('License Missing CODE');
		}

		if (empty($l['guid'])) {
			throw new \Exception('License Missing GUID');
		}

		$this->_License = $l;

		return $this->_License;

	}

	/**
	 * Everyone else should implement this
	 */
	function ping()
	{
		return [
			'data' => null,
			'meta' => [ 'detail' => 'not implemented' ],
		];
	}

	function get($url)
	{
		$req = new \GuzzleHttp\Psr7\Request('GET', $url);
		// Add Headers?
		foreach ($this->_req_head as $k => $v) {
			$req = $req->withHeader($k, $v);
		}

		$this->_res = $this->_c->send($req);
		$this->_raw = $this->_res->getBody()->getContents();
		$ret = json_decode($this->_raw, true);
		$this->_err = json_last_error();
		$this->_err_msg = json_last_error_msg();
		return $ret;
	}

	function post($url, $data, $type='auto')
	{
		$req = new \GuzzleHttp\Psr7\Request('POST', $url, $data);
		// Add Headers?
		foreach ($this->_req_head as $k => $v) {
			$req = $req->withHeader($k, $v);
		}

		$this->_res = $this->_c->send($req);
		$this->_raw = $this->_res->getBody()->getContents();
		$ret = json_decode($this->_raw, true);
		$this->_err = json_last_error();
		$this->_err_msg = json_last_error_msg();

		return $ret;
	}

	static function getEngineList()
	{
		$dir = __DIR__;
		$dir = dirname($dir);

		$ini_file = sprintf('%s/etc/cre.ini', $dir);
		$ini_data = parse_ini_file($ini_file, true, INI_SCANNER_RAW);

		return $ini_data;
	}

	/**
	 * Normalize record data array and return a hash
	 * @param [type] $a [description]
	 * @return [type] [description]
	 */
	static function recHash($a)
	{
		if (!is_array($a)) {
			if (is_object($a)) {
				if (method_exists($a, 'toArray')) {
					$a = $a->toArray();
				}
			}
		}
		$a = self::ksort_r($a);
		return md5(json_encode($a));
	}

	/*
	 * Key-Sort Array, Recursively
	 */
	static function ksort_r($a)
	{
		foreach ($a as &$v) {
			if (is_array($v)) {
				self::ksort_r($v);
			}
		}

		return ksort($a);
	}

}
