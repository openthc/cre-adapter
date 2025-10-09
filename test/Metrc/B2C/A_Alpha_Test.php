<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\E_Metrc\J_B2C;

class A_Alpha_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_b2c()
	{
		$this->assertTrue(false, 'Not Implemented');
	}
}
// Retail Consumer Types
// $url = $rbe->_make_url('/sales/v1/customertypes');
// $req = $rbe->_curl_init($url);
// $res = $rbe->_curl_exec($req);
// print_r($res);
// exit;

// Create a B2C Retail Sale
// $rbe->setLicense();
// $res = $rbe->b2c()->create([
// 	'SalesDateTime' => date(\DateTime::RFC3339),
// 	'SalesCustomerType' => 'Consumer', // Consumer | Patient | Caregiver | ExternalPatient
// 	// 'PatientLicenseNumber' => null,
// 	// 'CaregiverLicenseNumber' => null,
// 	// 'IdentificationMethod' => null,
// 	'Transactions' => [
// 		[
// 			'PackageLabel' => 'ABCDEF012345670000015450',
// 			'Quantity' => 77.00,
// 			'UnitOfMeasure' => 'Ounces',
// 			'TotalAmount' => 77.77
// 		],
// 		[
// 			'PackageLabel' => '1A4FFFB303D5721000000003',
// 			'Quantity' => 188 / 2,
// 			'UnitOfMeasure' => 'Ounces',
// 			'TotalAmount' => 188.22
// 		]
// 	]
// ]);
// print_r($res);
// exit;

// Create, then Update a B2C Sale
//  PUT /sales/v1/receipts
// $rbe->setLicense();
// $arg = [
// 	'SalesDateTime' => date(\DateTime::RFC3339),
// 	'SalesCustomerType' => 'Consumer', // Consumer | Patient | Caregiver | ExternalPatient
// 	'Transactions' => [
// 		[
// 			'PackageLabel' => '1A4FFFB303D5721000000019',
// 			'Quantity' => 3.0,
// 			'UnitOfMeasure' => 'Ounces',
// 			'TotalAmount' => 22.00
// 		],
// 		[
// 			'PackageLabel' => '1A4FFFB303D5721000000099',
// 			'Quantity' => 4.0,
// 			'UnitOfMeasure' => 'Ounces',
// 			'TotalAmount' => 55.00
// 		]
// 	]
// ];
// $res = $rbe->b2c()->create($arg);
// Update
// $arg['Id'] = 6736;
// $res = $rbe->b2c()->update($arg);
// print_r($res);
// exit;


// Delete/Void a B2C Sale
// DELETE /sales/v1/receipts/{id}
// $rbe->setLicense();
// $arg = [
// 	'SalesDateTime' => date(\DateTime::RFC3339),
// 	'SalesCustomerType' => 'Consumer', // Consumer | Patient | Caregiver | ExternalPatient
// 	'Transactions' => [
// 		[
// 			'PackageLabel' => '1A4FFFB303D5721000000312',
// 			'Quantity' => 2,
// 			'UnitOfMeasure' => 'Each',
// 			'TotalAmount' => 2.00
// 		],
// 		[
// 			'PackageLabel' => '1A4FFFB303D5721000000332',
// 			'Quantity' => 2,
// 			'UnitOfMeasure' => 'Each',
// 			'TotalAmount' => 2
// 		]
// 	]
// ];
// // $res = $rbe->b2c()->create($arg);
// $res = $rbe->b2c()->delete('6732');
// print_r($res);
// exit;
