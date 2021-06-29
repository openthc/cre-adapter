<?php
/**
 * Testing the Lag on Transfer Data State
 */

namespace Test\E_LeafData\F_Lot_Process;

class D_Contact_Sample_Test extends \Test\LeafData_Case
{
	protected function setUp() : void
	{
		// Reset API Connection to Lab
		$this->cre = $this->_api([
			'license' => $_ENV['leafdata-g0-public'],
			'license-secret' => $_ENV['leafdata-g0-secret'],
		]);
	}

	function test_adjust()
	{
		$res = $this->cre->get('inventories?f_global_id=' . $_ENV['leafdata-53-source-lot']);
		var_dump($res);
		$this->assertNotEmpty($res);

		$this->assertCount(9, $res);
		$this->assertArrayHasKey('data', $res);
		$this->assertIsArray($res['data']);
		$this->assertCount(1, $res['data']);

		$lot = $res['data'][0];
		$this->assertCount(46, $lot);
		$this->assertGreaterThan(3, $lot['qty']);

		$qty_alpha = $lot['qty'];

		// Adjust
		$qty_delta = -1;
		$mod = [
			'adjusted_at' => date('m/d/Y g:i:s a', $_SERVER['REQUEST_TIME']),
			'global_inventory_id' => $lot['global_id'],
			'qty' => $qty_delta,
			'uom' => 'gm',
			'reason' => 'internal_qa_sample',
			'memo' => 'TEST',
		];
		$arg = [ 'inventory_adjustment' => array($mod) ];
		$res = $this->cre->post('inventory_adjustments', $arg);
		$res = $this->assertValidResponse($res);
		print_r($res);


		// Check QTY
		$qty_omega = $qty_alpha - abs($qty_delta);

		$res = $this->cre->get('inventories?f_global_id=' . $_ENV['leafdata-53-source-lot']);
		$this->assertCount(9, $res);
		$this->assertArrayHasKey('data', $res);
		$this->assertIsArray($res['data']);
		$this->assertCount(1, $res['data']);

		$lot = $res['data'][0];
		$this->assertCount(46, $lot);

		$this->assertEquals($qty_omega, $lot['qty']);

	}

	function test_sublot_and_adjust()
	{
		// Find Inventory
		$res = $this->cre->get('inventories?f_global_id=' . $_ENV['leafdata-53-source-lot']);
		$this->assertNotEmpty($res);
		$this->assertCount(9, $res);
		$this->assertArrayHasKey('data', $res);
		$this->assertIsArray($res['data']);
		$this->assertCount(1, $res['data']);

		$lot = $res['data'][0];
		$this->assertCount(46, $lot);
		$this->assertGreaterThan(3, $lot['qty']);

		// Sub-Lot The Sample

		// Adjust the Sample to Zero

	}

}
