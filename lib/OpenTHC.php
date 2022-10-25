<?php
/**
 * Interface to the OpenTHC (or OpenTHC Like) CRE
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE;

class OpenTHC extends \OpenTHC\CRE\Base
{
	const ENGINE = 'openthc';

	private $_c; // Client Connection
	private $_cfg; // Configuration Array

	private $sid;

	protected $_api_base;
	protected $_api_host;

	public $_api_version;

	/**
	 * Array of Arguments
	 */
	function __construct($cfg)
	{
		$this->_cfg = $cfg;

		$this->_api_base = $cfg['server'];
		$this->_api_host = parse_url($cfg['server'], PHP_URL_HOST);
		$this->_api_version = $cfg['epoch'];
		$this->sid = $cfg['sid'];
		$this->_init_api();
	}

	/**
	 *
	 */
	protected function _init_api()
	{
		$jar_secure = true;
		if ('http' == parse_url($this->_api_base, PHP_URL_SCHEME)) {
			$jar_secure = false;
		}

		// @todo Make this Session Persistent?
		$jar = new \GuzzleHttp\Cookie\CookieJar();
		if (!empty($this->sid)) {
			$c = new \GuzzleHttp\Cookie\SetCookie(array(
				'Domain' => $this->_api_host,
				'Name' => 'openthc',
				'Value' => $this->sid,
				'Secure' => $jar_secure,
				'HttpOnly' => true,
			));
			$jar->setCookie($c);
		}

		$cfg = array(
			'base_uri' => $this->_api_base,
			'allow_redirects' => false,
			'cookies' => $jar,
			'headers' => array(
				'user-agent' => 'OpenTHC/CRE/Adapter v420.22.297',
			),
			'http_errors' => false,
			'verify' => false,
		);

		// Override Host Header Here
		// @see https://github.com/guzzle/guzzle/issues/1678#issuecomment-281921604
		// $host = $this->_api_host;
		// $ghhs = \GuzzleHttp\HandlerStack::create();
		// $ghhs->push(\GuzzleHttp\Middleware::mapRequest(function (\Psr\Http\Message\RequestInterface $R) use ($host) {
		// 	return $R->withHeader('host', $host);
		// }));
		// $cfg['handler'] = $ghhs;

		$this->_c = new \GuzzleHttp\Client($cfg);
	}

	function setLicense($x)
	{
		$l0 = $this->_License;
		parent::setLicense($x);
		$l1 = $this->_License;

		if ($l0['id'] != $l1['id']) {
			$this->auth([
				'cre' => $this->_cfg['code'],
				'program' => $this->_cfg['service-key'],
				'service' => $this->_cfg['service-key'],
				'company' => $this->_cfg['company'],
				'license' => $this->_License['id'],
				// 'license-key' =>
			]);
		}

	}

	/**
	 * Format Error
	 */
	function formatError($e)
	{
		if (is_array($e)) {
			if (!empty($e['detail'])) {
				return $e['detail'];
			}
		}

		return json_encode($e, JSON_PRETTY_PRINT);

	}

	/**
	 *
	 */
	function listSyncObjects()
	{
		return array(
			'license' => 'License',
			'section' => 'Section',
			'product' => 'Product',
			'variety' => 'Variety',
			'crop' => 'Crop',
			'lot' => 'Lot',
			'b2b' => 'B2B Sales',
			'b2c' => 'B2C Sales'
		);
	}


	/**
	 * HTTP GET Utility
	 */
	function get($url)
	{
		$res = $this->request('GET', $url);

		$ret = null;
		switch ($res->getStatusCode()) {
		case 200:
		case 201:
		case 403:
		case 404:
		case 410:
		case 423:
			$ret = json_decode($res->getBody(), true);
			$ret['code'] = $res->getStatusCode();
			break;
		default:
			// _exit_text($res->getStatusCode() . ': ' . $res->getBody());
			throw new \Exception('Invalid Response from OpenTHC [LRO-152]');
		}

		return $ret;
	}

	/**
	 * HTTP HEAD Utility
	 */
	function head($url)
	{
		$res = $this->request('HEAD', $url);
		return $res;
	}

	/**
	 * HTTP POST Utility
	 *
	 * @param string $type is un-used
	 */
	function post($url, $arg, $type='multipart/form-data')
	{
		$opt = [
			'form_params' => $arg,
		];

		$res = $this->request('POST', $url, $opt);

		$hsc = $res->getStatusCode();
		$raw = $res->getBody()->getContents();

		$ret = null;
		switch ($hsc) {
		case 200:
		case 201:
		case 202:
		case 403:
		case 404:
		case 409:
		case 410:
			$ret = json_decode($raw, true);
			$ret['code'] = $hsc;
			break;
		default:
			throw new \Exception(sprintf('Invalid Response Code: %03d from OpenTHC [LRO-193]', $hsc));
		}

		return $ret;
	}

	/**
	 * HTTP PATCH utility
	 */
	function patch($url, $arg)
	{
		$res = $this->request('PATCH', $url, [ 'json' => $arg ]);

		// Copied from $this->post() /mbw
		$hsc = $res->getStatusCode();

		$ret = null;
		switch ($hsc) {
		case 200:
			$ret = json_decode($res->getBody(), true);
			$ret['code'] = $hsc;
			break;
		default:
			$buf = $res->getBody()->getContents();
			var_dump($buf);
			throw new \Exception(sprintf('Invalid Response Code: %03d from OpenTHC [LRO-218]', $hsc));
		}

		return $ret;
	}

	/**
	 * HTTP DELETE Utility
	 */
	function delete($url, $arg=null)
	{
		$opt = [
			// 'form_params' => $arg,
		];
		$opt = array_merge($opt, $arg ?? []);

		$res = $this->request('DELETE', $url, $opt);

		$hsc = $res->getStatusCode();
		$raw = $res->getBody()->getContents();

		$ret = null;
		switch ($hsc) {
		case 200:
		case 202:
		case 204:
		case 403:
		case 404:
		case 410:
		case 423:
			$ret = json_decode($raw, true);
			$ret['code'] = $hsc;
			break;
		default:
			throw new \Exception(sprintf(_('Invalid Response Code "%03d" from OpenTHC [LRO-250]'), $hsc));
		}

		return $ret;
	}

	/**
	 * Common Request Handler
	 */
	function request($v, $u, $o=[])
	{
		$o = array_merge($o, [
			'headers' => [
				'openthc-service' => $this->_cfg['service-key'], // v1
				'openthc-company' => $this->_cfg['company'],
				'openthc-license' => $this->_License['id'],
			]
		]);

		return $this->_c->request($v, $u, $o);

	}


	/**
	 * Authentication Interfaces
	 */
	function auth($p)
	{
		$r = $this->post('/auth/open', $p);
		return $r;
	}

	/**
	 * Ping the Conenction
	 */
	function ping()
	{
		$r = $this->get('/auth/ping');
		return $r;
	}


	/**
	 * Get the Company interface
	 */
	function company()
	{
		// return new RBE_OpenTHC_Company($this->_c);
		//$r = $this->_c->get('/config/company');
		//echo $r->getBody()->__toString();
		//return json_decode($r->getBody(), true);
		return new \OpenTHC\CRE\OpenTHC\Company($this);
	}

	/**
	 * Get the Contact interface
	 */
	function contact()
	{
		//$r = $this->_c->get('/config/contact');
		//echo $r->getBody()->__toString();
		//return json_decode($r->getBody(), true);
		return new \OpenTHC\CRE\OpenTHC\Contact($this);
	}

	/**
	 * Get the License interface
	 */
	function license()
	{
		//$r = $this->_c->get('/config/license');
		//echo $r->getBody()->__toString();
		//return json_decode($r->getBody(), true);
		return new \OpenTHC\CRE\OpenTHC\License($this);
	}


	/**
	 * Get the B2B interface
	 */
	function b2b()
	{
		return new \OpenTHC\CRE\OpenTHC\B2B($this);
	}

	/**
	 * Retail
	 */
	function b2c()
	{
		return new \OpenTHC\CRE\OpenTHC\B2C($this);
	}

	/**
	 * Get the Batch interface
	 */
	// function batch()
	// {
	// 	return new \OpenTHC\CRE\OpenTHC\Batch($this);
	// }

	/**
	 * Get the Plant interface
	 */
	function crop()
	{
		return new \OpenTHC\CRE\OpenTHC\Crop($this);
	}

	/**
	 * Get the Inventory interface
	 */
	function inventory()
	{
		return new \OpenTHC\CRE\OpenTHC\Inventory($this);
	}

	/**
	 * Get the Product interface
	 */
	function product()
	{
		return new \OpenTHC\CRE\OpenTHC\Product($this);
	}

	/**
	 * Get the Section interface
	 */
	function section()
	{
		return new \OpenTHC\CRE\OpenTHC\Section($this);
	}

	/**
	 * Get the Variety interface
	 */
	function variety()
	{
		return new \OpenTHC\CRE\OpenTHC\Variety($this);
	}

}
