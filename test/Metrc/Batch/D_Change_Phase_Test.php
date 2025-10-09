<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\E_Metrc\C_Batch;

class D_Change_Phase_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_update()
	{
		$res = $this->cre->batch()->change([
			'Name' => 'Plant Batch dafeca19 - Alpha',
			'StartingTag' => '1A4FF0200000387000000688',
			'GrowthPhase' => 'Vegetative',
			'NewLocation' => 'TEST0 a6f63bd6',
			'GrowthDate' => date('Y-m-d'),
			'Count' => 2,
		]);

		$this->assertValidResponse($res);
	}
}

// Change the growth phase of 2 of the plants to flowering using: POST /plantbatches/v1/changegrowthphase
// $res = $rbe->batch()->create([
// 	'Name' => sprintf('Plant Batch %s Bravo', $code),
// 	'Type' => 'Seed',
// 	'Count' => 3,
// 	'Strain' => sprintf('Strain: %s', $code),
// 	'ActualDate' => date('Y-m-d'),
// ]);
// $res = $rbe->batch()->change([
// 	'Name' => sprintf('Plant Batch %s Bravo', $code),
// 	'Count' => 2,
// 	'GrowthDate' => date('Y-m-d'),
// 	'GrowthPhase' => 'Flowering',
// 	'NewRoom' => sprintf('Zone %s', $code),
// 	'StartingTag' => '1A4FF0200000001000003327',
// ]);
// var_dump($res); exit;

// Done
//Change the growth phase of 10 or more clones to vegetative
//$rbe->plantbatchChangePhase(array(array(
//	'Id' => 6702,
//	'Count' => 20,
//	'StartingTag' => 'ABCDEF012345670000014866',
//	'NewRoom' => '41af4406 - Room',
//	'GrowthPhase' => 'Vegetative',
//	'GrowthDate' => strftime('%Y-%m-%d'),
//)));

// Created #4730
//$rbe->plantbatchChangePhase(array(array(
//	'Id' => 4370,  // PLant Batch Package - Fails
//	'Count' => 11,
//	'StartingTag' => 'ABCDEF012345670000013300',
//	'NewRoom' => 'Room: 41af4406',
//	'GrowthPhase' => 'Vegetative',
//	'GrowthDate' => '2016-07-30',
//)));


// This created 11 new Plant objects; Decremented the Source
//$rbe->plantbatchChangePhase(array(array(
//	'Id' => 4801, // Plant Batch of Clones
//	'Count' => 11,
//	'StartingTag' => 'ABCDEF012345670000013272',
//	'NewRoom' => 'Room: 41af4406',
//	'GrowthPhase' => 'Vegetative',
//	'GrowthDate' => '2016-07-30',
//)));


// Change the growth phase of 5 or more immature plants to vegetative
// Didn't get an error but it did not create new objects or decrement a counter
//$rbe->plantbatchChangePhase(array(array(
//	'Id' => 3001,
//	'Count' => 5,
//	'StartingTag' => 'ABCDEF012345670000013272',
//	'NewRoom' => 'Room: 41af4406',
//	'GrowthPhase' => 'Vegetative',
//	'GrowthDate' => '2016-07-27',
//)));

// Created #4730
//$rbe->plantbatchChangePhase(array(array(
//	'Id' => 4370,  // PLant Batch Package - Fails
//	'Count' => 11,
//	'StartingTag' => 'ABCDEF012345670000013300',
//	'NewRoom' => 'Room: 41af4406',
//	'GrowthPhase' => 'Vegetative',
//	'GrowthDate' => '2016-07-30',
//)));


// This created 11 new Plant objects; Decremented the Source
//$rbe->plantbatchChangePhase(array(array(
//	'Id' => 4801, // Plant Batch of Clones
//	'Count' => 11,
//	'StartingTag' => 'ABCDEF012345670000013272',
//	'NewRoom' => 'Room: 41af4406',
//	'GrowthPhase' => 'Vegetative',
//	'GrowthDate' => '2016-07-30',
//)));


// Change the growth phase of 5 or more immature plants to vegetative
// Didn't get an error but it did not create new objects or decrement a counter
//$rbe->plantbatchChangePhase(array(array(
//	'Id' => 3001,
//	'Count' => 5,
//	'StartingTag' => 'ABCDEF012345670000013272',
//	'NewRoom' => 'Room: 41af4406',
//	'GrowthPhase' => 'Vegetative',
//	'GrowthDate' => '2016-07-27',
//)));


// Plant Batch Modify Two to Flowering
// $res = $rbe->plantbatchChangePhase([[
// 	'Name' => 'Plant Batch 78b705e8 - Alpha',
// 	'Count' => 2,
// 	'GrowthDate' => date('Y-m-d'),
// 	'GrowthPhase' => 'Flowering',
// 	'NewRoom' => 'Eval 78b705e8',
// 	'StartingTag' => 'ABCDEF012345670000016303',
// ]]);
// var_dump($res);



// Created #4730
//$rbe->plantbatchChangePhase(array(array(
//	'Id' => 4370,  // PLant Batch Package - Fails
//	'Count' => 11,
//	'StartingTag' => 'ABCDEF012345670000013300',
//	'NewRoom' => 'Room: 41af4406',
//	'GrowthPhase' => 'Vegetative',
//	'GrowthDate' => '2016-07-30',
//)));



// This created 11 new Plant objects; Decremented the Source
//$rbe->plantbatchChangePhase(array(array(
//	'Id' => 4801, // Plant Batch of Clones
//	'Count' => 11,
//	'StartingTag' => 'ABCDEF012345670000013272',
//	'NewRoom' => 'Room: 41af4406',
//	'GrowthPhase' => 'Vegetative',
//	'GrowthDate' => '2016-07-30',
//)));


// Change the growth phase of 5 or more immature plants to vegetative
// Didn't get an error but it did not create new objects or decrement a counter
//$rbe->plantbatchChangePhase(array(array(
//	'Id' => 3001,
//	'Count' => 5,
//	'StartingTag' => 'ABCDEF012345670000013272',
//	'NewRoom' => 'Room: 41af4406',
//	'GrowthPhase' => 'Vegetative',
//	'GrowthDate' => '2016-07-27',
//)));





// Plant Batch Modify Two to Flowering
// $res = $rbe->plantbatchChangePhase([[
// 	'Name' => 'Plant Batch 78b705e8 - Alpha',
// 	'Count' => 2,
// 	'GrowthDate' => date('Y-m-d'),
// 	'GrowthPhase' => 'Flowering',
// 	'NewRoom' => 'Eval 78b705e8',
// 	'StartingTag' => 'ABCDEF012345670000016303',
// ]]);
// var_dump($res);
