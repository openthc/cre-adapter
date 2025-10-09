<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\E_Metrc\J_B2C;

class B_Contact_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_contact_type_list()
	{
		$res = $this->cre->get('/sales/v1/customertypes');
		$this->assertValidResponse($res);
		$this->assertNotEmpty($res);
	}

	function test_contact_create()
	{
		$res = $this->cre->contact()->create([
			'LicenseNumber' => '78b705e8-Alpha',
			'LicenseEffectiveStartDate' => date('Y-m-d'),
			'LicenseEffectiveEndDate' => date('Y-m-d', time() + 86400 * 365),
			'RecommendedPlants' => 421,
			'RecommendedSmokableQuantity' => 4.21,
			'ActualDate' => date('Y-m-d'),
		]);
		$this->assertValidResponse($res, 201);

		// $this->cre->post()
		// POST /patients/v1/add
		// $rbe->setLicense('402-X0001');
		// $res = $rbe->contact()->create([
		// 	'LicenseNumber' => '78b705e8-Alpha',
		// 	'LicenseEffectiveStartDate' => date('Y-m-d'),
		// 	'LicenseEffectiveEndDate' => date('Y-m-d', time() + 86400 * 365),
		// 	'RecommendedPlants' => 421,
		// 	'RecommendedSmokableQuantity' => 4.21,
		// 	'ActualDate' => date('Y-m-d'),
		// ]);

	}

	function test_contact_update()
	{
		// POST /patients/v1/update
		$res = $this->cre->contact()->update([
			'LicenseNumber' => '78b705e8-Alpha',
			'NewLicenseNumber' => '78b705e8-Bravo',
			'LicenseEffectiveStartDate' => date('Y-m-d'),
			'LicenseEffectiveEndDate' => date('Y-m-d', time() + 86400 * 365),
			'RecommendedPlants' => 42,
			'RecommendedSmokableQuantity' => 42,
			'ActualDate' => date('Y-m-d'),
		]);

		$this->assertValidResponse($res);

		// POST /patients/v1/update
		// $res = $rbe->contact()->update([
		// 	'LicenseNumber' => '78b705e8-Alpha',
		// 	'NewLicenseNumber' => '78b705e8-Bravo',
		// 	'LicenseEffectiveStartDate' => date('Y-m-d'),
		// 	'LicenseEffectiveEndDate' => date('Y-m-d', time() + 86400 * 365),
		// 	'RecommendedPlants' => 42,
		// 	'RecommendedSmokableQuantity' => 42,
		// 	'ActualDate' => date('Y-m-d'),
		// ]);

	}

	function test_contact_delete()
	{
		// DELETE /patients/v1/{id}
		$res = $this->cre->contact()->delete('5402');
		$this->assertValidResponse($res);

		// $res = $rbe->contact()->create([
		// 	'LicenseNumber' => '78b705e8-Charlie',
		// 	'LicenseEffectiveStartDate' => date('Y-m-d'),
		// 	'LicenseEffectiveEndDate' => date('Y-m-d', time() + 86400 * 365),
		// 	'RecommendedPlants' => 100,
		// 	'RecommendedSmokableQuantity' => 1,
		// 	'ActualDate' => date('Y-m-d'),
		// ]);
		// $res = $rbe->contact()->delete('5402');

		// DELETE /patients/v1/{id}
		// $res = $rbe->contact()->create([
		// 	'LicenseNumber' => '78b705e8-Charlie',
		// 	'LicenseEffectiveStartDate' => date('Y-m-d'),
		// 	'LicenseEffectiveEndDate' => date('Y-m-d', time() + 86400 * 365),
		// 	'RecommendedPlants' => 100,
		// 	'RecommendedSmokableQuantity' => 1,
		// 	'ActualDate' => date('Y-m-d'),
		// ]);
		// $res = $rbe->contact()->delete('5402');

	}
}
