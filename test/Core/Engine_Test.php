<?php
/**
 * Load Each Engine
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\C_Core;

class Engine_Test extends \OpenTHC\CRE\Test\Base_Case
{
	/**
	 *
	 */
	function test_engine_ping()
	{
		$cfg_base = [
			'contact' => 'TEST',
			'company' => 'TEST',
			'username' => 'TEST',
			'password' => 'TEST',
			'service' => 'TEST',
			'service-sk' => 'TEST',
			'license' => 'TEST',
			'license-sk' => 'TEST',
		];

		$cre_list = \OpenTHC\CRE::getEngineList();

		foreach ($cre_list as $cfg0) {

			$cfg1 = array_merge($cfg0, $cfg_base);

			$class = $cfg1['class'];
			$cre = new $class($cfg1);
			$this->assertTrue($cre instanceof \OpenTHC\CRE\Base);

			// 'search', 'single', 'update', 'delete',
			// foreach ([ 'formatError', 'ping' ] as $method) {
			// 	$this->assertTrue(method_exists($cfg, $method), "Class '$class' Missing Method '$method'");
			// }

		}

	}

}
