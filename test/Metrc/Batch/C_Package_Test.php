<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Metrc\Batch;

class C_Package_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_create()
	{
		$res = $this->cre->batch()->package([
			'Id' => '118502',
			'Location' => 'TEST0 a6f63bd6',
			'Room' => 'TEST0 a6f63bd6',
			'PlantBatch' => '',
			'Item' => 'TEST Product abe8b3bb',
			'Count' => 3,
			'Tag' => '1A4FF0300000387000000123',
			'ActualDate' => date('Y-m-d'),
			'Note' => 'Testing Package',
		]);
		var_dump($res);
		$this->assertValidResponse($res);
	}
}

// Plant Batches
//$rbe->plantbatchPackageCreate(array(array(
//	'Id' => 6702, // Plant Batch ID of Clones
//	'Item' => sprintf('Item: %s-Plants', $code),
//	'Tag' => 'ABCDEF012345670000015145', // Package ID
//	'Count' => 20,
//	,
//)));
//$rbe->plantbatchPackageCreate(array(array(
//	'Id' => 6702, // Plant Batch ID of Clones
//	'Item' => sprintf('Item: %s-Seeds', $code),
//	'Tag' => 'ABCDEF012345670000015146', // Package ID
//	'Count' => 20,
//	'ActualDate' => strftime('%Y-%m-%d'),
//)));
// Create Plant Batch of Immature Plants
//$rbe->plantbatchPackageCreate(array(array(
//	'Id' => 4801, // Plant Batch ID of Clones
//	'Item' => 'Immature Plants: 41af4406',
//	'Tag' => 'ABCDEF012345670000013550', // Package ID
//	'Count' => 35,
//	'ActualDate' => '2016-07-30',
//)));
//{"row":0,"message":"Plant Batch 15 does not exist in the current Facility."},
//{"row":0,"message":"The Product \\"Immature Plants\\" is invalid. Valid values are: Buds - AK-47, Buds - Metrc Bliss, Buds - TN Orange Dream, Buds Item, Buds: 41af4406, Clones - AK-47, Clones - TN Orange Dream, Extracts Each: 41af4406, Extracts Each: 41af4406 v2, Extracts Ounces: 41af4406, Immature Plants: 41af4406, Seeds: 41af4406, Shake/Trim, To Modify: 41af4406."},
//{"row":0,"message":"Tag ABCDEF012345670000013300 is not valid."}]
// Create Plant Batch of Immature Plants
//$rbe->plantbatchPackageCreate(array(array(
//	'Id' => 4801, // Plant Batch ID of Clones
//	'Item' => 'Immature Plants: 41af4406',
//	'Tag' => 'ABCDEF012345670000013550', // Package ID
//	'Count' => 35,
//	'ActualDate' => '2016-07-30',
//)));
//{"row":0,"message":"Plant Batch 15 does not exist in the current Facility."},
//{"row":0,"message":"The Product \\"Immature Plants\\" is invalid. Valid values are: Buds - AK-47, Buds - Metrc Bliss, Buds - TN Orange Dream, Buds Item, Buds: 41af4406, Clones - AK-47, Clones - TN Orange Dream, Extracts Each: 41af4406, Extracts Each: 41af4406 v2, Extracts Ounces: 41af4406, Immature Plants: 41af4406, Seeds: 41af4406, Shake/Trim, To Modify: 41af4406."},
//{"row":0,"message":"Tag ABCDEF012345670000013300 is not valid."}]
// Create Plant Batch of Immature Plants
//$rbe->plantbatchPackageCreate(array(array(
//	'Id' => 4801, // Plant Batch ID of Clones
//	'Item' => 'Immature Plants: 41af4406',
//	'Tag' => 'ABCDEF012345670000013550', // Package ID
//	'Count' => 35,
//	'ActualDate' => '2016-07-30',
//)));
//{"row":0,"message":"Plant Batch 15 does not exist in the current Facility."},
//{"row":0,"message":"The Product \\"Immature Plants\\" is invalid. Valid values are: Buds - AK-47, Buds - Metrc Bliss, Buds - TN Orange Dream, Buds Item, Buds: 41af4406, Clones - AK-47, Clones - TN Orange Dream, Extracts Each: 41af4406, Extracts Each: 41af4406 v2, Extracts Ounces: 41af4406, Immature Plants: 41af4406, Seeds: 41af4406, Shake/Trim, To Modify: 41af4406."},
//{"row":0,"message":"Tag ABCDEF012345670000013300 is not valid."}]
