<?php
/**
 * SPDX-License-Identifier: GPL-3.0-only
 */

namespace OpenTHC\CRE\Test\Metrc\Core;

class A_Auth_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_auth()
	{
		foreach ($this->test_metrc_env_list as $env) {

			$cre =

			$res = $this->cre->uomList();
			$this->assertNotEmpty($res);
			$this->assertValidResponse($res);
			$this->assertIsArray($res);

		}
	}

	function test_auth_ca()
	{
		$cfg = \OpenTHC\CRE::getConfig('usa-ca-test');
		$cfg['license-key'] = $_ENV['OPENTHC_TEST_METRC_CA_LICENSE_SK'];
		$cre = \OpenTHC\CRE::getEngine($cfg);

		// $chk =
		// if (empty($chk)) {
		// 	$this->markTestSkipped();
		// }

		$res = $cre->uom()->get();
		$this->assertValidResponse($res);

	}

	function test_auth_ok()
	{
		$cfg = \OpenTHC\CRE::getConfig('usa-ok-test');
		$cfg['license-key'] = $_ENV['OPENTHC_TEST_METRC_OK_LICENSE_SK'];
		$cre = \OpenTHC\CRE::getEngine($cfg);

		// $chk =
		// if (empty($chk)) {
		// 	$this->markTestSkipped();
		// }

		$res = $cre->uom()->get();
		$this->assertValidResponse($res);

	}

}
