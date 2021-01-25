<?php
/**
 * Load Each Engine
 */

namespace Test;

class Engine_Test extends \Test\OpenTHC_Base_TestCase
{
	function test_engine_ping()
	{
		$cfg_base = [
			'company' => '123456789',
			'username' => 'fdsafdsaf',
			'password' => 'fdsafdsafsda',
			'license' => '123123123',
			'license-key' => 'fdsafdsafsda',
			'service-key' => 'fdsajklrewcsd',
		];

		$cre_list = \OpenTHC\CRE\Adapter\Base::getEngineList();

		foreach ($cre_list as $cfg) {

			$cfg = array_merge($cfg_base, $cfg);

			$n = $cfg['class'];
			// $this->assertNotEmpty($n);

			if (!empty($n)) {
				echo "Class: $n\n";
				$cre = new $n($cfg);
				// $this->assertTrue( implements );
				$this->assertTrue(method_exists($cfg, 'search'));
				$this->assertTrue(method_exists($cfg, 'single'));
				'update';
				'delete';
				'ping';

				$res = $cre->ping();
				// Assewrt Good
			}

		}

	}

}
