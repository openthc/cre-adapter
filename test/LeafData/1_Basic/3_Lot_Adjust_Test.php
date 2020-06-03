<?php
/**
 * Test that Lot Adjust Still Errors with Wacky Date
 */

namespace Test\LeafData\Basic;

class Lot_Adjust_Test extends \Test\OpenTHC_LeafData_Test
{
	protected function setUp() : void
	{
		// Reset API Connection to Lab
		$this->ghc = $this->_api([
			'license' => $_ENV['leafdata-g0-public'],
			'license-secret' => $_ENV['leafdata-g0-secret'],
		]);
	}

	/**
	 * Check that Date still acts funny
	 */
	function test_adjust_date_format_rfc3339()
	{
		$mod = [
			'adjusted_at' => date(\DateTime::RFC3339, $_SERVER['REQUEST_TIME']),
			'global_inventory_id' => $_ENV['leafdata-13-lot'],
			'qty' => -1,
			'uom' => 'gm',
			'reason' => 'reconciliation',
			'memo' => 'TEST',
		];
		$arg = [ 'inventory_adjustment' => array($mod) ];
		$res = $this->post('inventory_adjustments', $arg);
		$this->assertAdjustFailed($res);
	}

	/**
	 * Test that HINT still still asks us to use this format
	 * But also this format fails
	 */
	function test_adjust_date_format_fail()
	{
		//
		$mod = [
			'adjusted_at' => date('m/d/Y H:ia', $_SERVER['REQUEST_TIME']),
			'global_inventory_id' => $_ENV['leafdata-13-lot'],
			'qty' => -1,
			'uom' => 'gm',
			'reason' => 'reconciliation',
			'memo' => 'TEST',
		];
		$arg = [ 'inventory_adjustment' => array($mod) ];
		$res = $this->post('inventory_adjustments', $arg);
		$this->assertAdjustFailed($res);
	}

	/**
	 * Passes using some made up date format
	 */
	function test_adjust_date_format_real()
	{
		$mod = [
			'adjusted_at' => date('m/d/Y g:i:s a', $_SERVER['REQUEST_TIME']),
			'global_inventory_id' => $_ENV['leafdata-13-lot'],
			'qty' => 1,
			'uom' => 'gm',
			'reason' => 'reconciliation',
			'memo' => 'TEST',
		];
		$arg = [ 'inventory_adjustment' => array($mod) ];
		$res = $this->post('inventory_adjustments', $arg);
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res);
		$this->assertCount(1, $res);
		$res = $res[0];
		$this->assertIsArray($res);
		$this->assertCount(12, $res);

	}

	function assertAdjustFailed($res)
	{
		$res = $this->assertValidResponse($res, 422);
		$this->assertIsArray($res);
		$this->assertCount(3, $res);

		$this->assertArrayHasKey('error', $res);
		$this->assertEquals(1, $res['error']);

		$this->assertArrayHasKey('error_message', $res);
		$this->assertEmpty($res['error_message']);

		$this->assertArrayHasKey('validation_messages', $res);
		$this->assertArrayHasKey('adjusted_at', $res['validation_messages']);
		$this->assertCount(1, $res['validation_messages']['adjusted_at']);
		$msg = $res['validation_messages']['adjusted_at'][0];
		$this->assertEquals('The adjusted at does not match the format m/d/Y H:ia.', $msg);

	}

}
