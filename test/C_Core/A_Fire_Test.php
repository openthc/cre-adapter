<?php
/**
 * Test Class Loading
 *
 * SPDX-License-Identifier: MIT
 *
 * Remove this maybe
 */

namespace OpenTHC\CRE\Test\C_Core;

class A_Fire_Test extends \OpenTHC\CRE\Test\Base_Case
{

	function test_base_class()
	{
		$c = new \OpenTHC\CRE\Base([
			'server' => 'https://bunk.openthc.dev/',
		]);

		$l0 = $c->setLicense('L1');
		$this->assertIsArray($l0);
		// $p1 = $c->ping();
		// var_dump($p1);

		// $c = new \OpenTHC\CRE\BioTrack([
		// 	'server' => 'https://bunk.openthc.dev/biotrack',
		// 	'company' => '123456789',
		// 	'username' => 'test@openthc.dev',
		// 	'password' => 'password',
		// ]);
		// $l0 = $c->setLicense('L1');
		// $this->assertIsArray($l0);
		// $p1 = $c->ping();
		// var_dump($p1);

		$c = new \OpenTHC\CRE\LeafData([
			'server' => 'https://bunk.openthc.dev/leafdata',
			'license' => 'L0',
			'license-key' => 'L0-KEY',
		]);
		$l0 = $c->setLicense('L1');
		$this->assertIsArray($l0);
		$p1 = $c->ping();
		$this->assertNotEmpty($p1);
		// var_dump($p1);

		$c = new \OpenTHC\CRE\Metrc([
			'server' => 'https://bunk.openthc.dev/metrc',
			'service-key' => '-',
			'license-key' => '-',
		]);
		// $l0 = $c->setLicense('L1');
		$this->assertIsArray($l0);
		$p1 = $c->ping();
		$this->assertNotEmpty($p1);
		// var_dump($p1);

		$c = new \OpenTHC\CRE\OpenTHC([
			'server' => 'https://bunk.openthc.dev/openthc',
		]);
		$l0 = $c->setLicense('L1');
		$this->assertIsArray($l0);
		$p1 = $c->ping();
		$this->assertNotEmpty($p1);
		// var_dump($p1);

	}

}
