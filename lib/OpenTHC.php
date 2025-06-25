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
		'variety' => 'Variety',
		'product' => 'Product',
		'crop' => 'Crop',
		'crop-collect' => 'Crop Collect / Harvest / Cure',
		'inventory' => 'Inventory',
		'inventory-adjust' => 'Inventory Adjust Logs',
		'lab-report' => 'Lab Reports',
		// 'b2b' => 'B2B Sales',
		'b2b-incoming' => 'B2B Incoming (Purchase Orders)',
		'b2b-outgoing' => 'B2B Outgoing (Sales)',
		'b2c' => 'B2C Sales'
	];

	/**
	 * Array of Arguments
	 */
	function __construct(array $cfg)
	{
		// I hate this mess /djb 2023-05-30
		if (empty($cfg['service-id'])) {
			$cfg['service-id'] = defined('OPENTHC_SERVICE_ID') ? OPENTHC_SERVICE_ID : ($cfg['service'] ?: $_SERVER['SERVER_NAME']);
		}

		parent::__construct($cfg);

		$this->_init_api();

	}

	/**
	 * Create the GuzzleHTTP Client
	 */
	protected function _init_api() : void
	{
		$head = [
			'accept' => 'application/json',
			'user-agent' => 'OpenTHC/CRE/Adapter v420.23.052',
			'openthc-service-id' => $this->_cfg['service-id'],
			'openthc-contact-id' => $this->_cfg['contact'],
			'openthc-company-id' => $this->_cfg['company'],
			'openthc-license-id' => $this->_License['id'],
		];

		// Session ID?
		if ( ! empty($this->_cfg['session-id'])) {
			$head['authorization'] = sprintf('Bearer v2024/%s', $this->_cfg['session-id']);
		}

		$cfg = array(
			'base_uri' => $this->_api_base,
			'allow_redirects' => false,
			'cookies' => true,
			'headers' => $head,
			'http_errors' => false,
		);

		$this->_c = new \GuzzleHttp\Client($cfg);

	}

	function auth($x)
	{
		if (empty($this->_cfg['server-pk'])) {
			throw new \Exception('Invalid Configuration [CLO-081]');
		}
		if (empty($this->_cfg['client-pk'])) {
			throw new \Exception('Invalid Configuration [CLO-084]');
		}
		if (empty($this->_cfg['client-sk'])) {
			throw new \Exception('Invalid Configuration [CLO-087]');
		}

		// v2024 Token
		$plain_tok = [];
		$plain_tok['pk'] = $this->_cfg['client-pk'];
		$plain_tok['ts'] = time();
		$plain_tok['service'] = $this->_cfg['service'];
		$plain_tok['contact'] = $this->_cfg['contact'];
		$plain_tok['company'] = $this->_cfg['company'];
		$plain_tok['license'] = $this->_License['id'];
		$plain_tok = json_encode($plain_tok);

		$crypt_box = \OpenTHC\Sodium::encrypt($plain_tok, $this->_cfg['client-sk'], $this->_cfg['server-pk']);
		$crypt_box = \OpenTHC\Sodium::b64encode($crypt_box);

		$opt = [
			'headers' => [
				'authorization' => sprintf('Bearer v2024/%s/%s', $this->_cfg['client-pk'], $crypt_box),
			]
		];

		$res = $this->_c->request('POST', '/auth/open', $opt);

		$this->_res_code = $res->getStatusCode();
		$this->_res_body = $res->getBody()->getContents();
		$this->_res_type = $res->getHeaderLine('content-type');
		$this->_res_type = strtok($this->_res_type, ';');

		// $res = $this->post('/auth/open', [], $opt);
		$res = json_decode($this->_res_body, true);

		$ret = [];
		$ret['code'] = $this->_res_code;
		$ret['data'] = $res['data'];
		$ret['meta'] = $res['meta'];

		switch ($ret['code']) {
		case 200:
			$this->_cfg['session-id'] = $ret['data']['sid'];
			break;
		}

		return $ret;

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
	function get(string $url)
	{
		$res = $this->request('GET', $url);

		$this->_res_code = $res->getStatusCode();
		$this->_res_body = $res->getBody()->getContents();
		$this->_res_type = $res->getHeaderLine('content-type');
		$this->_res_type = strtok($this->_res_type, ';');

		$ret = null;
		switch ($this->_res_code) {
		case 200:
		case 201:
		case 401:
		case 403:
		case 404:
		case 405:
		case 410:
		case 423:
			switch ($this->_res_type) {
			case 'application/json':
				$ret = json_decode($this->_res_body, true);
				$ret['code'] = $this->_res_code;
				break;
			case 'application/pdf':
				$ret['code'] = $this->_res_code;
				$ret['data'] = $this->_res_body; // It's BINARY
				$ret['meta'] = [
					// Not really what it's called.
					'name' => $res->getHeaderLine('content-filename'),
				];
				break;
			case 'text/html':
				$ret['code'] = $this->_res_code;
				$ret['data'] = $this->_res_body;
				break;
			default:
				throw new \Exception('Invalid MIME Type [LRO-131]');
			}
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
	function head(string $url)
	{
		$res = $this->request('HEAD', $url);
		return $res;
	}

	/**
	 * HTTP POST Utility
	 *
	 * @param string $type is un-used
	 */
	function post(string $url, $arg, $type='multipart/form-data')
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
	function patch(string $url, $arg)
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
	function delete(string $url, $arg=null)
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
	function request(string $v, string $u, $o=[])
	{
		// Encrypt Data In Header
		$h = [
			'headers' => [
				'openthc-license-id' => $this->_License['id'],
			]
		];

		if ( ! empty($this->_cfg['session-id'])) {
			$h['headers']['authorization'] = sprintf('Bearer v2024/%s', $this->_cfg['session-id']);
		}

		$o = array_merge($o, $h);

		return $this->_c->request($v, $u, $o);

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
