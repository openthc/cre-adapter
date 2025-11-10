<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Metrc\Laboratory;

class A_Alpha_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_get_state_list()
	{
		$res = $this->cre->get('/labtests/v1/states');
		$this->assertNotEmpty($res);

	}

	function test_get_metric_list()
	{
		$res = $this->cre->get('/labtests/v1/types');
		$this->assertNotEmpty($res);

	}

}

// // POST /labtests/v1/record
// $arg = [
// 	'Label' => '1A4FFFB303DA541000000007',
// 	'ResultDate' => date(\DateTime::RFC3339),
// 	'DocumentFileName' => 'Lab Result 78b705e8-Alpha',
// 	// 'DocumentFileBase64' => base64_encode(),
// 	'Results' => [],
// ];
// foreach ($lab_metric_list as $lm0) {
// 	$arg['Results'][] = [
// 		'LabTestTypeName' => $lm0['Name'],
// 		'Quantity' => (random_int(100, 10000) / 100),
// 		'Passed' => true,
// 		'Notes' => '',
// 	];
// }
// print_r($arg);
// $res = $rbe->labresult()->create($arg);
// print_r($res);


// Laboratory Stuff
// $rbe->setLicense('405R-X0001');
// $url = $rbe->_make_url('/labtests/v1/states');
// $req = $rbe->_curl_init($url);
// // $res = $rbe->_curl_exec($req);
// // print_r($res);
// // exit;

// $url = $rbe->_make_url('/labtests/v1/types');
// $req = $rbe->_curl_init($url);
// $res = $rbe->_curl_exec($req);
// $lab_metric_list = $res['data'];
// // print_r($res);
// // exit;

// // POST /labtests/v1/record
// $arg = [
// 	'Label' => '1A4FFFB303DA541000000007',
// 	'ResultDate' => date(\DateTime::RFC3339),
// 	'DocumentFileName' => 'Lab Result 78b705e8-Alpha',
// 	// 'DocumentFileBase64' => base64_encode(),
// 	'Results' => [],
// ];
// foreach ($lab_metric_list as $lm0) {
// 	$arg['Results'][] = [
// 		'LabTestTypeName' => $lm0['Name'],
// 		'Quantity' => (random_int(100, 10000) / 100),
// 		'Passed' => true,
// 		'Notes' => '',
// 	];
// }
// print_r($arg);

// $res = $rbe->labresult()->create($arg);
// print_r($res);
