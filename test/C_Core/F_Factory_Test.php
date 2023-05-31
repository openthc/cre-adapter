<?php
/**
 * Test the Factory Returns the Right Things
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\C_Core;

class F_Factory_Test extends \OpenTHC\CRE\Test\Base_Case
{
	function test_factory()
	{
		$cre_list = \OpenTHC\CRE::getEngineList();
		foreach ($cre_list as $cre_code => $cfg) {

			$cfg['license'] = 'TEST';
			$cfg['license-sk'] = 'TEST';
			$cfg['service'] = 'TEST';
			$cfg['service-sk'] = 'TEST';

			// try {
				$cre = \OpenTHC\CRE::factory($cfg);
				$this->assertIsObject($cre);
				$this->assertTrue(
					$cre instanceof \OpenTHC\CRE\Base
				);
			// } catch (\Exception $e) {
				// $this->assertEmpty($e, sprintf('Exception on %s: %s', $cre_code, $e->getMessage()));
			// }
		}
	}
}
