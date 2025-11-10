<?php
/**
 * Test Class Loading
 *
 * SPDX-License-Identifier: MIT
 *
 * Remove this maybe
 */

namespace OpenTHC\CRE\Test\Core;

class Fire_Test extends \OpenTHC\CRE\Test\Base_Case
{
	/**
	 *
	 */
	function test_base_class()
	{
		$cre_config = [
			'server' => 'https://bunk.openthc.dev/',
			'contact' => 'TEST',
			'company' => 'TEST',
			'license' => 'TEST',
			'license-sk' => 'L0-KEY',
			'username' => 'TEST',
			'password' => 'TEST',
			'service-id' => 'TEST',
			'service-sk' => 'TEST',
		];

		$c = new \OpenTHC\CRE\Base($cre_config);

		$l0 = $c->setLicense('L1');
		$this->assertIsArray($l0);
		// $p1 = $c->ping();
		// var_dump($p1);

		$c = new \OpenTHC\CRE\BioTrack($cre_config);
		// $l0 = $c->setLicense('L1');
		// $this->assertIsArray($l0);
		// $p1 = $c->ping();
		// var_dump($p1);

		$c = new \OpenTHC\CRE\Metrc2023($cre_config);
		// $l0 = $c->setLicense('L1');
		// $this->assertIsArray($l0);
		// $p1 = $c->ping();
		// $this->assertNotEmpty($p1);
		// var_dump($p1);

		$c = new \OpenTHC\CRE\OpenTHC($cre_config);
		// $l0 = $c->setLicense('L1');
		// $this->assertIsArray($l0);
		// $p1 = $c->ping();
		// $this->assertNotEmpty($p1);
		// var_dump($p1);

	}

}
