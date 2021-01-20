<?php
/**
 * Handle Inventory Adjustment Records
 */

namespace OpenTHC\CRE\LeafData;

class Lot_Delta extends \OpenTHC\CRE\LeafData\Base
{
	protected $_path = '/inventory_adjustments';

	// Original List
	/*
	private $_reason_list = array(
		'create' => 'Create',
		'update' => 'Update',
		'disposal' => 'Waste/Disposal',
		'conversion' => 'Conversion',
		'transfer' => 'Transfer',
		'expired' => 'Expired',
		'damaged' => 'Damaged',
		'recalled' => 'Recalled',
		'sale' => 'Sale',
		'return' => 'Return',
		'void' => 'Void',
		'member_left_the_cooperative' => 'Member Left',
	);
	*/

	// Current List for MJFLD/WA
	private $_reason_list = array(
		'reconciliation' => 'Reconciliation',
		'internal_qa_sample' => 'Sample/QA/Internal',
		'budtender_sample' => 'Sample/Budtender',
		'vendor_sample' => 'Sample/Vendor',
		'member_left_the_cooperative' => 'Member left the Cooperative',
		'theft' => 'Theft',
		'seizure' => 'Seizure',
	);

	function create($x)
	{
		$arg = array('inventory_adjustment' => array($x));
		$res = $this->_client->call('POST', '/inventory_adjustments', $arg);

		// Remaps to a Single Result, since we only go one at a time
		if ('success' == $res['status']) {
			if (is_array($res['result']) && (1 == count($res['result'])) && !empty($res['result'][0])) {
				if (count($res['result'][0]) > 3) {
					$res['result'] = $res['result'][0];
				}
			}
		}

		return $res;

	}

	function delete()
	{
		// NO-OP
		return false;
	}

}
