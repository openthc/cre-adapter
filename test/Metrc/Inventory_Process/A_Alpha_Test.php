<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\E_Metrc\F_Lot_Process;

class A_Alpha_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_package_create()
	{
		$this->assertTrue(false, 'Not Implemented');
		// Package/Lot Create
		// $res = $rbe->packageCreate([[
		// 	'Tag' => 'ABCDEF012345670000016511',
		// 	'Room' => null,
		// 	'Item' => 'Buds',
		// 	'Quantity' => 246+666,
		// 	'UnitOfMeasure' => 'Grams',
		// 	// 'PatientLicenseNumber' => 'X00001',
		// 	// 'Note' => 'This is a note.',
		// 	// 'IsProductionBatch' => false,
		// 	// 'ProductionBatchNumber' => null,
		// 	// 'ProductRequiresRemediation' => false,
		// 	'ActualDate' => date('Y-m-d'),
		// 	'Ingredients' => [
		// 		[
		// 			'Package' => '1A4FFFB303D7E32000000084',
		// 			'Quantity' => 246.0,
		// 			'UnitOfMeasure' => 'Grams'
		// 		],
		// 		[
		// 			'Package' => '1A4FFFB303D7E32000000087',
		// 			'Quantity' => 666.0,
		// 			'UnitOfMeasure' => 'Grams'
		// 		]
		// 	]
		// ]]);
		// print_r($res);
	}

	function test_package_update()
	{
		$this->assertTrue(false, 'Not Implemented');
		// Package/Lot Change
		// $res = $rbe->packageChangeItem([[
		// 	'Label' => 'ABCDEF012345670000016511',
		// 	'Item' => 'Product 78b705e8 - Bravo',
		// ]]);
		// print_r($res);
	}

	function test_package_adjust()
	{
		$this->assertTrue(false, 'Not Implemented');
		// Package/Lot Adjust
		// $res = $rbe->packageAdjust([[
		// 	'Label' => '1A4FFFB303D7E32000000089',
		// 	'Quantity' => -666,
		// 	'UnitOfMeasure' => 'Grams',
		// 	'AdjustmentReason' => 'Proficiency Testing',
		// 	'AdjustmentDate' => date('Y-m-d'),
		// 	'ReasonNote' => 'Adjust a package using: POST /packages/v1/adjust'
		// ]]);
		// print_r($res);

	}

	function test_package_finish()
	{
		$this->assertTrue(false, 'Not Implemented');
		// Lot Finish
		// $res = $rbe->packageAdjust([[
		// 	'Label' => '1A4FFFB303D7E32000000093',
		// 	'Quantity' => -666,
		// 	'UnitOfMeasure' => 'Grams',
		// 	'AdjustmentReason' => 'Proficiency Testing',
		// 	'AdjustmentDate' => date('Y-m-d'),
		// 	'ReasonNote' => 'Finish a package using: POST /packages/v1/finish'
		// ]]);
		// print_r($res);

		// $res = $rbe->packageFinish([[
		// 	'Label' => '1A4FFFB303D7E32000000093',
		// 	'ActualDate' => date('Y-m-d'),
		// ]]);
		// print_r($res);

	}

	function test_package_finish_undo()
	{
		$this->assertTrue(false, 'Not Implemented');
		// Lot Finish Undo
		// $res = $rbe->packageFinishUndo([[
		// 	'Label' => '1A4FFFB303D7E32000000093'
		// ]]);
		// print_r($res);

	}
}
