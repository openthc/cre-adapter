<?php
/**
 * METRC Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE;

class Metrc2023 extends \OpenTHC\CRE\Base
{
	const ENGINE = 'metrc';

	private $_api_key_vendor = null;

	private $_api_key_client = null;

	private $_datetime_alpha;

	private $_datetime_omega;

	public static $license_type_list = array(
		'F' => 'Producer',
		'R' => 'Retailer',
		'P' => 'Processor',
		'QA' => 'Labs',
		'RC' => 'Research',
	);

	protected $obj_list = array(
		'uom' => 'Units of Measure',
		'tag' => 'Tags',
		'license' => 'License',
		'contact' => 'Contact / Patient',
		'section-type' => 'Section Type',
		'section' => 'Section',
		'variety' => 'Variety',
		'product-type' => 'Product Type / Item Categories',
		'product' => 'Product',
		'crop' => 'Crop',
		'cropbatch' => 'Crop Batches',
		'cropcollect' => 'Crop Collect',
		'inventory' => 'Inventory',
		'lab-result' => 'Lab Result',
		'b2b' => 'B2B / Wholesale',
		'b2c' => 'B2C / Retail',
	);

	/**
	 * Cleanup Shitty Data
	 * @param [type] $rec [description]
	 * @return $rec but fixed
	 */
	public static function de_fuck($rec)
	{
		$key_list = array(
			'EstimatedDepartureDateTime',
			'EstimatedArrivalDateTime',
		);

		foreach ($key_list as $k) {
			if ('0001-01-01T00:00:00.000' == $rec[$k]) {
				$rec[$k] = null;
			}
		}

		return $rec;
	}

	/**
	 * @param $cfg CRE Configuration Options
	 */
	function __construct(array $cfg, $dbc=null)
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

	/**
	 * Error Formatter
	 */
	function formatError($res)
	{
		if ( ! is_array($res)) {
			$chk = json_decode($res, true);
			if (is_array($chk)) {
				$res = $chk;
			}
		}

		if (is_array($res)) {
			if (( ! empty($res['code'])) && ( ! empty($res['meta']['note']))) {
				return $res['meta']['note'];
			}
			if (!empty($res['Message'])) {
				return $res['Message'];
			}
		}

		if ( ! empty($res[0]['message'])) {
			return $res[0]['message'];
		}

		var_dump($res);
		// var_dump(debug_print_backtrace());
		throw new \Exception('METRC Really Broken [LRM-159]');
		exit(0);
	}

	/**
	 * Determines if the Object is a METRC time-aware resource
	 * @param [type] $k [description]
	 * @return boolean [description]
	 */
	function _is_time_aware($obj)
	{
		switch ($obj) {
		case 'b2b':
		case 'b2c':
		case 'crop':
		case 'crop':
		case 'cropbatch':
		case 'cropcollect':
		case 'harvest': // v1
		case 'inventory':
		case 'lot': // v1
		case 'plant': // v1
		case 'plantbatch': // v1
		case 'variety':
			return true;
		case 'contact': // is patients & employees -- time aware or not?
		case 'lab-result':
		case 'patients':
		case 'product':
		case 'product-type':
		case 'section':
		case 'section-type':
		case 'tag':
		case 'variety':
		case 'uom':
			return false;
		default:
			throw new \Exception(sprintf('Unknown Object Type: "%s"', $obj));
		}
	}

	function contact()
	{
		return new Metrc2023\Contact($this);
	}
	// function patient()
	// {
	// 	return new Metrc\Patient($this);
	// }

	function license()
	{
		return new Metrc2023\License($this);
	}

	function crop()
	{
		return new Metrc2023\Crop($this);
	}

	function crop_batch()
	{
		return new Metrc2023\Crop_Batch($this);
	}

	function crop_collect()
	{
		return new Metrc2023\Crop_Collect($this);
	}

	function inventory()
	{
		return new Metrc2023\Inventory($this);
	}

	function labresult()
	{
		return new Metrc2023\Lab_Result($this);
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

	function b2b()
	{
		return new Metrc2023\B2B($this);
	}

	function b2c()
	{
		return new Metrc2023\B2C($this);
	}

	function tag()
	{
		return new Metrc2023\Tag($this);
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
		$this->_datetime_alpha = $dt;
	}

	function setTimeOmega($dt)
	{
		$this->_datetime_omega = $dt;
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

			// $ret = [];
			// $ret['code'] = $res['code'];
			// $ret['data'] = $res['data']['Data'];
			// unset($res['data']['Data']);
			// $ret['meta'] = $res['data'];

			break;
		case 400:
			return [
				'code' => $code,
				'data' => $this->_raw,
				'meta' => [
					'note' => 'Unexpected Server Error [CLM-388]',
					'message' => $this->_res['Message']
				]
			];
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
		// $auth = base64_encode($auth);
		curl_setopt($req, CURLOPT_USERPWD, $auth);

		$head = array(
			'accept: application/json',
			// sprintf('authorization: Basic %s', $auth),
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
			if (!empty($this->_datetime_alpha)) {
				$d0 = $this->_datetime_alpha;
			}
		}

		if (empty($d1)) {
			if (!empty($this->_datetime_omega)) {
				$d1 = $this->_datetime_omega;
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
