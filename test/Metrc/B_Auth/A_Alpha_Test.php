<?php
/**
 * Test Auth Stuff
 *
 * SPDX-License-Identifier: MIT
 *
 * Notes about the Auth module
 * The "service-sk" cooresponds to a code that is a company object identifier
 * The "license-sk" cooresponds to a code that is a license object identifier
 *
 * Licenses can belong to a company in a 1:M way
 * Companies can have different permissions to act on a license's object
 *
 */

namespace OpenTHC\CRE\Test\Metrc\B_Auth;

class A_Alpha_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	public function test_auth()
	{
		$c = new \OpenTHC\CRE\Adapter\Metrc([
			'server' => 'https://bunk.openthc.org/metrc',
			'service-sk' => '-',
			'license-sk' => '-',
		]);
		$l0 = $c->setLicense('L1');
		$this->assertIsArray($l0);
		$p1 = $c->ping();
		var_dump($p1);
	}

	public function test_open_fail()
	{
		$c = new \OpenTHC\CRE\Adapter\Metrc([
			'server' => 'https://bunk.openthc.org/metrc',
			'service-sk' => 'garbage-data',
			'license-sk' => 'garbage-data',
		]);
		$l0 = $c->setLicense('L1');
		$this->assertIsEmpty($l0);
		$p1 = $c->ping();

		$res = $this->assertValidResponse($p1, 403);
		var_dump($p1);
	}

	function test_open_pass()
	{
		// TEST COMPANY A
		// $res = $this->_post('/auth/open', [
		$c = new \OpenTHC\CRE\Adapter\Metrc([
			'service-sk' => $_ENV['api-service-a'],
			// 'company-key' => $_ENV['api-company-g0'],
			'license-sk' => $_ENV['api-license-g0'],
		]);

		$p1 = $c->ping();
		$this->assertValidResponse($p1, 200);

		$this->assertIsArray($p1);
		$this->assertCount(3, $p1);
		$this->assertMatchesRegularExpression('/\w{26,256}/', $res['data']);
	}

	function test_open_fail_company_license()
	{
		$c = new \OpenTHC\CRE\Adapter\Metrc([
			'server' => 'https://bunk.openthc.org/metrc',
			'service-sk' => $_ENV['api-service-a'],
			'license-sk' => 'garbage-data',
		]);
		$p1 = $c->ping();

		$res = $this->assertValidResponse($p1, 500);
		var_dump($p1);
	}

}
