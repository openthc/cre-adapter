<?php
/**
 * METRC Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE;

class Metrc2023 extends \OpenTHC\CRE\Base
{
	private $_date_alpha;

	private $_date_omega;

	/**
	 *
	 */
	function __construct(array $cfg)
	{
		parent::__construct($cfg);

		if (empty($this->_cfg['service-sk'])) {
			throw new \Exception('Invalid Service Key [LRM-048]');
		}

		if (empty($this->_cfg['license-sk'])) {
			throw new \Exception('Invalid License Key [LRM-052]');
		}

		$this->_api_key_vendor = $this->_cfg['service-sk'];
		$this->_api_key_client = $this->_cfg['license-sk'];

		if ( ! empty($this->_cfg['license'])) {
			$this->setLicense($this->_cfg['license']);
		}

	}

	function ping()
	{
		try {

			$req = $this->_curl_init('/facilities/v2/');
			$res = $this->_curl_exec($req);

			switch ($res['code']) {
				case 200:
					// OK
					break;
				default:
					return $res;
			}

		} catch (\Exception $e) {
			return array(
				'code' => 500,
				'data' => null,
				'meta' => [ 'note' => $e->getMessage() ],
			);
		}

		try {

			$req = $this->_curl_init('/unitsofmeasure/v2/active');
			$res = $this->_curl_exec($req);

			switch ($res['code']) {
				case 200:
					// OK
					break;
				default:
					return $res;
			}

		} catch (\Exception $e) {
			return array(
				'code' => 500,
				'data' => null,
				'meta' => [ 'note' => $e->getMessage() ],
			);
		}

		return array(
			'code' => 200,
			'data' => [],
			'meta' => [ 'note' => 'Everything is Awesome!' ],
		);
	}

	function contact()
	{
		return new Metrc2023\Contact($this);
	}

	function crop()
	{
		return new Metrc2023\Crop($this);
	}

	function inventory()
	{
		return new Metrc2023\Inventory($this);
	}

	function license()
	{
		return new Metrc2023\License($this);
	}

	function product()
	{
		return new Metrc2023\Product($this);
	}

	function section()
	{
		return new Metrc2023\Section($this);
	}

	function variety()
	{
		return new Metrc2023\Variety($this);
	}

	function is_time_aware($obj)
	{
		switch ($obj) {
		case 'b2b':
		case 'b2c':
		case 'harvest':
		case 'lot':
		case 'inventory':
		case 'plantbatch':
		case 'plant':
		case 'retail':
		case 'transfer':
			return true;
		case 'license':
		case 'contact': // is patients & employees -- time aware or not?
		case 'lab_result':
		case 'lab-result':
		case 'patients':
		case 'product':
		case 'uom':
		case 'section':   // OpenTHC Name
		case 'locations': // Metrc Name
		case 'variety':
			return false;
		default:
			throw new \Exception(sprintf('Unknown Object Type: "%s"', $obj));
		}

	}

	function setTimeAlpha($dt)
	{

	}

	function setTimeOmega($dt)
	{

	}

	/**
	 * Do the CURL thing
	 */
	function _curl_exec($ch, $arg=null)
	{
		$verb = 'GET';

		$t0 = microtime(true);

		if (!empty($arg)) {

			$verb = 'POST';

			$arg = json_encode($arg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $arg);

		}

		$this->_res = null;
		$this->_raw = curl_exec($ch);
		$this->_inf = curl_getinfo($ch);
		$this->_err = curl_errno($ch);
		curl_close($ch);

		$code = $this->_inf['http_code'];

		$mime = strtolower(strtok($this->_inf['content_type'], ';'));
		if ('application/json' == $mime) {
			$this->_res = json_decode($this->_raw, true);
			$this->_res_err = json_last_error_msg();
		}

		$t1 = microtime(true);
		$tx = $t1 - $t0;

		// _stat_count(sprintf('rbe.metrc.code.%s.%03d', $verb, $code), 1);
		// _stat_timer(sprintf('rbe.metrc.time.%s.%03d', $verb, $code), $tx);

		$result = array();

		switch ($code) {
		case 200: // OK
			if ('DELETE' == $verb) {
				if (0 == $this->_inf['download_content_length']) {
					return array(
						'code' => 200,
						'data' => null,
						'meta' => [],
					);
				}
			}
			break;
		case 400:
		case 405:
		case 500:
			return [
				'code' => $code,
				'data' => $this->_raw,
				'meta' => [ 'note' => 'Unexpected Server Error [CLM-226]' ]
			];
		case 401:
			return [
				'code' => $code,
				'data' => null,
				'meta' => [
					'note' => 'Not Authorized [CLM-433]',
					'message' => $this->_res['Message'],
				]
			];
		case 404:
			return [
				'code' => $code,
				'data' => null,
				'meta' => [ 'note' => 'Not Found [CLM-439]' ]
			];
		default:
			// var_dump($this);
			$msg = sprintf('Server Error / Invalid Request: %d [RBE-735]', $code);
			throw new \Exception($msg);
		}

		if (empty($this->_res)) {
			$this->_res = [];
		}

		return [
			'code' => $code,
			'data' => $this->_res,
			'meta' => [],
		];

	}

	/**
	 * Executes the Single or Multiple Requests
	 */
	function _curl_init(string $uri, $head=null)
	{
		$uri = ltrim($uri, '/.');
		$uri = sprintf('%s/%s', $this->_api_base, $uri);

		$req = parent::_curl_init($uri);

		$auth = sprintf('%s:%s', $this->_api_key_vendor, $this->_api_key_client);
		curl_setopt($req, CURLOPT_USERPWD, $auth);

		$head = array(
			'accept: application/json',
			'content-type: application/json',
			// sprintf('openthc-service-id: %s', $this->_cfg['service']),
			// sprintf('openthc-contact-id: %s', $this->_cfg['contact']),
			// sprintf('openthc-company-id: %s', $this->_cfg['company']),
			// sprintf('openthc-license-id: %s', $this->_License['id']),
		);
		curl_setopt($req, CURLOPT_HTTPHEADER, $head);

		return $req;
	}

	/**
	 * Prepare a URL for Use with METRC (adds licenseNumber to QS)
	 */
	function _make_url(string $url, $arg=[])
	{
		$arg = array_merge(array(
			'licenseNumber' => $this->_License['code'],
		), $arg);

		if (empty($d0)) {
			if (!empty($this->_time_alpha)) {
				$d0 = $this->_time_alpha;
			}
		}

		if (empty($d1)) {
			if (!empty($this->_time_omega)) {
				$d1 = $this->_time_omega;
			}
		}

		if (!empty($d0)) {
			$arg['lastModifiedStart'] = $d0;
		}

		if (!empty($d1)) {
			$arg['lastModifiedEnd'] = $d1;
		}

		$url = $url . '?' . http_build_query($arg);
		$url = trim($url, '?');

		return $url;

	}

}
