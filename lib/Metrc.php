<?php
/**
 * Franwell / METRC Interface
 */

namespace OpenTHC\CRE\Adapter;

class Metrc extends Base
{
	const ENGINE = 'metrc';

	protected $_api_base = null;
	protected $_api_host = null;
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

	protected static $obj_list = array(
		'uom' => 'Units of Measure',
		'license' => 'License',
		'contact' => 'Contact/Patient',
		//'product_type' => 'Item Categories/Product Types', // Manual Sync
		'room' => 'Room',
		'strain' => 'Strain',
		'product' => 'Product',
		'plantbatch' => 'Plant Batches',
		'plant' => 'Plant',
		'harvest' => 'Harvest',
		'lot' => 'Lot',
		'labresult' => 'Lab Results',
		'retail' => 'Retail',
		'transfer' => 'Transfer',
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
			program-key
			license-key
	*/
	function __construct($x)
	{
		parent::__construct($x);

		if (empty($x['program-key'])) {
			throw new \Exception('Invalid Program Key [LRM#048]');
		}

		if (empty($x['license-key'])) {
			throw new \Exception('Invalid License Key [LRM#052]');
		}

		$this->_api_key_vendor = $x['program-key'];
		$this->_api_key_client = $x['license-key'];

		if (!empty($x['license'])) {
			$this->setLicense($x['license']);
		}

	}

	function getClientKey()
	{
		return $this->_api_key_client;
	}

	/**
		Turns on Test Mode, Session Persistent
	*/
	function setTestMode()
	{
		throw new Exception('LRM#063: Not Implemented');
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

	public static function listSyncObjects()
	{
		return self::$obj_list;
	}

	function ping()
	{
		try {
			$res = $this->uomList();
		} catch (\Exception $e) {
			return array(
				'code' => 500,
				'status' => 'failure',
				'detail' => $e->getMessage(),
			);
		}

		try {
			$res = $this->packageTypeList();
		} catch (\Exception $e) {
			return array(
				'code' => 500,
				'status' => 'failure',
				'detail' => $e->getMessage(),
			);
		}

		return array(
			'code' => 200,
			'status' => 'success',
			'detail' => 'Everything is Awesome!',
		);
	}

	/**
		Error Handler
	*/
	function formatError($res)
	{
		//var_dump($res);

		if (!is_array($res)) {
			$chk = json_decode($res, true);
			if (is_array($chk)) {
				$res = $chk;
			}
		}

		if (is_array($res)) {
			if (!empty($res['Message'])) {
				return $res['Message'];
			}
		}

		header('Content-Type: text/plain');
		var_dump($res);
		var_dump(debug_print_backtrace());
		throw new \Exception('METRC Really Broken [LRM#159]');
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
		$res = $this->get('/unitsofmeasure/v1/active');
		return $res;
	}

	/**
	*/
	function adjustList()
	{
		$url = $this->_make_url('/packages/v1/adjust/reasons');
		$req = $this->_curl_init($url);
		$res = $this->_curl_exec($req);
		return $res;
	}

	/**
		Adjust the Unit of Measure on one or more items
		@see https://api-or.metrc.com/Documentation#Packages.post_packages_v1_adjust
	*/
	function packageAdjust($arg)
	{
		$url = $this->_make_url('/packages/v1/adjust');
		$req = $this->_curl_init($url);
		$res = $this->_curl_exec($req, $arg);
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

	/**

	*/
	function packageFinish($arg)
	{
		$url = $this->_make_url('/packages/v1/finish');
		$req = $this->_curl_init($url);
		$res = $this->_curl_exec($req, $arg);
		return $res;
	}

	function packageFinishUndo($arg)
	{
		$url = $this->_make_url('/packages/v1/unfinish');
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

//	function packageGet()
//	{
//	}
//
	function packageTypeList()
	{
		$res = $this->get('/packages/v1/types');
		return $res;
	}


	function plantbatchChangePhase($arg)
	{
		throw new \Exception('@deprecated');
	}

	/**
		Creates a Set of Plants? That you then Create Plants
	*/
	function plantbatchCreatePlantings($arg)
	{
		throw new \Exception('@deprecated');
	}

	function plantbatchDestroy($arg)
	{
		throw new \Exception('@deprecated');
	}

	/**
		Taking Clone or Seed into Package for Sale
	*/
	function plantbatchPackageCreate($arg)
	{
		$url = $this->_make_url('/plantbatches/v1/createpackages');
		$x = $this->_curl_init($url);
		$res = $this->_curl_exec($x, $arg);
		return $res;
	}


	/**
		Delete an Item (an SKU like thing)
	*/
	function itemDelete($id)
	{
		$url = sprintf('/items/v1/%d', $id);
		$url = $this->_make_url($url);
		$req = $this->_curl_init($url);
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, 'DELETE');
		$res = $this->_curl_exec($req);
		return $res;
	}

	/**
		@param $x {id} or {date}
	*/
	function sales($x=null)
	{
		$r = new RBE_Metrc_Sales($this);
		return $r;
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
			$arg['lastModifiedStart'] = $d0; //=> '', // 2018-01-17T06:30:00Z
		}
		if (!empty($d1)) {
			$arg['lastModifiedEnd'] = $d1;
		}

		return $url . '?' . http_build_query($arg);

	}

	function b2c()
	{
		$o = new RBE_Metrc_B2C($this);
		return $o;
	}

	function batch()
	{
		$o = new RBE_Metrc_Batch($this);
		return $o;
	}

	function contact()
	{
		$o = new RBE_Metrc_Contact($this);
		return $o;
	}

	function labresult()
	{
		$o = new RBE_Metrc_Lab_Result($this);
		return $o;
	}

	function license()
	{
		$o = new RBE_Metrc_License($this);
		return $o;
	}

	function lot()
	{
		$o = new RBE_Metrc_Lot($this);
		return $o;
	}

	function plant()
	{
		$o = new RBE_Metrc_Plant($this);
		return $o;
	}

	function plant_collect()
	{
		$o = new RBE_Metrc_Plant_Collect($this);
		return $o;
	}

	function product()
	{
		$o = new RBE_Metrc_Product($this);
		return $o;
	}

	function strain()
	{
		$o = new RBE_Metrc_Strain($this);
		return $o;
	}

	/**
		Interface for One Transfer
	*/
	function transfer()
	{
		$r = new RBE_Metrc_Transfer($this);
		return $r;
	}

	/**
		Interface for Zones
	*/
	function zone()
	{
		$r = new RBE_Metrc_Zone($this);
		return $r;
	}


	/**

	*/
	function _curl_exec($ch, $arg=null)
	{
		$verb = 'GET';

		$t0 = microtime(true);

		if (!empty($arg)) {

			$verb = 'POST';

			$arg = json_encode($arg, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $arg);

		}

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
		App::metric()->timing(sprintf('rbe.metrc.time.%s.%03d', $verb, $code), $tx);

		// echo "\n{$code} {$this->_inf['url']} {$tx}\n";
		// echo "\n<<<<\n{$this->_raw}\n####\n";
		// var_dump($this->_inf);

		$result = array();

		switch ($code) {
		case 200: // OK
			if ('DELETE' == $verb) {
				if (0 == $this->_inf['download_content_length']) {
					return array(
						'status' => 'success',
						'result' => array(),
					);
				}
			}
			break;
		case 400:
		case 405:
			throw new \Exception($this->formatError($this->_res));
			break;
		case 401:
			return [
				'code' => 401,
				'data' => null,
				'meta' => [ 'Access Denied' ]
			];
			break;
		default:
			var_dump($this);
			$msg = sprintf('Server Error / Invalid Request: %d [RBE#735]', $code);
			throw new \Exception($msg);
		}

		if (empty($this->_res)) {
			$this->_res = array();
		}

		return array(
			'code' => $code,
			'status' => (200 == $code ? 'success' : 'failure'),
			'result' => $this->_res,
		);

	}

}
