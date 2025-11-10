<?php
/**
 * Test the Factory Returns the Right Things
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Core;

class Factory_Test extends \OpenTHC\CRE\Test\Base_Case
{
	function test_factory()
	{
		$cre_list = \OpenTHC\CRE::getEngineList();
		foreach ($cre_list as $cre_code => $cfg) {

			$cfg['contact'] = 'TEST';
			$cfg['company'] = 'TEST';
			$cfg['license'] = 'TEST';
			$cfg['license-sk'] = 'TEST';
			$cfg['service'] = 'TEST';
			$cfg['service-sk'] = 'TEST';

			$cre = \OpenTHC\CRE::factory($cfg);
			$this->assertIsObject($cre);
			$this->assertTrue($cre instanceof \OpenTHC\CRE\Base);

			$cfg['id'] = str_replace('-', '/', $cfg['id']);
			$cfg['code'] = str_replace('-', '/', $cfg['code']);

			$cre = \OpenTHC\CRE::factory($cfg);
			$this->assertIsObject($cre);
			$this->assertTrue($cre instanceof \OpenTHC\CRE\Base);

		}
	}
}
