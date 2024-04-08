<?php
/**
 * Test Helper for BioTrack
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test;

class BioTrack_Case extends \OpenTHC\CRE\Test\Base_Case
{
	protected $_sid;

	protected $_sync_table_list = [
		'vendor' => 'Vendor',
		'qa_lab' => 'QA Lab',
		'third_party_transporter' => 'Carrier',
		'employee' => 'Contacts',
		'vehicle' => 'Vehicle',
		'inventory_room' => 'Room/Inventory',
		'plant_room' => 'Room/Plant',
		'inventory' => 'Inventory',
		'plant' => 'Plant',
		'plant_derivative' => 'Plant Derivative',
		'manifest' => 'B2B/Outgoing',
		'inventory_transfer' => 'B2B/Outgoing/Item',
		'inventory_transfer_inbound' => 'B2B/Incoming',
		'inventory_sample' => 'Inventory Sample',
		'inventory_qa_sample' => 'QA Sample',
		'inventory_adjust' => 'Inventory Adjustment',
		'sale' => 'B2C/Sale',
		'tax_report' => 'Tax Reporting',
		'id_preassign' => 'IDs',
	];

	protected function setUp() : void
	{
		$this->ghc = $this->_api();
	}

	function auth($t)
	{
		// Good Login
		$arg = [
			'action' => 'login',
			'license_number' => getenv('OPENTHC_TEST_BIOTRACK_LICENSE'),
			'username' => getenv('OPENTHC_TEST_BIOTRACK_USERNAME'),
			'password`' => getenv('OPENTHC_TEST_BIOTRACK_PASSWORD'),
		];

		$res = $this->post('', $arg);
		$res = $this->assertValidResponse($res);
		$this->assertEquals(1, $res['success']);
		// var_dump($res);
		$this->_sid = $res['sessionid'];
	}

	/**
		HTTP Utility
	*/
	function post($url, $arg)
	{
		$arg['API'] = '4.0';
		$arg['sessionid'] = $this->_sid;
		// $arg['training'] = $_ENV['biotrack-training-mode'];
		return parent::post($url, $arg);
	}

	function assertValidResponse($res, $code_expect=200, $type_expect=null, $dump=null) {

		$ret = parent::assertValidResponse($res, $code_expect, $type_expect, $dump);

		$this->assertIsArray($ret);
		$this->assertArrayHasKey('success', $ret);

		return $ret;

	}

	/**
		@param $b The Base URL
	*/
	protected function _api($opt=null)
	{
		$cre = \OpenTHC\CRE::factory(getenv('OPENTHC_BIOTRACK_CRE'));
		$cre->auth();
		return $cre;
	}

}
