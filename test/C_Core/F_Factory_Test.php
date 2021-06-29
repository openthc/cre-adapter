<?php
/**
 * Test the Factory Returns the Right Things
 */

class F_Factory_Test extends \Test\OpenTHC_Base_TestCase
{
	function test_factory()
	{
		$cre_list = \OpenTHC\CRE::getEngineList();
		foreach ($cre_list as $cre_code => $cfg) {

			$cfg['license'] = 'bunk-license';
			$cfg['license-key'] = 'bunk-license-key';

			try {
				$cre = \OpenTHC\CRE::factory($cfg);
				$this->assertIsObject($cre);
				$this->assertTrue(
					$cre instanceof \OpenTHC\CRE\Base
				);
			} catch (\Exception $e) {
				$this->assertEmpty($e, sprintf('Exception on %s: %s', $cre_code, $e->getMessage()));
			}
		}
	}
}
