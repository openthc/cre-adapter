<?php
/**
 * Test Authentication
 */

namespace Test\E_LeafData\A_Auth;

class A_Alpha_Test extends \Test\OpenTHC_LeafData_Test
{
	public function test_auth()
	{
		/**
		 * Log in with bad credentials given the assumption that mechanism we're using to log in is correct
		 */
		// Case 1: Bad license, bad secret
		$cre = $this->_api([
			'license' => 'invalid-license',
			'license-secret' => 'invalid-password',
		]);
		$res = $cre->license()->search();
		$res = $this->assertValidResponse($res, 401);


		// Case 2: Good license, bad secret
		$cre = $this->_api([
			'license' => $_ENV['leafdata-g0-public'],
			'license-secret' => 'invalid-password',
		]);
		$res = $cre->get('mmes');
		$res = $this->assertValidResponse($res, 401);

		// Case 3: Good
		$cre = $this->_api([
			'license' => $_ENV['leafdata-g0-public'],
			'license-secret' => $_ENV['leafdata-g0-secret'],
		]);

		$res = $cre->get('mmes');
		$res = $this->assertValidResponse($res);

	}

	public function test_license_list()
	{
		$cre = $this->_api([
			'license' => $_ENV['leafdata-g0-public'],
			'license-secret' => $_ENV['leafdata-g0-secret'],
		]);

		$res = $cre->get('mmes');
		$this->assertIsArray($res);
		$this->assertCount(3050, $res);

		// Spin Each for Validity?

	}

}
