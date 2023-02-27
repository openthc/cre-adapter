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

	protected $_c; // Client Connection

	protected $_res_body;
	protected $_res_code;

	protected $obj_list = [
		'license' => 'License',
		'section' => 'Section',
		'product' => 'Product',
		'variety' => 'Variety',
		'crop' => 'Crop',
		'crop-collect' => 'Crop Collect / Harvest / Cure',
		'lot' => 'Lot',
		'lab-report' => 'Lab Reports',
		'b2b' => 'B2B Sales',
		'b2b_incoming' => 'B2B Incoming (Purchase Orders)',
		'b2b_outgoing' => 'B2B Outgoing (Sales)',
		'b2c' => 'B2C Sales'
	];


	/**
	 * Array of Arguments
	 */
	function __construct($cfg)
	{
		parent::__construct($cfg);
		$this->_init_api();
	}

	/**
	 *
	 */
	protected function _init_api()
	{
		$cfg = array(
			'base_uri' => $this->_api_base,
			'allow_redirects' => false,
			'cookies' => false,
			'headers' => array(
				'accept' => 'application/json',
				'user-agent' => 'OpenTHC/CRE/Adapter v420.23.052',
			),
			'http_errors' => false,
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

	/**
	 * Format Error
	 */
	function formatError($e)
	{
		if (is_array($e)) {

			if ( ! empty($e['meta']['note'])) { // v2
				return $e['meta']['note'];
			}

			if ( ! empty($e['meta']['detail'])) { // v1
				return $e['meta']['detail'];
			}

			if ( ! empty($e['detail'])) { // v0
				return $e['detail'];
			}
		}

		return json_encode($e, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

	}

	/**
	 * HTTP GET Utility
	 */
	function get($url)
	{
		$res = $this->request('GET', $url);

		$this->_res_body = $res->getBody()->getContents();
		$this->_res_code = $res->getStatusCode();

		$ret = null;
		switch ($this->_res_code) {
		case 200:
		case 201:
		case 403:
		case 404:
		case 405:
		case 410:
		case 423:
			$ret = json_decode($this->_res_body, true);
			$ret['code'] = $this->_res_code;
			break;
		default:
			// _exit_text($this->_res_code . ': ' . $res->getBody());
			throw new \Exception(sprintf('Invalid Response "%d" from OpenTHC [LRO-152]', $this->_res_code));
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

		$this->_res_code = $res->getStatusCode();
		$this->_res_body = $res->getBody()->getContents();

		$ret = null;
		switch ($this->_res_code) {
		case 200:
		case 201:
		case 202:
		case 400:
		case 403:
		case 404:
		case 405:
		case 409:
		case 410:
			$ret = json_decode($this->_res_body, true);
			$ret['code'] = $this->_res_code;
			break;
		default:
			echo ">>>{$this->_res_body}\n###\n";
			throw new \Exception(sprintf('Invalid Response Code: %03d from OpenTHC [LRO-208]', $this->_res_code));
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
		$this->_res_code = $res->getStatusCode();
		$this->_res_body = $res->getBody()->getContents();

		$ret = null;
		switch ($this->_res_code) {
		case 200:
			$ret = json_decode($this->_res_body, true);
			$ret['code'] = $this->_res_code;
			break;
		default:
			var_dump($this->_res_body);
			throw new \Exception(sprintf('Invalid Response Code: %03d from OpenTHC [LRO-218]', $this->_res_code));
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

		$this->_res_code = $res->getStatusCode();
		$this->_res_body = $res->getBody()->getContents();

		$ret = null;
		switch ($this->_res_code) {
		case 200:
		case 202:
		case 204:
		case 403:
		case 404:
		case 405:
		case 410:
		case 423:
			$ret = json_decode($this->_res_body, true);
			$ret['code'] = $this->_res_code;
			break;
		default:
			throw new \Exception(sprintf(_('Invalid Response Code "%03d" from OpenTHC [LRO-290]'), $this->_res_code));
		}

		return $ret;
	}

	/**
	 * Common Request Handler
	 */
	function request($v, $u, $o=[])
	{
		$jwt = new \OpenTHC\JWT([
			'iat' => time() - 30,
			'iss' => $_SERVER['SERVER_NAME'],
			'exp' => (time() + 120),
			'sub' => $this->_cfg['contact'],
			'service' => $this->_cfg['service'],
			'cre' => $this->_cfg['id'], // CRE ID
			'company' => $this->_cfg['company'], // @deprecated
			'license' => $this->_License['id'],
		]);

		$o = array_replace_recursive($o, [
			'headers' => [
				'authorization' => sprintf('Bearer jwt:%s', $jwt->__toString()),
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
