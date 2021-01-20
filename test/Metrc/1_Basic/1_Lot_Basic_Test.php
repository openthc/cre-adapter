<?php
/**
 * Test Lot
 */

namespace Test\Metrc\Basic;

class Lot_Basic_Test extends \Test\OpenTHC_Metrc_Test
{
	public function testLotPackageCreate()
	{
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

	public function testLotPackageChange()
	{
		// Package/Lot Change
		// $res = $rbe->packageChangeItem([[
		// 	'Label' => 'ABCDEF012345670000016511',
		// 	'Item' => 'Product 78b705e8 - Bravo',
		// ]]);
		// print_r($res);
		// print_r($rbe->adjustList());
	}

	public function testLotPackageAdjust()
	{
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

	public function testLotFinish()
	{
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
	public function testLotFinishUndo()
	{
		// Lot Finish Undo
		// $res = $rbe->packageFinishUndo([[
		// 	'Label' => '1A4FFFB303D7E32000000093'
		// ]]);
		// print_r($res);
	}
}
