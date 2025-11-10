<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Metrc\Batch;

class E_Destroy_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_destroy()
	{
		$res = $this->cre->batch()->search();
		$this->assertValidResponse($res);

		// Plant Batch Destroy One
		$res = $this->cre->batch()->destroy([
			'PlantBatch' => 'Plant Batch dafeca19 - Alpha',
			'Count' => 1,
			'ReasonNote' => 'Destroy 1 of the plants using: POST /plantbatches/v1/destroy',
			'ActualDate' => date('Y-m-d')
		]);

	}
}

// var_dump($res);
//$rbe->plantbatchDestroy(array(array(
//	'Id' => '4301',
//	'Count' => '11',
//	'ReasonNote' => 'Destroy for API Testing',
//	'ActualDate' => '2016-07-08',
//)));
// DONE
//Destroy 10 or more immature plants from an existing plant batch
//$rbe->plantbatchDestroy(array(array(
//	'Id' => 6701,
//	'Count' => 10,
//	'ReasonNote' => sprintf('Reason %s', $code),
//	'ActualDate' => strftime('%Y-%m-%d'),
//)));
//$rbe->plantbatchDestroy(array(array(
//	'Id' => '4301',
//	'Count' => '11',
//	'ReasonNote' => 'Destroy for API Testing',
//	'ActualDate' => '2016-07-08',
//)));
// Plant Batch Destroy One
// $res = $rbe->plantbatchDestroy([[
// 	'PlantBatch' => 'Plant Batch 78b705e8 - Alpha',
// 	'Count' => 1,
// 	'ReasonNote' => 'Destroy 1 of the plants using: POST /plantbatches/v1/destroy',
// 	'ActualDate' => date('Y-m-d')
// ]]);
// var_dump($res);

//$rbe->plantbatchDestroy(array(array(
//	'Id' => '4301',
//	'Count' => '11',
//	'ReasonNote' => 'Destroy for API Testing',
//	'ActualDate' => '2016-07-08',
//)));



// Plant Batch Destroy One
// $res = $rbe->batch()->destroy([
// 	'PlantBatch' => sprintf('Plant Batch %s Bravo', $code),
// 	'Count' => 1,
// 	'ReasonNote' => 'Destroy 1 of the plants using: POST /plantbatches/v1/destroy',
// 	'ActualDate' => date('Y-m-d')
// ]);
// var_dump($res); exit;
