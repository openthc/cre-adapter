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

		$cre_list = \OpenTHC\CRE::getEngineList();

		foreach ($cre_list as $cfg) {

			$cfg = array_merge($cfg_base, $cfg);

			$n = $cfg['class'];
			// $this->assertNotEmpty($n);

			if (!empty($n)) {
				$cre = new $n($cfg);
				// $this->assertTrue( implements );
				
				foreach (['search', 'single', 'update', 'delete', 'ping'] as $method) {
					$this->assertTrue(method_exists($cfg, $method));
				}

				$res = $cre->ping();
				// Assert Good
			}

		}

	}

}
