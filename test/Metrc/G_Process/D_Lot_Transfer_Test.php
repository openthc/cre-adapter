<?php
/**
 * Test Lot Transfer
 */

namespace Test\Metrc\G_Process;

class D_Lot_Transfer_Test extends \Test\OpenTHC_Metrc_Test
{
	public function testTransferBasic()
	{
		// Create, then Update a B2C Sale
		//  PUT /sales/v1/receipts
		// $rbe->setLicense('402R-X0001');
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
	}

	public function testTransferVoid()
	{
		// Delete/Void a B2C Sale
		// DELETE /sales/v1/receipts/{id}
		// $rbe->setLicense('402R-X0001');
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
	}
}
