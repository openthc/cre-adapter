<?php
/**
	@see http://www.biotrackthc.com/api/json
	@see http://www.biotrackthc.com/api/xml
*/

namespace OpenTHC\CRE;

use Edoceo\Radix\DB\SQL;

class BioTrack extends \OpenTHC\CRE\Base
{
	const ENGINE = 'biotrack';

	public $_sid = null;

	protected $_name = 'BioTrack';
	protected $_api_base = 'https://<server>/serverjson.asp';

	protected $_training = false;

	protected $_inf;
	protected $_raw;
	protected $_ret;

	// Inventory Adjustment Reasons
	public static $adj_list = array(
		1 => 'Inventory Audit',
		2 => 'Theft',
		3 => 'Seizure by Law Enforcement',
		4 => 'Correction',
		5 => 'Moisture Loss',
		6 => 'Depletion',
	);

	// Location Types defined by BioTrack
	// @todo Should be Private
	public static $loc_type = array(
		1 => 'T1',
		2 => 'T2',
		3 => 'T3',
		4 => 'T1P',
		5 => 'T2P',
		6 => 'T3P',
		7 => 'P',
		8 => 'R',
		9 => 'Tribe',
		10 => 'R+MMJ',
		11 => 'CO-OP',
	);

	// Listed in order the Sync should run
	// @todo Should be Private
	public static $obj_list = array(
		'vendor' => 'Vendor',
		'qa_lab' => 'QA Lab',
		'third_party_transporter' => 'Third Party Transporter',
		'employee' => 'Contacts',
		'vehicle' => 'Vehicle',
		'inventory_room' => 'Section/Inventory',
		'plant_room' => 'Section/Plant',
		'inventory' => 'Inventory',
		'plant' => 'Plant',
		'plant_derivative' => 'Plant Derivative',
		'manifest' => 'Manifest',
		'inventory_transfer' => 'B2B Sale / Outgoing',
		'inventory_transfer_inbound' => 'B2B Sale / Incoming',
		'inventory_sample' => 'Inventory Sample',
		'inventory_qa_sample' => 'Lab Result',
		'inventory_adjust' => 'Inventory Adjustment',
		'sale' => 'B2C Sale',
		'tax_report' => 'Tax Reporting',
		'id_preassign' => 'IDs',
	);

	// Deprecated
	private static $_inv_kind = array(
		'5'  => 'Kief',
		'6'  => 'Flower',
		'7'  => 'Clone',
		'9'  => 'Other Plant Material',
		'10' => 'Seed',
		'11' => 'Plant Tissue',
		'12' => 'Mature Plant',
		'13' => 'Flower Lot',
		'14' => 'Other Plant Material Lot',
		'15' => 'Bubble Hash',
		'16' => 'Hash',
		'17' => 'Hydrocarbon Wax',
		'18' => 'CO2 Hash Oil',
		'19' => 'Food Grade Solvent Extract',
		'20' => 'Infused Dairy or Fat - Solid',
		'21' => 'Infused Cooking Oil',
		'22' => 'Infused Edible - Solid',
		'23' => 'Infused Edible - Liquid',
		'24' => 'Extract for Inhalation',
		'25' => 'Infused Topicals',
		'26' => 'Sample Jar',
		'27' => 'Waste',
		'28' => 'Usable Marijuana',
		'29' => 'Wet Flower',
		'30' => 'Marijuana Mix',
		'31' => 'Marijuana Mix - Packaged',
		'32' => 'Marijuana Mix - Infused',
	);

	private static $_uom_list = array(
		'each' => 'Each',
		'g' => 'Grams',
		'ml' => 'Milliliters',
		'mg' => 'Milligrams',
		'kg' => 'Kilograms',
		'oz' => 'Ounces',
		'lb' => 'Pounds',
	);

	/**
		Kind Operations
	*/
	static function kindList()
	{
		return self::$_inv_kind;
	}
	static function kindMap($k, $trim=false)
	{
		$k = intval($k);
		$r = self::$_inv_kind[ $k ];
		return ( $trim ? preg_replace('/^[\(\)\d ]+/', null, $r) : $r);
	}

	/**
	*/
	static function getUOMList()
	{
		return self::$_uom_list;
	}

	/**
		Map Adjustment Code to Name
	*/
	static function mapAdjustment($a)
	{
		$r = self::$adj_list[$a];
		if (empty($r)) {
			$r = $a;
		}
		return $r;
	}

	/**
	*/
	static function mapLocationType($t)
	{
		switch ($t) {
		case 1:
			return 'G1';
		case 2:
			return 'G2';
		case 3:
			return 'G3';
		case 4:
			return 'G1P';
		case 5:
			return 'G2P';
		case 6:
			return 'G3P';
		case 7:
			return 'P';
		case 8:
			return 'R';
		case 9: // Appeared on 2016-05-28 w/o notice - seems to be tribal /djb
			return 'Tribe';
		case 10:
			return 'MMJ';
		case 11:
			return 'MMJ/CO-OP';
		}

		return $t;

	}

	/**
	 * Normalize an Address from the BioTrackTHC System
	 * @return normalized address, as string
	 */
	static function fixAddress($rec)
	{
		$key_list = array_keys($rec);
		foreach ($key_list as $k) {
			$rec[$k] = trim($rec[$k]);
		}
		//$zip = substr($rec['zip'], 0, 5);

		$a = array();
		$a[] = $rec['address1'];

		// Cleanup Address2 Field
		if (!empty($src['address2'])) {
			if ('none' == $src['address2']) {
				$src['address2'] = null;
			}
		}

		// sometimes Address2 is already in Address1, so only add if NOT found

		// In BioTrack the Address2 Field is Sometimes Duplicated in Address1
		// if (!empty($x['address2'])) {
		// 	$i = strpos($x['address1'], $x['address2']);
		// 	if (($i !== false) && ($i > 0)) {
		// 		$x['address1'] = substr($x['address1'], 0, $i);
		// 		$x['address1'] = preg_replace('/[, ]+$/', null, $x['address1']);
		// 	}
		// }

		if (!empty($x['address2'])) {
			if (false === strpos($rec['address1'], $rec['address2'])) {
				$a[] = $rec['address2'];
			}
		}
		$a[] = sprintf(', %s', $rec['city']);
		$a[] = sprintf(', %s', $rec['state']);
		$a[] = $rec['zip'];
		$a = implode(' ', $a);

		$a = preg_replace('/\s+/', ' ', $a); // Strip duplicated Space
		$a = preg_replace('/\s+,/', ',', $a); // Space+Comma to just Comma
		$a = trim($a);

		return $a;

	}

	/**
		Constructor
		@param $x Array of RBE Options
	*/
	function __construct($x=null)
	{
		if (!empty($x)) {
			if (is_array($x)) {
				$this->_company = $x['company'];
				$this->_username = $x['username'];
				$this->_password = $x['password'];
			} elseif (is_string($x)) {
				$this->_sid = $x;
			}
		}
	}

	/**
		Format an Error Message
		@param $res The Response String or Object
	*/
	function formatError($res)
	{

		if (empty($res)) {
			return sprintf('RBE#135: %s Error: Empty Response', $this->_name);
		}

		//if (empty($res['error'])) {
		//	return sprintf('RBE#139: %s Error: Unknown Error: ' . json_encode($res));
		//}

		if (empty($res['errorcode'])) {
			return sprintf('RBE#143: %s Error: %s', $this->_name, $res['error']);
		}

		return sprintf('RBE#146: %s Error #%d: %s', $this->_name, $res['errorcode'], $res['error']);

		// $x['errorcode'] = intval($x['errorcode']);
		// return sprintf('RBE#%03d: %s', $x['errorcode'], $x['error']);
	}

	/**
	 * Set Test mode
	 */
	function setTestMode()
	{
		$this->_training = true;
	}

	/**
		Return list of Objects to Sync
	*/
	function getObjectList()
	{
		return self::$obj_list;
	}

	/**
		@param $company Company Identifier (internally OrgId)
		@param $username un
		@param $password pw
	*/
	function login($company, $username, $password)
	{
		$arg = array(
			'action' => 'login',
			'license_number' => $company,
			'username' => $username,
			'password' => $password,
		);

		if (empty($arg['username'])) {
			return false;
		}

		if (empty($arg['password'])) {
			return false;
		}

		if (empty($arg['license_number'])) {
			return false;
		}

		$ret = $this->_curl_exec($arg);
		$ret['success'] = intval($ret['success']);

		if (1 == $ret['success']) {
			$this->_sid = $ret['sessionid'];
		}

		return $ret;
	}

	function ping()
	{
		if (empty($this->_sid)) {
			return [
				'code' => 403,
				'data' => null,
				'meta' => [ 'detail' => 'No Session is Active [LRB-319]' ],
			];
		}

		return [
			'code' => 200,
			'data' => null,
			'meta' => [ 'detail' => 'Everything is Awesome' ],
		];

	}

	/**
		Lookup a Customer or Patient in the RBE
		@param $mp Medical Patient
		@param $cg Care Giver
	*/
	function card_lookup($mp, $cg)
	{
		$arg = array(
			'action' => 'card_lookup',
			'card_id' => $mp,
			'caregiver_card_id' => $cg,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $id Employee ID
		@param $name Name
		@param $dob Date of Birth
		@param $doh Date of Hire
	*/
	function employee_add($id, $name, $dob, $doh)
	{
		$dob = strtotime($dob);
		$doh = strtotime($doh);

		$arg = array(
			'action' => 'employee_add',
			'employee_id' => $id,
			'employee_name' => trim($name),
			'birth_year' => strftime('%Y', $dob),
			'birth_month' => strftime('%m', $dob),
			'birth_day' => strftime('%d', $dob),
			'hire_year' => strftime('%Y', $doh),
			'hire_month' => strftime('%m', $doh),
			'hire_day' => strftime('%d', $doh),
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $id Employee ID
		@param $name Name
		@param $dob Date of Birth
		@param $doh Date of Hire
	*/
	function employee_modify($id, $name, $dob, $doh)
	{
		$dob = strtotime($dob);
		$doh = strtotime($doh);

		$res = $this->_curl_exec(array(
			'action' => 'employee_modify',
			'employee_id' => $id,
			'employee_name' => trim($name),
			'birth_year' => strftime('%Y', $dob),
			'birth_month' => strftime('%m', $dob),
			'birth_day' => strftime('%d', $dob),
			'hire_year' => strftime('%Y', $doh),
			'hire_month' => strftime('%m', $doh),
			'hire_day' => strftime('%d', $doh),
		));
		return $res;
	}

	/**
		@param $id Employee ID
	*/
	function employee_remove($id)
	{
		$res = $this->_curl_exec(array(
			'action' => 'employee_remove',
			'employee_id' => $id,
		));
		return $res;
	}

	/**
		@param $l License/Location Number
		@param $c Count
	*/
	function id_preassign($l, $c=1)
	{
		$arg = array(
			'action' => 'id_preassign',
			'location' => $l,
			'count' => $c,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $iid Inventory Barcode ID
		@param $rem_q Quantity
		@param $rem_u Unit
		@param $rem_type Type
		@param $rem_text Free Text
	*/
	function inventory_adjust($iid, $q, $qu, $rem_type, $rem_text)
	{
		$arg = array(
			'action' => 'inventory_adjust',
			'data' => array(
				'barcodeid' => $iid,
				'quantity' => sprintf('%0.6f', $q),
				'quantity_uom' => $qu,
				// 'remove_quantity' => $q,
				// 'remove_quantity_uom' => $qu,
				'type' => $rem_type,
				'reason' => $rem_text,
			),
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $iid Inventory Barcode ID
		@param $q Quantity
	*/
	function inventory_adjust_usable($iid, $q)
	{
		$arg = array(
			'action' => 'inventory_adjust_usable',
			'barcodeid' => $iid,
			'quantity' => sprintf('%0.6f', $q),
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $x one, or array of Barcode ID
	*/
	function inventory_check($x)
	{
		if (!is_array($x)) $x = array($x);

		return $this->_curl_exec(array(
			'action' => 'inventory_check',
			'barcodeid' => $x,
		));
	}


	/**
		@param $id_data array of (barcodeid, remove_quantity, remove_quantity_uom)
		@param $d_kind Derivative Kind
	*/
	/*
		According to the Docuentation the rm_data can be an array of inventory
		But! According to TJ from BioTrash if you have no_modification the it cannot be an array
	*/
	function inventory_convert($rm_data, $d_kind, $s_name, $p_name, $d_size, $d_unit, $du_size, $du_unit, $np_size, $np_unit, $w_q, $w_u, $qa)
	{
		$arg = array(
			'action' => 'inventory_convert',
			'data' => $rm_data,
			'derivative_strain' => trim($s_name),
			'derivative_product' => trim($p_name),
			'derivative_type' => $d_kind,
			'derivative_quantity' => $d_size,
			'derivative_quantity_uom' => $d_unit,
			'derivative_usable' => $du_size,
			'derivative_usable_uom' => $du_unit,
			'net_package' => floatval($np_size),
			'net_package_uom' => $np_unit,
			'waste' => $w_q,
			'waste_uom' => $w_u,
			'no_modification' => intval($qa),
		);

		$res = $this->_curl_exec($arg);

		return $res;
	}

	/**
	*/
	function inventory_convert_undo($id)
	{
		$arg = array(
			'action' => 'inventory_convert_undo',
			'barcodeid' => $id,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $qty New Weight @deprecated
		@param $uom New Weight UOM @deprecated
		@param $src Array of Source & Removal Definitions
		@param $med 0|1 Is Medical
	*/
	function inventory_create_lot($qty, $uom, $src, $med=0)
	{
		$arg = array(
			'action' => 'inventory_create_lot',
			// 'lot_type' => 13, 14, 30
			'is_medical' => intval($med),
			'data' => $src,
			//'use_preassigned' => 1,
			//'barcodeid_preassign' => array('4157360000000025'),
		);

		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $x Barcode ID
	*/
	function inventory_destroy($x)
	{
		return $this->_curl_exec(array(
			'action' => 'inventory_destroy',
			'barcodeid' => $x,
		));
	}

	/**
		@param $x Barcode ID
		@param $r_code Reason Code
		@param $r_text Reason Text
	*/
	function inventory_destroy_schedule($x, $r_code, $r_text)
	{
		if (!is_array($x)) $x = array($x);

		return $this->_curl_exec(array(
			'action' => 'inventory_destroy_schedule',
			'barcodeid' => $x,
			'reason_extended' => $r_code,
			'reason' => $r_text,
		));
	}

	/**
		@param $x Barcode ID or Array of Barcode IDs
	*/
	function inventory_destroy_schedule_undo($x)
	{
		if (!is_array($x)) $x = array($x);

		$arg = array(
			'action' => 'inventory_destroy_schedule_undo',
			'barcodeid' => $x,
		);

		return $this->_curl_exec($arg);
	}

	/**
		@param $x Barcode ID
		@param $sn Strain, null to leave unchanged
		@param $pn Product
	*/
	function inventory_modify($bc, $sn, $pn, $np=null, $np_uom=null, $in_proc=false)
	{
		$arg = array(
			'action' => 'inventory_modify',
			'barcodeid' => $bc,
		);

		if (!empty($sn)) {
			$arg['strain'] = trim($sn);
		}

		$arg['productname'] = trim($pn);

		// @hack BioTrack Workaround, their system cannot update to a blank product name
		// So we fake it with a space, which we trim on import /djb 20170724
		if (empty($arg['productname'])) {
			$arg['productname'] = ' ';
		}

		if (!empty($np)) {

			if (empty($np_uom)) {
				$np_uom = 'g';
			}

			$arg['net_package'] = floatval($np);
			$arg['net_package_uom'] = $np_uom;
		}

		$arg['in_process'] = intval($in_proc);

		return $this->_curl_exec($arg);
	}

	/**
		Move Inventory
		@param $rid Section ID
		@param $ida ID Scalar of Array of IDs
	*/
	function inventory_move($id_list, $rid)
	{
		if (!is_array($id_list)) {
			$id_list = array($id_list);
		}

		$rid = (preg_match('/^(I|P)([0-9a-f]+)$/', $rid, $m) ?  hexdec($m[2]) : $rid);

		$arg = array(
			'action' => 'inventory_move',
			'data' => array(),
		);

		// Array of IDs to Move
		foreach ($id_list as $x) {
			$arg['data'][] = array(
				'barcodeid' => $x,
				'room' => $rid,
			);
		}

		$res = $this->_curl_exec($arg);

		return $res;

	}

	function inventory_new($arg)
	{
		$tmp = array_merge(array('action' => 'inventory_new'), $arg);
		return $this->_curl_exec($tmp);
	}

	/**
		Create an Employee or Vendor Sample
	*/
	function inventory_sample($iid, $type, $q, $q_uom, $evid=null, $edu=0)
	{
		$type = intval($type);

		$arg = array(
			'action' => 'inventory_sample',
			'barcodeid' => $iid,
			'quantity' => floatval($q),
			'quantity_uom' => $q_uom,
			'educational_sample' => intval($edu),
			'sample_type' => $type,
		);
		switch ($type) {
		case 1: // Vendor
			$arg['vendor_license'] = $evid;
			break;
		case 2: // Employee
			$arg['employee_id'] = $evid;
			break;
		default:
			throw new Exception('Invalid Sample Type');
		}

		$res = $this->_curl_exec($arg);

		return $res;
	}

	/**
	*/
	function inventory_split($x)
	{
		$loc = $this->_License['guid'];
		$res = $this->_curl_exec(array(
			'action' => 'inventory_split',
			'location' => $loc,
			'data' => $x,
		));
		return $res;
	}

	/**
		@param $lic Location ID not License
	*/
	function inventory_manifest($loc, $eid, $vid, $stop_data)
	{
		$arg = array(
			'action' => 'inventory_manifest',
			'employee_id' => $eid,
			'vehicle_id'  => $vid,
			'location' =>  $loc,
			'stop_overview' => $stop_data,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $x Location License Identifier
	*/
	function inventory_manifest_lookup($x)
	{
		$res = $this->_curl_exec(array(
			'action' => 'inventory_manifest_lookup',
			'location' => $x,
		));
		return $res;
	}

	/**
		Basically Allows to Re-Assign
	*/
	function inventory_manifest_modify($mid, $eid, $name=null, $dob=null) // v1.09
	{
		$arg = array(
			'action' => 'inventory_manifest_modify',
			'manifest_id' => $mid,
			'employee_id' => $eid,
		);
		// Optional, For Pickup
		if (!empty($name)) {
			$arg['employee_name'] = trim($name);
		}
		// Optional, For Pickup
		if (!empty($dob)) {
			$arg['employee_dob'] = $dob;
		}

		$res = $this->_curl_exec($arg);

		return $res;
	}

	/**
		@param $lic Target License (mine)
		@param $lic_source Source License of the Product
		@param $mid Manifest ID
		@param $item_list Array of Inventory

	*/
	function inventory_manifest_order($lic, $lic_source, $mid, $item_list)
	{
		$arg = array(
			'action' => 'inventory_manifest_order',
			'location' => $lic,
			'vendor_license' => $lic_source,
			'manifest_id' => $mid,
			'data' => $item_list,
		);

		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $e Employee Details
		@param $v Vehicle Details
		@param $o Originator License number
		@param $stop_data Array of Stop Details
	*/

	function inventory_manifest_pickup($e, $v, $o, $stop_data)
	{
		$arg = array(
			'action' => 'inventory_manifest_pickup',
			'employee_id' => trim($e['id']),
			'employee_name' => trim($e['name']),
			'employee_dob' => strftime('%m/%d/%Y', $e['dob']),
			'vehicle_color' => trim($v['color']),
			'vehicle_year' => trim($v['year']),
			'vehicle_make' => trim($v['make']),
			'vehicle_model' => trim($v['model']),
			'vehicle_plate' => trim($v['tag']),
			'vehicle_vin' => trim($v['vin']),
			'location' => $o,
			'stop_overview' => $stop_data
		);

		$res = $this->_curl_exec($arg);
		return $res;

	}

	/**
		@param $l Origin Location License Number
		@param $c Third Party Carrier License Number
		@param $sd Stop Data Set
	*/
	function inventory_manifest_third_party($l, $c, $sd)
	{
		$arg = array(
			'action' => 'inventory_manifest_third_party',
			'location' => $l,
			'third_party_license' => $c,
			'stop_overview' => $sd,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		Void a Manifest
		@param $mid Manifest ID
	*/
	function inventory_manifest_void($mid)
	{
		$arg = array(
			'action' => 'inventory_manifest_void',
			'manifest_id' => $mid,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	function inventory_manifest_void_items($mid, $iid)
	{
		$arg = array(
			'action' => 'inventory_manifest_void_items',
			'manifest_id' => $mid,
			'barcodeid' => $iid,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	function inventory_manifest_void_stop($mid, $sno)
	{
		$arg = array(
			'action' => 'inventory_manifest_void_stop',
			'manifest_id' => $mid,
			'stop_number' => intval($sno),
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**

	*/
	function inventory_qa_sample($bid, $lab, $qty, $uom, $use)
	{
		$arg = array(
			'action' => 'inventory_qa_sample',
			'barcodeid' => $bid,
			'lab_id' => $lab,
			'quantity' => $qty,
			'quantity_uom' => $uom,
			'use' => $use,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	function inventory_qa_sample_non_mandatory($bid, $lab, $qty, $uom)
	{
		$arg = array(
			'action' => 'inventory_qa_sample_non_mandatory',
			'barcodeid' => $bid,
			'lab_id' => $lab,
			'quantity' => $qty,
			'quantity_uom' => $uom,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $iid Inventory ID to get a new Sample for
	*/
	function inventory_qa_sample_override_request($iid)
	{
		$arg = array(
			'action' => 'inventory_qa_sample_override_request',
			'barcodeid' => $iid,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $iid Inventory ID to ByPass
		The docx file calls this Sample ID but TJs comment on JIRA reads 'transfer specified product'
	*/
	function inventory_qa_sample_results_bypass($iid)
	{
		$arg = array(
			'action' => 'inventory_qa_sample_results_bypass',
			'barcodeid' => $iid,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		Void a QA Sample
	*/
	function inventory_qa_sample_void($txn)
	{
		$arg = array(
			'action' => 'inventory_qa_sample_void',
			'transactionid' => $txn,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		Pushes Data to the State
		@param $guid The Sample GUID
		@param $test_data the Test Data Spec
	*/
	function inventory_qa_sample_results($guid, $test_data)
	{
		$arg = array(
			'action' => 'inventory_qa_sample_results',
			'sample_id' => $guid,
			'test' => $test_data
		);
		$res = $this->_curl_exec($arg);
		/*
		Array
		(
			[errorcode] => 200
			[error] => Only QA Labs may enter results.
			[success] => 0
		)
		*/
		return $res;
	}

	/**
		inventory_qa_check
	*/
	function inventory_qa_check($sid)
	{
		$arg = array(
			'action' => 'inventory_qa_check',
			'sample_id' => $sid,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		inventory_qa_check
		@note doesn't really return all results, only most recent
	*/
	function inventory_qa_check_all($sid)
	{
		$arg = array(
			'action' => 'inventory_qa_check_all',
			'barcodeid' => array($sid),
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	function inventory_room_add($arg) // @deprecated
	{
		return $this->_curl_exec(array_merge(array('action' => 'inventory_room_add'), $arg));
	}

	function inventory_room_modify($rid, $name, $loc, $q) // @deprecated
	{
		$rid = (preg_match('/^(I|P)([0-9a-f]+)$/', $rid, $m) ?  hexdec($m[2]) : $rid);
		$arg = array(
			'action' => 'inventory_room_modify',
			'id' => $rid,
			'name' => trim($name),
			'location' => $loc,
			'quarantine' => intval($q)
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param @loc Location License ID
		@param $id Section ID
	*/
	function inventory_room_remove($loc, $rid)
	{
		$rid = (preg_match('/^(I|P)([0-9a-f]+)$/', $rid, $m) ?  hexdec($m[2]) : $rid);

		$res = $this->_curl_exec(array(
			'action' => 'inventory_room_remove',
			'location' => $loc,
			'id' => $rid,
		));
		return $res;
	}

	/**
		@param $l Location, but it's actually a 6 digit license number of the source of the inventory
		@note with 6 digit sender license number we get: "NMDOH Error: #63: You provided an invalid location license number."
		@note with 6 digit receiver location number we get: "NMDOH Error: #602: The barcode identifier could not be found under the specified license number or has already been received."
		@note with 9 digit receiver UBI we get:
		@param $d Data, array of (Barcode, Quantity, UOM)
	*/
	function inventory_transfer_inbound($l, $d)
	{
		$arg = array(
			'action' => 'inventory_transfer_inbound',
			'location' => $l,
			'data' => $d,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $txn Transaction ID
		@param $oid Object ID
		@param $p Price
	*/
	function inventory_transfer_inbound_modify($txn, $oid, $p)
	{
		$arg = array(
			'action' => 'inventory_transfer_inbound_modify',
			'transactionid' => $txn,
			'barcodeid' => $oid,
			'price' => floatval($p),
		);
		$res = $this->_curl_exec($arg);
		return $res;

	}

	/**
		@param $l Location License
		@param $m Manifest ID
	*/
	function inventory_transfer_lookup($l, $m)
	{
		$res = $this->_curl_exec(array(
			'action' => 'inventory_transfer_lookup',
			'location' => $l,
			'manifest_id' => $m,
		));
		return $res;
	}

	/**

	*/
	function inventory_transfer_outbound($mid, $ivi_list)
	{
		$arg = array(
			'action' => 'inventory_transfer_outbound',
			'manifest_id' => $mid,
			'data' => $ivi_list,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $tid Transaction ID from the inventory_transfer_outbound request
		@param $rid Resource Barcode Identifier
		@param $usd New Price
	*/
	function inventory_transfer_outbound_modify($tid, $rid, $usd)
	{
		$arg = array(
			'action' => 'inventory_transfer_outbound_modify',
			'transactionid' => $tid,
			'barcodeid' => $rid,
			'price' => floatval($usd),
			// We don't use this because it's so unreliable
			// 'item_number' => 0,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	function inventory_transfer_outbound_return($loc, $data)
	{
		$arg = array(
			'action' => 'inventory_transfer_outbound_return',
			'location' => $loc,
			'data' => $data,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}


	function inventory_transfer_outbound_return_lookup($loc)
	{
		$arg = array(
			'action' => 'inventory_transfer_outbound_return_lookup',
			'location' => $loc,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	function nonce_replay($n)
	{
		$arg = array(
			'action' => 'nonce_replay',
			'nonce' => $n,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}


	/**
		Plant Functions
		@param $idl Barcode ID List
	*/
	function plant_convert_to_inventory($idl)
	{
		if (!is_array($idl)) {
			$idl = array($idl);
		}

		$arg = array(
			'action' => 'plant_convert_to_inventory',
			'barcodeid' => $idl,
		);

		$res = $this->_curl_exec($arg);

		return $res;
	}


	/**
		Cures a Plant
		@param $pid Plant ID
		@param $rid Section the Collection is Happengin IN?
		@param $weights Weight Data
		@param $cts Collect Timestamp
		@param $add Collect Additional 0|1
	*/
	function plant_cure($pid, $weight_data, $rid=null, $cts=null, $add=0)
	{
		if (empty($cts)) {
			$cts = $_SERVER['REQUEST_TIME'];
		}
		$rid = (preg_match('/^(I|P)([0-9a-f]+)$/', $rid, $m) ?  hexdec($m[2]) : $rid);

		// echo "plant_cure($pid, $rid, $inv06_q, $inv09_q, $inv27_q)\n";
		$arg = array(
			'action' => 'plant_cure',
			'collectiontime' => $cts,
			'barcodeid' => $pid,
			//'location' => $loc,
			'room' => $rid,
			'weights' => $weight_data,
			'collectadditional' => intval($add),
		);

		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		Rollback a Cure Operation
	*/
	function plant_cure_undo($txn)
	{
		$arg = array(
			'action' => 'plant_cure_undo',
			'transactionid' => $txn,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		Mark a Plant for Destruction
	*/
	function plant_destroy($id)
	{
		$arg = array(
			'action' => 'plant_destroy',
			'barcodeid' => array($id),
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $id Barcode ID
		@param $r_code Reason Code
		@param $r_text Reason Text
	*/
	function plant_destroy_schedule($id, $r_code, $r_text, $override=0)
	{
		$res = $this->_curl_exec(array(
			'action' => 'plant_destroy_schedule',
			'barcodeid' => $id,
			'reason_extended' => $r_code,
			'reason' => $r_text,
		));
		return $res;
	}

	/**
		@param $ids Barcode ID List
	*/
	function plant_destroy_schedule_undo($ids)
	{
		if (!is_array($ids)) {
			$ids = array($ids);
		}

		$res = $this->_curl_exec(array(
			'action' => 'plant_destroy_schedule_undo',
			'barcodeid' => $ids,
		));

		return $res;
	}

	/**
		@param $id Plant ID or Array of Plant IDs
		@param $cts Collection Time
		@param $wd Weight Data
		@param $rid Section ID
		@param $add Additional Collecitons?
		@param $wet Is Wet 1/0
	*/
	function plant_harvest($pid, $cts, $weights, $rid=null, $add=0, $wet=0)
	{
		$rid = (preg_match('/^(I|P)([0-9a-f]+)$/', $rid, $m) ?  hexdec($m[2]) : $rid);

		$arg = array(
			'action' => 'plant_harvest',
			'barcodeid' => $pid,
			'collectiontime' => $cts,
			'weights' => $weights,
			'new_room' => $rid,
			'collectadditional' => intval($add),
			'wet' => intval($wet),
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		Schedule a Plant for Harvesting
		@param $pid Plant ID or array of IDs
	*/
	function plant_harvest_schedule($pid)
	{
		if (!is_array($pid)) {
			$pid = array($pid);
		}

		$arg = array(
			'action' => 'plant_harvest_schedule',
			'barcodeid' => $pid,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param String or Array of Strings of GUIDs to Un-Schedule
	*/
	function plant_harvest_schedule_undo($pid)
	{
		if (!is_array($pid)) {
			$pid = array($pid);
		}

		$arg = array(
			'action' => 'plant_harvest_schedule_undo',
			'barcodeid' => $pid,
		);

		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		Plant Harvest Undo Wet Weight
	*/
	function plant_harvest_undo($txn)
	{
		$arg = array(
			'action' => 'plant_harvest_undo',
			'transactionid' => $txn,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $id One or More Plant IDs
		@param $name Strain
		@param $mom Is Mother
		@param $dob Date of Birth/Planted
	*/
	function plant_modify($id, $name, $mother=null, $dob=null)
	{
		$arg = array(
			'action' => 'plant_modify',
			'barcodeid' => $id,
			'strain' => trim($name),
		);

		if (!empty($mother)) {
			$arg['mother'] = ($mother ? '1' : '0');
		}

		if (!empty($dob)) {
			$arg['birthdate'] = _date('Ymd', $dob);
		}

		if (empty($arg['strain'])) {
			unset($arg['strain']);
		}

		$res = $this->_curl_exec($arg);

		return $res;
	}


	/**
		@param $id One or More Plant IDs
		@param $rid Section Integer ID
	*/
	function plant_move($id_list, $rid)
	{
		if (!is_array($id_list)) {
			$id_list = array($id_list);
		}

		$rid = (preg_match('/^(I|P)([0-9a-f]+)$/', $rid, $m) ?  hexdec($m[2]) : $rid);

		$arg = array(
			'action' => 'plant_move',
			'room' => $rid,
			'barcodeid' => $id_list,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	function plant_new($arg)
	{
		$arg['room'] = (preg_match('/^(I|P)([0-9a-f]+)$/', $arg['room'], $m) ?  hexdec($m[2]) : $arg['room']);
		$arg = array_merge(array('action' => 'plant_new'), $arg);
		$res = $this->_curl_exec($arg);
		return $res;

	}

	/**
		@param $bcid Barcode ID
	*/
	function plant_new_undo($bcid)
	{
		$arg = array(
			'action' => 'plant_new_undo',
			'barcodeid' => $bcid,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}


	/**
		@param $loc Location
		@param $val Weight
		@param $uom g|kg|mg|oz|lb
	*/
	function plant_waste_weigh($loc, $val, $uom)
	{
		$loc = trim($loc);
		if (empty($loc)) {
			throw new Exception("Invalid Location");
		}
		$val = trim($val);
		if (empty($val)) {
			throw new Exception("Invalid Value");
		}
		$uom = trim($uom);
		if (empty($uom)) {
			throw new Exception("Invalid Unit of Measure");
		}

		$arg = array(
			'action' => 'plant_waste_weigh',
			'collectiontime' => time(),
			'location' => $loc,
			'weight' => floatval($val),
			'uom' => $uom,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $kind Inventory Type ID
	*/
	function plant_yield_modify($txn, $cts, $weight_data)
	{
		// The state API does not handle this conversion
		//$key_list = array_keys($weight_data);
		//foreach ($key_list as $key) {
		//	switch ($weight_data[$key]['uom']) {
		//	case 'lb':
		//		$weight_data[$key]['amount'] = sprintf('%0.8f', floatval($weight_data[$key]['amount']) * 453.592);
		//		$weight_data[$key]['uom'] = 'g';
		//		break;
		//	}
		//}

		$arg = array(
			'action' => 'plant_yield_modify',
			'collectiontime' => $cts,
			'transactionid' => $txn,
			'weights' => $weight_data,
		);
		$res = $this->_curl_exec($arg);
		return $res;

	}

	/**
		Section Functions
	*/
	function plant_room_add($arg) // @deprecated
	{
		$arg = array_merge(array('action' => 'plant_room_add'), $arg);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $id Identifier in State System
		@param $n Name
		@parma $l Location Identifier
	*/
	function plant_room_modify($rid, $n, $l) // @deprecated
	{
		$rid = (preg_match('/^(I|P)([0-9a-f]+)$/', $rid, $m) ?  hexdec($m[2]) : $rid);

		$arg = array(
			'action' => 'plant_room_modify',
			'id' => $rid,
			'name' => trim($n),
			'location' => $l,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param @loc Location License ID
		@param $id Section ID
	*/
	function plant_room_remove($loc, $rid) // @deprecated
	{
		$rid = (preg_match('/^(I|P)([0-9a-f]+)$/', $rid, $m) ?  hexdec($m[2]) : $rid);

		$res = $this->_curl_exec(array(
			'action' => 'plant_room_remove',
			'location' => $loc,
			'id' => $rid,
		));
		return $res;
	}

	/**
		@param $term_id Terminal ID
		@param $cust_id Customer ID
		@param $cust_key Customer Card Key
		@param $sale_data Array of { barcode, quantity, price }
	*/
	function sale_dispense($item_list, $card_key) // $term_id, $cust_id, $cust_key, )
	{
		$arg = array(
			'action' => 'sale_dispense',
			//'terminal_id' => $term_id,
			'sale_time' => $_SERVER['REQUEST_TIME'],
			// 'card_id' => $cust_id,
			'card_key' => $card_key,
			'data' => $item_list,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
	*/
	function sale_modify()
	{
		die(__FUNCTION__  . ' not implemented');
	}

	/**
	*/
	function sale_refund()
	{
		die(__FUNCTION__  . ' not implemented');
	}

	/**
	*/
	function sale_void()
	{
		die(__FUNCTION__  . ' not implemented');
	}

	/**
		@param $l Location
		@param $m Month
		@param $y Year
		@param $sales Gross Sales
		@param $taxes Excise Taxes to pay
	*/
	function tax_obligation_file($l, $m, $y, $sales, $taxes)
	{
		$arg = array(
			'action' => 'tax_obligation_file',
			'location' => $l,
			'month' => min(12, max(1, intval($m))),
			'year' => intval($y),
			'gross_sales' => $sales,
			'excise_tax' => $taxes,
			'verify' => 0, // Docs say boolean but only 1/0 are accepted
		);

		$res = $this->_curl_exec($arg);

		return $res;
	}

	function user_add($un, $pw, $acl=null)
	{
		$arg = array(
			'action' => 'user_add',
			'new_admin' => 1,
			'new_username' => trim($un),
			'new_password' => trim($pw),
			'new_permissions' => $acl,
		);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	function user_modify($un, $pw, $acl=null)
	{
		$arg = array(
			'action' => 'user_modify',
			'new_admin' => 1,
			'new_username' => trim($un),
			'new_permissions' => $acl,
		);

		if (!empty($pw)) {
			$arg['new_password'] = trim($pw);
		}
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $un Username
	*/
	function user_remove($un)
	{
		$arg = array(
			'action' => 'user_remove',
			'new_username' => trim($un),
		);

		if (empty($arg['new_username'])) {
			throw new Exception('Invalid Username for Remove');
		}

		return $this->_curl_exec($arg);
	}

	/**
		@param $opt Vehicle Description
		@todo Error Check Each Option
	*/
	function vehicle_add($opt)
	{
		$arg = array(
			'action' => 'vehicle_add',
		);
		$arg = array_merge($arg, $opt);
		return $this->_curl_exec($arg);

	}

	/**
		@param $opt Vehicle Description
	*/
	function vehicle_modify($opt)
	{
		$arg = array(
			'action' => 'vehicle_modify',
		);
		$arg = array_merge($arg, $opt);
		return $this->_curl_exec($arg);

	}

	/**
		vehicle_remove($vid)
		@param $vehicle ID
	*/
	function vehicle_remove($vid)
	{
		$arg = array(
			'action' => 'vehicle_remove',
			'vehicle_id' => $vid,
		);
		return $this->_curl_exec($arg);
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_check($opt=null)
	{
		$arg = array(
			'action' => 'sync_check',
			'download' => '0',
		);
		if (empty($opt)) {
			$opt = array();
		}
		$arg = array_merge($arg, $opt);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**

	*/
	function _sync_object($act, $opt=null)
	{
		if (is_array($opt)) {
			$arg = array(
				'action' => $act,
				'download' => 1,
				'transaction_start' => intval($opt['min']),
				'transaction_end' => intval($opt['max']),
			);
		} elseif (is_numeric($opt)) {
			$arg = array(
				'action' => $act,
				'download' => 1,
				'transaction_start' => intval($opt),
			);
		} else {
			$arg = array(
				'action' => $act,
				'download' => 1,
			);
		}

		return $arg;
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_employee($min=null)
	{
		$arg = $this->_sync_object('sync_employee', $min);
		return $this->_curl_exec($arg);
	}

	/**
		Not even sure I need to use this one
	*/
	function sync_id_preassign($opt=null)
	{
		$arg = $this->_sync_object('sync_id_preassign', $opt);
		return $this->_curl_exec($arg);
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_inventory($min=null)
	{
		$arg = $this->_sync_object('sync_inventory', $min);
		return $this->_curl_exec($arg);
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_inventory_adjust($min=null)
	{
		$arg = $this->_sync_object('sync_inventory_adjust', $min);
		return $this->_curl_exec($arg);
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_inventory_qa_sample($min=null)
	{
		$arg = $this->_sync_object('sync_inventory_qa_sample', $min);
		return $this->_curl_exec($arg);
	}

	/**
	*/
	function sync_inventory_sample($min=null)
	{
		//$arg = array(
		//	'sync_check',
		//	'download' => 1,
		//	'data' => array(
		//		'inventory_sample',
		//		'transaction_start' => intval($min),
		//	),
		//);

		$arg = $this->_sync_object('sync_inventory_sample', $min);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_inventory_transfer($min=null)
	{
		$arg = $this->_sync_object('sync_inventory_transfer', $min);
		return $this->_curl_exec($arg);
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_inventory_transfer_inbound($min=null)
	{
		$arg = $this->_sync_object('sync_inventory_transfer_inbound', $min);
		return $this->_curl_exec($arg);
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_manifest($min=null)
	{
		$arg = $this->_sync_object('sync_manifest', $min);
		return $this->_curl_exec($arg);
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_plant($min=null)
	{
		$arg = $this->_sync_object('sync_plant', $min);
		$ret = $this->_curl_exec($arg);
		return $ret;
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_plant_derivative($min=null)
	{
		$arg = $this->_sync_object('sync_plant_derivative', $min);
		return $this->_curl_exec($arg);
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_plant_room($min=null)
	{
		$arg = $this->_sync_object('sync_plant_room', $min);
		return $this->_curl_exec($arg);
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_inventory_room($min=null)
	{
		$arg = $this->_sync_object('sync_inventory_room', $min);
		return $this->_curl_exec($arg);
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_qa_lab($min=null)
	{
		$arg = $this->_sync_object('sync_qa_lab', $min);
		return $this->_curl_exec($arg);
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_sale($min=null)
	{
		$arg = $this->_sync_object('sync_sale', $min);
		return $this->_curl_exec($arg);
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_tax_report($min=null)
	{
		$arg = $this->_sync_object('sync_tax_report', $min);
		return $this->_curl_exec($arg);
	}

	/**
	*/
	function sync_third_party_transporter($min=null)
	{
		$arg = $this->_sync_object('sync_third_party_transporter', $min);
		return $this->_curl_exec($arg);
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_vehicle($min=null)
	{
		$arg = $this->_sync_object('sync_vehicle', $min);
		$res = $this->_curl_exec($arg);
		return $res;
	}

	/**
		@param $arg array of data for Sync Check
	*/
	function sync_vendor($min=null)
	{
		$arg = $this->_sync_object('sync_vendor', $min);
		return $this->_curl_exec($arg);
	}

	/**
	 * Interface for Strains
	 */
	function strain()
	{
		$r = new RBE_BioTrack_Strain($this);
		return $r;
	}

	/**
	 * Interface for Section
	 */
	function section()
	{
		$r = new RBE_BioTrack_Section($this);
		return $r;
	}

	/**
		Executue the Request with the given Arguments
		@param $arg Array of Arguments for API call
	*/
	function _curl_exec($arg)
	{
		$arg['API'] = '4.0';
		if (!empty($this->_sid)) {
			$arg['sessionid'] = $this->_sid;
		}

		if (!empty($this->_training)) {
			$arg['training'] = 1;
		}

		if (empty($arg['nonce'])) {
			$arg['nonce'] = sha1(serialize($arg).microtime(true));
		}

		$t0 = microtime(true);

		$ch = $this->_curl_init($this->_api_base);

		$this->_arg = $arg;
		$this->_req = json_encode($this->_arg);
		curl_setopt($ch, CURLOPT_POST, true);
		// curl_setopt($ch, CURLOPT_HEADER, true); // Get Headers in Response?
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_req);

		$this->_raw = curl_exec($ch);
		$this->_inf = curl_getinfo($ch);

		// this is a workaround for a biotrack bug where headers leak into the response body /djb 20170723
		$this->_raw = str_replace('Content-Type: text/plain', null, $this->_raw);
		$this->_raw = trim($this->_raw);

		$this->_ret = json_decode($this->_raw, true);
		$this->_err = json_last_error_msg();

		$t1 = microtime(true);

		// Handle Response
		switch ($this->_inf['http_code']) {
		case 200:
			// OK
			break;
		default:
			//throw new Exception(sprintf('Invalid HTTP Response Code: %d See Log: %s', $inf['http_code'], basename($log_file)));
			if (empty($this->_raw)) {
				$this->_ret = array(
					'success' => 0,
					'errorcode' => $this->_inf['http_code'],
					'error' => 'BioTrack System Error: Empty Response: Please try your request again.',
				);
			} else {
				$this->_ret = array(
					'success' => 0,
					'errorcode' => $this->_inf['http_code'],
					'error' => sprintf('BioTrack System Error; Code #%03d: Please try your request again.', $this->_inf['http_code']), //Invalid Response from WSLCB',
				);
			}
		}

		// @todo Plugin::notify_run('rbe-error-handler');
		if (!empty($this->_ret['error'])) {
			if (preg_match('/session.+expired/', $this->_ret['error'])) {
				$this->_sid = null;
			}
		}

		return $this->_ret;
	}

	/**
		Executes the Single or Multiple Requests
		@return Curl Handle
	*/
	protected function _curl_init($uri)
	{
		$ch = _curl_init($uri);

		$h = parse_url($uri, PHP_URL_HOST);

		$head = array(
			'Content-Type: text/JSON', // Not Really Accurate - Should be application/json
			sprintf('Host: %s', $h),
			sprintf('User-Agent: OpenTHC/%s', APP_BUILD),
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $head);

		return $ch;
	}

}
