<?php
/**
 * Load Each Engine
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\C_Core;

class G_Engine_Test extends \OpenTHC\CRE\Test\Base_Case
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

			$class = $cfg['class'];
			$this->assertNotEmpty($class);

			$cre = new $class($cfg);
			$this->assertTrue(
				$cre instanceof \OpenTHC\CRE\Base
			);

			foreach (['search', 'single', 'update', 'delete', 'ping'] as $method) {
				$this->assertTrue(method_exists($cfg, $method));
			}

		}

	}

}
