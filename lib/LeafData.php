<?php
/**
 * LeafData (aka MJ Freeway)
 *
 * SPDX-License-Identifier: MIT
 *
 * @see https://watest.leafdatazone.com/api_docs/test
 *
 * - Company - They Don't Have This
 * - License - It's called MME, we can search/select them, write is allowed but we don't do it
 * - Contact - Users
 *
 * - Plants
 * - Plants/Collect-Wet
 * - Plants/Collect-Dry
 *
 * - Inventory/Create Batch
 * - Inventory/Convert Batch
 * - Inventory/Combine Batch
 */

namespace OpenTHC\CRE;

class LeafData extends \OpenTHC\CRE\Base
{
	const ENGINE = 'leafdata';

	const FORMAT_DATE_TIME = 'm/d/Y g:i:s a';

	private $_arg;

	// protected $_api_base = 'https://traceability.lcb.wa.gov/api/v1';
	protected $_api_base = 'https://pipe.openthc.com/leafdata/wa';

	protected $_lic_type = array(
		'cultivator' => '(G) Producers',
		'dispensary' => '(R) Retailers',
		'lab' => '(L) Lab',
		'production' => '(M) Processors',
		'cultivator_production' => '(J) Producer/Processor',
		'tribe' => '(T) Tribe',
		'co-op' => '(E) Co-op',
		'transporter' => '(Z) Transporter',
	);

	protected $obj_list = array(
		'license' => 'License',
		'contact' => 'Contact',

		'section' => 'Section',
		'product' => 'Product',
		'variety' => 'Variety',
		'batch' => 'Batch',

		'plant' => 'Plant',

		'inventory' => 'Inventory',
		'inventory-adjustment' => 'InventoryAdjustment',

		'lab-result' => 'LabResult',

		'disposal' => 'Disposal',

		'transfer' => 'Transfer',
		'sale' => 'Sale',
	);

	public static function de_fuck($obj)
	{
		$obj = self::de_fuck_moron_date_format($obj);
		$obj = self::de_fuck_noisy_fields($obj);
		return $obj;
	}

	/**
		@param $rec A data-array record from LeafDooDoo
		@return a Fixed Record
	*/
	public static function de_fuck_moron_date_format($rec)
	{
		$date_field_list = array(
			'adjusted_at',
			'batch_created_at',
			'created_at',
			'deleted_at',
			'disposal_at',
			'est_harvest_at',
			'harvested_at',
			'harvested_end_at',
			'hold_ends_at',
			'hold_starts_at',
			'inventory_created_at',
			'inventory_expires_at',
			'inventory_packaged_at',
			'lab_results_date',
			'packaged_completed_at',
			'plant_created_at',
			'plant_harvested_at',
			'planted_at',
			'updated_at',
		);

		foreach ($date_field_list as $f) {

			$d = trim($rec[$f]);

			if (empty($d)) {
				continue;
			}

			// MySQL
			if ('00/00/0000' == $d) {
				$rec[$f] = null;
				continue;
			}

			if ('0000-00-00 00:00:00' == $d) {
				$rec[$f] = null;
				continue;
			}

			$d = strtotime($d);
			if ($d > 0) {
				// $d = sprintf("@%s", $d);
				// $rec[$f] = self::fix_date_to_utc($d);
				$rec[$f] = date('Y-m-d H:i:s', $d);
			} else {
				// Handle Stupid Shit
				if (preg_match('/^(.+ )(\d+):(\d+)(am|pm)$/i', $rec[$f], $m)) {
					$d = $m[1];
					$hh = intval($m[2]);
					$mm = intval($m[3]);
					if ($hh >= 13) {
						$d.= sprintf('%02d:%02d', $hh, $mm);
					} elseif ($m[4] == 'pm') {
						$d.= sprintf('%02d:%02d', $hh + 12, $mm);
					} else {
						$d.= sprintf('%02d:%02d', $hh, $mm);
					}
					$d = strtotime($d);
					if ($d > 0) {
						// $d = sprintf("@%s", $d);
						// $rec[$f] = self::fix_date_to_utc($d);
						$rec[$f] = date('Y-m-d H:i:s', $d);
					} else {
						throw new \Exception('Really Bad Date');
					}
				}
			}
		}

		return $rec;

	}

	/**
	 * This strips out noisy fields from LeafData Objects
	 * These fields appear some times (like in response to update)
	 * But don't appear other times (like on sync)
	 * So, we just strip them out, since all they do is toggle and clutter my logs
	 * @param $obj with noisy fields
	 * @return $obj, fixed
	 */
	static function de_fuck_noisy_fields($obj)
	{
		$field_list = array(
			'area_id',
			'area_name',
			'batch_id',
			'created_by_mme_id',
			'first_name', // user names leak on batch!?
			'flower_area_id',
			'id',
			'last_name',
			'mme_id',
			'mme_code',
			'mme_name',
			'mother_plant_id',
			'other_area_id',
			'strain_id',
			'strain_name',
			'user_id',
		);

		foreach ($field_list as $f) {
			unset($obj[$f]);
		}

		return $obj;

	}

	/**
	 * Fix date object to a timezoned format, assuming the date is in Pacific time.
	 * @param mixed $d date/time string
	 * @param string $f optional Date time format
	 */
	public static function fix_date_to_utc($d, $f = null)
	{
		//if (empty($f)) $f = self::FORMAT_DATE_TIME;
		//$f = \DateTime::RFC3339;
		$f = 'Y-m-d H:i:s';
		$tz = new \DateTimeZone('America/Los_Angeles');
		$dt = new \DateTime($d, $tz);
		// $dt->setTimezone(new \DateTimeZone('UTC'));
		return $dt->format($f);
	}

	/**
	 * For all the Date/Time Fields -- RE-FUCK the format to the LeafData shit
	 * @param data-array $obj data object to fuck up
	 * @return data-array $obj, but "fixed"
	 */
	function re_fuck_moron_date_format($obj)
	{
		$key_list = array();
		foreach ($key_list as $key) {
			$obj[$key] = _date(self::FORMAT_DATE_TIME, $obj[$key]);
		}
		return $obj;
	}

	/**
		@param $x Array of RBE Options
	*/
	function __construct(array $x)
	{
		parent::__construct($x);

		if (empty($this->_cfg['license'])) {
			throw new \Exception('Invalid API License [LRL-113]');
		}

		if (empty($this->_cfg['license-sk'])) {
			throw new \Exception('Invalid API Secret [LRL-177]');
		}

		$this->setLicense($this->_cfg['license']);

		$this->_req_head = [
			'accept' => 'application/json',
			'content-type' => 'application/json',
			'openthc-license-id' => $this->_License['id'],
			'x-mjf-mme-code' => $this->_cfg['license'],
			'x-mjf-key' => $this->_cfg['license-sk'],
		];

	}

	/**
		@param HTTP VERB
		@param $path the API Path
		@param $post The API Data to POST, as Array or String
	*/
	public function call(string $verb, string $path, $post=null)
	{
		$path = trim($path, '/');
		$url  = sprintf('%s/%s', $this->_api_base, $path);
		$urla = parse_url($url);
		$req = $this->_curl_init($url);

		switch ($verb) {
		case 'GET':
			// Nothing Special
			break;
		case 'POST':

			if (!empty($post)) {
				$this->_arg = $post;
				if (!is_string($this->_arg)) {
					$this->_arg = json_encode($this->_arg);
				}
				curl_setopt($req, CURLOPT_POST, true);
				curl_setopt($req, CURLOPT_POSTFIELDS, $this->_arg);
			}

			break;

		case 'DELETE':

			$this->_arg = null;
			curl_setopt($req, CURLOPT_CUSTOMREQUEST, 'DELETE');

			break;
		}

		$head = [];
		foreach ($this->_req_head as $k => $v) {
			$head[] = sprintf('%s: %s', $k, $v);
		}
		curl_setopt($req, CURLOPT_HTTPHEADER, $head);

		$t0 = microtime(true);
		$this->_raw = curl_exec($req);
		$this->_inf = curl_getinfo($req);
		$err = curl_errno($req);
		$t1 = microtime(true);
		$tx = $t1 - $t0;

		_stat_count(sprintf('rbe.leafdata.code.%s.%03d', $verb, $this->_inf['http_code']), 1);
		_stat_timer(sprintf('rbe.leafdata.time.%s.%03d', $verb, $this->_inf['http_code']), $tx);

		// This means a FATAL curl ERROR
		if ($err) {
			return array(
				'code' => 500,
				'data' => $this->_raw,
				'meta' => [ 'note' => sprintf('LRL#179: LeafData Server Error #%d', curl_error($req)) ],
			);
		}

		$this->_res = json_decode($this->_raw, true);

		$this->_err = json_last_error();
		$this->_err_msg = json_last_error_msg();

		switch ($this->_inf['http_code']) {
		case 200:
		case 201:

			// OK
			return [
				'code' => $this->_inf['http_code'],
				'data' => $this->_res,
			];

			break;

		case 302:

			// The API Call Worked, but gives this odd response that is HTML
			if (('inventory_transfers/update' == $path) && ('POST' == $verb)) {
				return array(
					'code' => $this->_inf['http_code'],
					'data' => $this->_raw,
				);
			}

			break;

		case 401:
		// case 403:
		case 404:

			return array(
				'code' => $this->_inf['http_code'],
				'data' => null,
				'meta' => [
					'note' => sprintf('LRL#%03d: %s', $this->_inf['http_code'], $this->formatError($this->_res)),
					'source' => $this->_res,
				],
			);

			break;

		case 405:
		case 422:
		case 423:

			return array(
				'code' => $this->_inf['http_code'],
				'data' => null,
				'meta' => [
					'note' => sprintf('LRL#422: %s', $this->formatError($this->_res)),
					'source' => $this->_raw,
				],
			);

			break;

		case 500:

			$ret = [
				'code' => $this->_inf['http_code'],
				'data' => $this->_raw,
				'meta' => [ 'note' => 'LeafData Server Error [CLD-315]' ],
			];

			if (preg_match('/SQLSTATE/', $this->_raw)) {
				$ret['meta']['hint'] = 'This indicates a database error in LeafData, you may want to retry your request';
			}

			return $ret;

		case 502:

			return array(
				'code' => $this->_inf['http_code'],
				'data' => null,
				'meta' => [ 'note' => 'LeafData Server Error 502: Bad Gateway [LRL-502]' ],
			);

		case 504:

			return array(
				'code' => $this->_inf['http_code'],
				'data' => null,
				'meta' => [ 'note' => 'LeafData Server Error 504: Gateway Timeout [LRL#504]' ],
			);

		//default:
			//print_r($this);
			//var_dump($this->_inf['http_code']);
			//var_dump($post);
			//var_dump($this->_raw);
			//throw new Exception(sprintf('Invalid Response #%03d from LeafData', $this->_inf['http_code']));
		}

		throw new Exception(sprintf('LRL#240: Invalid Response #%03d from LeafData', $this->_inf['http_code']));

		return $this->_raw;

	}

	/**
	 * Implement Formatting of Error
	 * @return String of Error
	 */
	function formatError($err)
	{
		$ret = array();

		// v2 Way
		if (!empty($err['meta'])) {
			return ($err['meta']['detail']);
		}

		// v1 Way
		if (empty($err['status'])) {

			$ret[] = $this->formatError_from_response($err);

			// if (!empty($err['error_message'])) {
			// 	$ret[] = $err['error_message'];
			// } elseif (!empty($err['validation_messages'])) {
			//
			// } else {
			// 	$ret[] = print_r($err, true);
			// }

			return implode('; ', $ret);

		}

		// v1 Explicit Error
		if ('failure' == $err['status']) {

			if (!empty($err['detail'])) {
				$ret[] = $err['detail'];
			}

			$ret[] = $this->formatError_from_response($err['result']);

		}

		return implode('; ', $ret);

	}

	private function formatError_from_response($err_sub)
	{
		$ret = array();
		if (is_array($err_sub)) {

			// Attach Failure Message
			if (!empty($err_sub['error_message'])) {
				$ret[] = $err_sub['error_message'];
			}

			// Attach Warning Message
			if (!empty($err_sub['validation_messages'])) {
				if (is_array($err_sub['validation_messages'])) {
					// Validate Message Index
					// Validate Message Data
					foreach ($err_sub['validation_messages'] as $vmi => $vmd) {

						if (is_array($vmd)) {
							//var_dump($vmd);
							// Validate Message Key
							// Validate Message Value
							foreach ($vmd as $vmk => $vmv) {
								if (is_array($vmv)) {
									$ret[] = sprintf('%s: %s', $vmi, implode(' & ', $vmv));
								} elseif (is_string($vmv) || is_numeric($vmv)) {
									$ret[] = sprintf('%s: %s', $vmi, $vmv);
								}
							}
						} elseif (is_string($vmd)) {
							$ret[] = $vmd;
						} else {
							throw new Exception('LRL#262 Fuck LeafData');
						}
					}
				} elseif (is_string($err_sub['validation_messages'])) {
					$ret[] = $err_sub['validation_messages'];
				} else {
					throw new Exception('LRL#262 Fuck LeafData');
				}
			}
		} elseif (is_string($err_sub)) {
			$ret[] = $err_sub;
		}

		return implode('; ', $ret);

	}

	function info()
	{
		return array(
			'url' => $this->_inf['url'],
			'http_code' => $this->_inf['http_code'],
			'_raw' => $this->_raw,
			'_err' => $this->_err,
		);
	}

	/**
	 * Ping this connection and return an informational object
	 * @return array('status' => '', 'detail' => '');
	 */
	function ping()
	{
		try {

			$res = $this->call('GET', '/areas');

			switch ($this->_inf['http_code']) {
			case 200:
				if (!empty($res)) {
					if (empty($res['error'])) {
						return array(
							'code' => 200,
							'status' => 'success',
							'detail' => 'Everything is Awesome!',
						);
					}
				}

				break;

			case 302:

				// When MFA is Enabled, the API responds with a 302 status
				// The Location header and the Body both contain the phrase 'enter-mfa'

				if (strpos($this->_inf['redirect_url'], 'enter-mfa')) {
					return array(
						'code' => 302,
						'status' => 'failure',
						'detail' => 'API requires that MFA is disabled in LeafData',
					);
				}

				if (preg_match($this->_raw, 'enter-mfa')) {
					return array(
						'code' => 302,
						'status' => 'failure',
						'detail' => 'API requires that MFA is disabled in LeafData',
					);
				}

				break;
			}

		} catch (Exception $e) {
			// Ignore
		}

		return array(
			'code' => 500,
			'status' => 'failure',
			'detail' => $this->formatError($res),
		);
	}

	/**
	 * Section (Area)
	 */
	function section()
	{
		return new LeafData\Section($this);
	}

	function batch()
	{
		return new LeafData\Batch($this);
	}

	function contact()
	{
		return new LeafData\Contact($this);
		//throw new Exception('Not Used in Washington');
	}

	function customer()
	{
		require_once(__DIR__ . '/LeafData/Customer.php');
		throw new Exception('Not Used in Washington');
	}

	function disposal()
	{
		return new LeafData\Disposal($this);
	}

	// Basically a Product or Product Type or SKU like thing
	function product()
	{
		return new LeafData\Product($this);
	}

	// Basically a Lots/Inventory
	function inventory()
	{
		return new LeafData\Lot($this);
	}

	/**
	 * Not sure why they didn't make this an UPDATE on the /inventory
	 */
	function inventory_delta()
	{
		return new LeafData\Lot_Delta($this);
	}

	function b2c()
	{
		return new LeafData\B2C_Sale($this);
	}

	function b2b()
	{
		return new LeafData\B2B_Sale($this);
	}

	function lab_result()
	{
		return new LeafData\Lab_Result($this);
	}

	/**
		What I want it to Be on the Common Interface
	*/
	function license()
	{
		return new LeafData\License($this);
	}

	function crop()
	{
		return new LeafData\Crop($this);
	}

	function variety()
	{
		return new LeafData\Variety($this);
	}

	function tax()
	{
		return new RBE_LeafData_Taxes($this);
	}

}
