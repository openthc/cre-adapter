<?php
/**
 * Test the Factory Returns the Right Things
 */

class F_Factory_Test extends \Test\OpenTHC_Base_TestCase
{
	function test_factory()
	{
		$cre_list = \OpenTHC\CRE::getEngineList();
		foreach ($cre_list as $cfg) {
			$cre = \OpenTHC\CRE::factory($cfg);
			// assertIsObject();
			// assertImplements();
			// assertFunctions();  Necessary?
		}
	}
}


