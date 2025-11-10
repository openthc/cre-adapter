<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Metrc\Crop_Collect;

class A_Alpha_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_crop_collect()
	{
		$this->assertTrue(false, 'Not Implemented');
		// Plant Manicure
		// $res = $rbe->plant_collect()->manicure([[
		// 	'Plant' => '1A4FFFC303D7E32000001843',
		// 	'Weight' => 123.45,
		// 	'UnitOfWeight' => 'Grams',
		// 	'DryingRoom' => 'Eval 78b705e8',
		// 	'HarvestName' => null,
		// 	'ActualDate' => date('Y-m-d'),
		// ]]);
		// var_dump($res);
	}

	function test_crop_harvest()
	{
		$this->assertTrue(false, 'Not Implemented');
		// Plant Harvest
		// $res = $rbe->plant_collect()->harvest([[
		// 	'Plant' => '1A4FFFC303D7E32000001847',
		// 	'Weight' => 123.45,
		// 	'UnitOfWeight' => 'Grams',
		// 	'DryingRoom' => 'Eval 78b705e8',
		// 	// 'HarvestName' => '2020-01-11-Eval 78b705e8-H',
		// 	'ActualDate' => date('Y-m-d'),
		// ]]);
		// var_dump($res);
	}
}


// Harvest Create Package
// $res = $rbe->plant_collect()->harvestPackageCreate([[
// 	'Tag' => 'ABCDEF012345670000016525',
// 	'Room' => 'Eval 78b705e8',
// 	'Item' => 'Buds',
// 	'UnitOfWeight' => 'Grams',
// 	'Note' => 'Test 78b705e8',
// 	'IsProductionBatch' => false,
// 	'ActualDate' => date('Y-m-d'),
// 	'Ingredients' => [
// 		[
// 			'HarvestId' => '45001',
// 			'Weight' => 62.00,
// 			'UnitOfWeight' => 'Grams'
// 		]
// 	]
// ]]);
// print_r($res);


// Harvest Remove Waste
// $res = $rbe->plant_collect()->harvestWasteRemove([[
// 	'Id' => '45001',
// 	'WasteType' => 'Plant Material',
// 	'WasteWeight' => 31.45,
// 	'UnitOfWeight' => 'Grams',
// 	'ActualDate' => date('Y-m-d'),
// ]]);
// print_r($res);


// Harvest Finish
// $res = $rbe->plant_collect()->harvestFinish([[
// 	'Id' => '45001',
// 	'ActualDate' => date('Y-m-d'),
// ]]);
// print_r($res);


// Harvest Finish Undo
// $res = $rbe->plant_collect()->harvestFinishUndo([[
// 	'Id' => '45001',
// ]]);
// print_r($res);


################## CA #################
/**
	Harvests
*/
// Harvests
//print_r($rbe->harvestList()); exit;
//print_r($rbe->harvestList('onhold'));
//print_r($rbe->harvestList('inactive'));

// Create Packages from Harvest
//$rbe->harvestPackageCreate(array(
//array(
//	'Tag' => 'ABCDEF012345670000013518',
//	'Harvest' => '3',
//	'Item' => sprintf('Buds: %s', $code),
//	'Weight' => '420',
//	'UnitOfWeight' => 'Grams',
//	'IsProductionBatch' => false,
//	'ProductionBatchNumber' => null,
//	'ActualDate' => '2016-06-30',
//),
//array(
//	'Tag' => 'ABCDEF012345670000013519',
//	'Harvest' => '3',
//	'Item' => sprintf('Buds: %s', $code),
//	'Weight' => '420',
//	'UnitOfWeight' => 'Grams',
//	'IsProductionBatch' => false,
//	'ProductionBatchNumber' => null,
//	'ActualDate' => '2016-06-30',
//)));


// Remove Waste from Harvest
//$rbe->harvestWasteRemove(array(array(
//	'Id' => '4',
//	'UnitOfWeight' => 'Grams',
//	'WasteWeight' => 10,
//	'ActualDate' => '2016-06-30',
//)));

// Finish Harvest
//$rbe->harvestFinish(array(array(
//	'Id' => 4,
//	'ActualDate' => '2016-06-30',
//)));

// Unfinish Harvest =Done
//$rbe->harvestFinishUndo(array(array(
//	'Id' => 4
//)));



#### CA

// Manicure =DONE
//$rbe->plant_collect()->manicure(array(
//array(
//	'Plant' => 'ABCDEF012345670000011318',
//	'UnitOfWeight' => 'Grams',
//	'Weight' => sprintf('%0.2f', 55 / 2),
//	'DryingRoom' => 'Room: 41af4406',
//	'HarvestName' => sprintf('Harvest Share: %s', $code),
//	'ActualDate' => '2016-07-02',
//)
//));

// Harvest
//$rbe->plantPhaseChange(array(
//array(
//	'Label' => 'ABCDEF012345670000011319',
//	'Room' => 'Room: 41af4406',
//	'GrowthDate' => '2016-07-02',
//	'GrowthPhase' => 'Flowering',
//),
//array(
//	'Label' => 'ABCDEF012345670000011320',
//	'Room' => 'Room: 41af4406',
//	'GrowthDate' => '2016-07-02',
//	'GrowthPhase' => 'Flowering',
//)
//));
//
//$rbe->plant_collect()->harvest(array(
//array(
//	'Plant' => 'ABCDEF012345670000011319',
//	'UnitOfWeight' => 'Ounces',
//	'Weight' => sprintf('%0.2f', 55 / 2),
//	'DryingRoom' => 'Room: 41af4406',
//	'HarvestName' => sprintf('Harvest Buds: %s', $code),
//	'ActualDate' => '2016-07-02',
//),
//array(
//	'Plant' => 'ABCDEF012345670000011320',
//	'UnitOfWeight' => 'Ounces',
//	'Weight' => sprintf('%0.2f', 55 / 2),
//	'DryingRoom' => 'Room: 41af4406',
//	'HarvestName' => sprintf('Harvest Buds: %s', $code),
//	'ActualDate' => '2016-07-02',
//)
//));


##### FROM OR / MT

// DONE
// Create Packages from Harvest
/*
$rbe->harvestPackageCreate(array(
array(
	'Tag' => 'ABCDEF012345670000015150',
	'Harvest' => '3702',
	'Item' => 'Item: 41af4406-Buds',
	'Weight' => 150,
	'UnitOfWeight' => 'Grams',
	'IsProductionBatch' => false,
	'ProductionBatchNumber' => null,
	'ActualDate' => strftime('%Y-%m-%d'),
),
array(
	'Tag' => 'ABCDEF012345670000015151',
	'Harvest' => '3702',
	'Item' => 'Item: 41af4406-Buds',
	'Weight' => 151,
	'UnitOfWeight' => 'Grams',
	'IsProductionBatch' => false,
	'ProductionBatchNumber' => null,
	'ActualDate' => strftime('%Y-%m-%d'),
)));
*/

// DONE
// Remove Waste from Harvest
/*
$rbe->harvestWasteRemove(array(array(
	'Id' => 3702,
	'UnitOfWeight' => 'Ounces',
	'WasteWeight' => 3.21,
	'ActualDate' => strftime('%Y-%m-%d'),
)));
*/

// DONE
// Finish Harvest
/*
$rbe->harvestFinish(array(array(
	'Id' => 3402,
	'ActualDate' => strftime('%Y-%m-%d'),
)));
*/

// Unfinish Harvest =Done
/*
$rbe->harvestFinish(array(array(
	'Id' => 6,
	'ActualDate' => strftime('%Y-%m-%d'),
)));
sleep(3);
$rbe->harvestFinishUndo(array(array(
	'Id' => 6
)));
*/


// DONE
// Manicure between 5g and 50g of shake/trim from a vegetative plant and assign the manicure harvest a name
/*
$rbe->plant_collect()->manicure(array(array(
	'Plant' => 'ABCDEF012345670000014867',
	'UnitOfWeight' => 'Grams',
	'Weight' => 43.21,
	'DryingRoom' => 'Room: 41af4406',
	'HarvestName' => sprintf('Manicure: %s', $code),
	'ActualDate' => strftime('%Y-%m-%d'),
)));
*/

// DONE
// Harvest a total between of 5oz and 50oz of buds from at least two flowering plants of the same strain
/*
$rbe->plant_collect()->harvest(array(
array(
	'Plant' => 'ABCDEF012345670000014881',
	'UnitOfWeight' => 'Ounces',
	'Weight' => 43.21,
	'DryingRoom' => 'Room: 41af4406',
	'HarvestName' => sprintf('Harvest Buds: %s', $code),
	'ActualDate' => strftime('%Y-%m-%d'),
),
array(
	'Plant' => 'ABCDEF012345670000014882',
	'UnitOfWeight' => 'Ounces',
	'Weight' => 23.45,
	'DryingRoom' => 'Room: 41af4406',
	'HarvestName' => sprintf('Harvest Buds: %s', $code),
	'ActualDate' => strftime('%Y-%m-%d'),
)
));
*/



// Plant Manicure
// $res = $rbe->plant_collect()->manicure([[
// 	'Plant' => '1A4FFFC303D7E32000001843',
// 	'Weight' => 123.45,
// 	'UnitOfWeight' => 'Grams',
// 	'DryingRoom' => 'Eval 78b705e8',
// 	'HarvestName' => null,
// 	'ActualDate' => date('Y-m-d'),
// ]]);
// var_dump($res);

// Plant Harvest
// $res = $rbe->plant_collect()->harvest([[
// 	'Plant' => '1A4FFFC303D7E32000001847',
// 	'Weight' => 123.45,
// 	'UnitOfWeight' => 'Grams',
// 	'DryingRoom' => 'Eval 78b705e8',
// 	// 'HarvestName' => '2020-01-11-Eval 78b705e8-H',
// 	'ActualDate' => date('Y-m-d'),
// ]]);
// var_dump($res);


// Harvest Create Package
// $res = $rbe->plant_collect()->harvestPackageCreate([[
// 	'Tag' => 'ABCDEF012345670000016525',
// 	'Room' => 'Eval 78b705e8',
// 	'Item' => 'Buds',
// 	'UnitOfWeight' => 'Grams',
// 	'Note' => 'Test 78b705e8',
// 	'IsProductionBatch' => false,
// 	'ActualDate' => date('Y-m-d'),
// 	'Ingredients' => [
// 		[
// 			'HarvestId' => '45001',
// 			'Weight' => 62.00,
// 			'UnitOfWeight' => 'Grams'
// 		]
// 	]
// ]]);
// print_r($res);


// Harvest Remove Waste
// $res = $rbe->plant_collect()->harvestWasteRemove([[
// 	'Id' => '45001',
// 	'WasteType' => 'Plant Material',
// 	'WasteWeight' => 31.45,
// 	'UnitOfWeight' => 'Grams',
// 	'ActualDate' => date('Y-m-d'),
// ]]);
// print_r($res);


// Harvest Finish
// $res = $rbe->plant_collect()->harvestFinish([[
// 	'Id' => '45001',
// 	'ActualDate' => date('Y-m-d'),
// ]]);
// print_r($res);


// Harvest Finish Undo
// $res = $rbe->plant_collect()->harvestFinishUndo([[
// 	'Id' => '45001',
// ]]);
// print_r($res);
