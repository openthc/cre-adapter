<?php
/**
 * Test Class Loading
 */

namespace Test\Core;

class Fire_Test extends \Test\OpenTHC_Base_TestCase
{

	function test_class()
	{
		$c = new \OpenTHC\CRE\Adapter\Base([
			'server' => 'https://bunk.openthc.org/',
		]);

		$l0 = $c->setLicense('L1');
		$this->assertIsArray($l0);
		// $p1 = $c->ping();
		// var_dump($p1);

		// $c = new \OpenTHC\CRE\Adapter\BioTrack([
		// 	'server' => 'https://bunk.openthc.dev/biotrack',
		// 	'company' => '123456789',
		// 	'username' => 'test@openthc.dev',
		// 	'password' => 'password',
		// ]);
		// $l0 = $c->setLicense('L1');
		// $this->assertIsArray($l0);
		// $p1 = $c->ping();
		// var_dump($p1);

		$c = new \OpenTHC\CRE\Adapter\LeafData([
			'server' => 'https://bunk.openthc.org/leafdata',
			'license' => 'L0',
			'license-key' => 'L0-KEY',
		]);
		$l0 = $c->setLicense('L1');
		$this->assertIsArray($l0);
		$p1 = $c->ping();
		var_dump($p1);

		$c = new \OpenTHC\CRE\Adapter\Metrc([
			'server' => 'https://bunk.openthc.org/metrc',
			'service-key' => '-',
			'license-key' => '-',
		]);
		$l0 = $c->setLicense('L1');
		$this->assertIsArray($l0);
		$p1 = $c->ping();
		var_dump($p1);

		$c = new \OpenTHC\CRE\Adapter\OpenTHC([
			'server' => 'https://bunk.openthc.org/openthc',
		]);
		$l0 = $c->setLicense('L1');
		$this->assertIsArray($l0);
		$p1 = $c->ping();
		var_dump($p1);

	}

}
