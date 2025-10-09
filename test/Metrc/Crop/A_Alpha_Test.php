<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\E_Metrc\D_Crop;

class A_Alpha_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_crop_move()
	{
		$this->assertTrue(false, 'Not Implemented');
		// Plant Move Room
		// $res = $rbe->plant()->move([[
		// 	'Id' => '305001',
		// 	'Label' => 'ABCDEF012345670000016303',
		// 	'Room' => 'Eval 78b705e8 - Rename - Bravo',
		// 	'ActualDate' => date('Y-m-d'),
		// ]]);
		// var_dump($res);

		### CA

		// Move - DONE
		//$rbe->plantMove(array(
		//array(
		//	'Id' => '13032',
		//	//'Label' => '',
		//	'Room' => 'Room: 41af4406',
		//	'ActualDate' => '2016-07-02'
		//),
		//array(
		//	'Id' => '13033',
		//	//'Label' => '',
		//	'Room' => 'Room: 41af4406',
		//	'ActualDate' => '2016-07-02'
		//),
		//));

	}

	function test_crop_destroy()
	{
		$this->assertTrue(false, 'Not Implemented');
		// Plant Destroy
		// $res = $rbe->plant()->destroy([[
		// 	'Id' => '302801',
		// 	'Label' => '1A4FFFC303D7E32000001838',
		// 	'ReasonNote' => 'Destroy a plant using: POST /plants/v1/destroyplants',
		// 	'ActualDate' => date('Y-m-d'),
		// ]]);
		// var_dump($res);

		// Destroy =DONE
		//$rbe->plantDestroy(array(
		//array(
		//	'Id' => '1359',
		//	'ReasonNote' => sprintf('Destroy for API Eval: %s', $code),
		//	'ActualDate' => '2016-07-02',
		//)
		//));


		// Destroy =DONE
		//$rbe->plantDestroy(array(
		//array(
		//	'Id' => '1359',
		//	'ReasonNote' => sprintf('Destroy for API Eval: %s', $code),
		//	'ActualDate' => '2016-07-02',
		//)
		//));

	}

}


// Change Phase - Vegetative to Flowering & Move = DONE
//$rbe->plantPhaseChange(array(
//array(
//	'Id' => '1348',
//	'Room' => 'Room: 41af4406',
//	'GrowthDate' => '2016-07-02',
//	'GrowthPhase' => 'Flowering',
//),
//array(
//	'Id' => '1349',
//	'Room' => 'Room: 41af4406',
//	'GrowthDate' => '2016-07-02',
//	'GrowthPhase' => 'Flowering',
//),
//array(
//	'Id' => '1350',
//	'Room' => 'Room: 41af4406',
//	'GrowthDate' => '2016-07-02',
//	'GrowthPhase' => 'Flowering',
//),
//array(
//	'Id' => '1356',
//	'Room' => 'Room: 41af4406',
//	'GrowthDate' => '2016-07-02',
//	'GrowthPhase' => 'Flowering',
//),
//array(
//	'Id' => '1357',
//	'Room' => 'Room: 41af4406',
//	'GrowthDate' => '2016-07-02',
//	'GrowthPhase' => 'Flowering',
//)
//));

// Change Tag (via Phase) =DONE
//$rbe->plantPhaseChange(array(
//array(
//	'Id' => '1358',
//	'NewTag' => 'ABCDEF012345670000013270',
//	// Have to re-peat existing data
//	'GrowthDate' => '2015-10-20',
//	'GrowthPhase' => 'Vegetative',
//)
//));

// Change Tag (via Phase) =DONE
//$rbe->plantPhaseChange(array(
//array(
//	'Id' => '1358',
//	'NewTag' => 'ABCDEF012345670000013270',
//	// Have to re-peat existing data
//	'GrowthDate' => '2015-10-20',
//	'GrowthPhase' => 'Vegetative',
//)
//));
