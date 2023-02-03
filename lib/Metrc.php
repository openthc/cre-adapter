<?php
/**
 * Franwell / METRC Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE;

class Metrc extends \OpenTHC\CRE\Base
{
	const ENGINE = 'metrc';

	protected $_api_name = 'metrc';
	protected $_api_key_vendor = null;
	protected $_api_key_client = null;

	public static $license_type_list = array(
		'F' => 'Producer',
		'R' => 'Retailer',
		'P' => 'Processor',
		'QA' => 'Labs',
		'RC' => 'Research',
	);

	protected $obj_list = array(
		'uom' => 'Units of Measure',
		'license' => 'License',
		'contact' => 'Contact / Patient',
		'section-type' => 'Section Type',
		'section' => 'Section',
		'variety' => 'Variety',
		'product-type' => 'Product Type / Item Categories',
		'product' => 'Product',
		'cropbatch' => 'Crop Batches',
		'crop' => 'Crop',
		'harvest' => 'Harvest', // crop collect
		'lot' => 'Lot',
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
		@param $x RBE Options
			license
			license-key
	*/
	function __construct($cfg)
	{
		if (empty($cfg['service-key'])) {
			throw new \Exception('Invalid Service Key [LRM-048]');
		}

		if (empty($cfg['license-key'])) {
			throw new \Exception('Invalid License Key [LRM-052]');
		}

		if (!empty($cfg['server'])) {
			$this->_api_base = rtrim($cfg['server'], '/.');
		}

		$this->_api_key_vendor = $cfg['service-key'];
		$this->_api_key_client = $cfg['license-key'];

		if (!empty($cfg['license'])) {
			$this->setLicense($cfg['license']);
		}

	}

	/**
	 *
	 */
	function getClientKey()
	{
		return $this->_api_key_client;
	}

	/**
	 * [setTimeAlpha description]
	 */
	function setTimeAlpha($x)
	{
		$this->_time_alpha = $x;
	}

	/**
	 * [setTimeOmega description]
	 */
	function setTimeOmega($x)
	{
		$this->_time_omega = $x;
	}

	function ping()
	{
		try {
			$res = $this->uomList();
		} catch (\Exception $e) {
			return array(
				'code' => 500,
				'data' => null,
				'meta' => [ 'detail' => $e->getMessage() ],
			);
		}

		try {
			$res = $this->packageTypeList();
		} catch (\Exception $e) {
			return array(
				'code' => 500,
				'data' => null,
				'meta' => [ 'detail' => $e->getMessage() ],
			);
		}

		return array(
			'code' => 200,
			'data' => [],
			'meta' => [ 'detail' => 'Everything is Awesome!' ],
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
		What ?
	*/
	function itemCategoryList()
	{
		$req = $this->_curl_init('/items/v1/categories');
		$res = $this->_curl_exec($req);
		return $res;
	}

	/**
	*/
	function uomList()
	{
		$req = $this->_curl_init('/unitsofmeasure/v1/active');
		$res = $this->_curl_exec($req);
		return $res;
	}

	/**
	*/
	function locationsTypesList()
	{
		$url = $this->_make_url('/locations/v1/types');
		$req = $this->_curl_init($url);
		$res = $this->_curl_exec($req);
		return $res;
	}

	function packageChangeItem($arg)
	{
		$url = $this->_make_url('/packages/v1/change/item');
		$req = $this->_curl_init($url);
		$res = $this->_curl_exec($req, $arg);
		return $res;
	}

	function packageCreate($arg)
	{
		$url = $this->_make_url('/packages/v1/create');
		$req = $this->_curl_init($url);
		$res = $this->_curl_exec($req, $arg);
		return $res;
	}

	function packageCreateTesting($arg)
	{
		$url = $this->_make_url('/packages/v1/create/testing');
		$x = $this->_curl_init($url);
		$res = $this->_curl_exec($x, $arg);
		return $res;
	}

	function packageList($mode=null)
	{
		if (empty($mode)) {
			$mode = 'active';
		}

		$url = sprintf('/packages/v1/%s', $mode);
		$url = $this->_make_url($url);
		$x = $this->_curl_init($url);
		$res = $this->_curl_exec($x);
		return $res;
	}

	function packageTypeList()
	{
		$x = $this->_curl_init('/packages/v1/types');
		$res = $this->_curl_exec($x);
		return $res;
	}

	/**
		Delete an Item (an SKU like thing)
	*/
	function itemDelete($id)
	{
		$url = sprintf('/items/v1/%s', $id);
		$url = $this->_make_url($url);
		$req = $this->_curl_init($url);
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, 'DELETE');
		$res = $this->_curl_exec($req);
		return $res;
	}

	/**
	 * Prepare a URL for Use with METRC (adds licenseNumber to QS)
	 */
	function _make_url($url, $arg=[])
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

	/**
		Interface for One Transfer
	*/
	function b2b()
	{
		return new Metrc\B2B($this);
	}

	function b2c()
	{
		return new Metrc\B2C($this);
	}

	function batch()
	{
		return new Metrc\Batch($this);
	}

	function contact()
	{
		return new Metrc\Contact($this);
	}

	function crop()
	{
		return new Metrc\Crop($this);
	}

	function crop_collect()
	{
		return new Metrc\Crop_Collect($this);
	}

	function labresult()
	{
		return new Metrc\Lab_Result($this);
	}

	function license()
	{
		return new Metrc\License($this);
	}

	function lot()
	{
		return new Metrc\Lot($this);
	}

	function patient()
	{
		return new Metrc\Patient($this);
	}


	function product()
	{
		return new Metrc\Product($this);
	}

	function variety()
	{
		return new Metrc\Variety($this);
	}

	/**
	 * Interface for Sections
	 * Metrc used to call these Rooms, now they are called Locations
	 */
	function section()
	{
		return new Metrc\Section($this);
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
			// throw new \Exception($this->formatError($this->_res));
			// break;
		case 401:
			return [
				'code' => $code,
				'data' => $this->_raw,
				'meta' => [ 'note' => 'Not Authorized' ]
			];
		case 405:
			return [
				'code' => $code,
				'data' => null,
				'meta' => [ 'detail' => $this->formatError($this->_res) ]
			];
			break;
		default:
			// var_dump($this);
			$msg = sprintf('Server Error / Invalid Request: %d [RBE-735]', $code);
			throw new \Exception($msg);
		}

		if (empty($this->_res)) {
			$this->_res = array();
		}

		return array(
			'code' => $code,
			'data' => $this->_res,
			'meta' => [],
		);

	}

	/**
	 * Executes the Single or Multiple Requests
	 */
	function _curl_init($uri, $head=null)
	{
		$uri = ltrim($uri, '/.');
		$uri = sprintf('%s/%s', $this->_api_base, $uri);

		$req = parent::_curl_init($uri);

		$auth = sprintf('%s:%s', $this->_api_key_vendor, $this->_api_key_client);
		curl_setopt($req, CURLOPT_USERPWD, $auth);

		$head = array(
			'accept: application/json',
			'content-type: application/json',
			sprintf('openthc-company: %s', $_SESSION['Company']['id']),
		);
		curl_setopt($req, CURLOPT_HTTPHEADER, $head);

		return $req;
	}
}
