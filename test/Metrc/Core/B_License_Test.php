<?php
/**
 * SPDX-License-Identifier: GPL-3.0-only
 */

namespace OpenTHC\CRE\Test\Metrc\Core;

class B_License_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	/**
	 *
	 */
	function license_search($cre)
	{
		$res = $cre->license()->search();
		$this->assertValidResponse($res);
	}

	/**
	 * @test
	 * @depends A_Auth_Test::test_auth_ca
	 */
	function license_search_ca()
	{
		$cfg = \OpenTHC\CRE::getConfig('usa-ca-test');
		$cfg['license-key'] = $_ENV['OPENTHC_TEST_METRC_CA_LICENSE_SK'];
		$cre = \OpenTHC\CRE::getEngine($cfg);

		$this->license_search($cre);

		// $res = $this->cre->license()->search();
		// $this->assertValidResponse($res);
	}

	/**
	 *
	 */
	function test_license_select()
	{
		$res = $this->cre->license()->search();
		$this->assertValidResponse($res);
		$this->assertIsArray($res['data']);
		// $this->assertCount()

		// Just Use the First One
		$L = $res['data'][0];
		$this->cre->setLicense([
			'id' => $L['License']['Number'],
			'code' => $L['License']['Number'],
			'guid' => $L['License']['Number'],
			'name' => $L['DisplayName']
		]);

		$res = $this->cre->section()->search();
		$this->assertValidResponse($res);
	}

}
