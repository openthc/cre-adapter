<?php
/**
 * Test Lot Adjust
 */

namespace Test\Metrc\G_Process;

class C_Lot_Adjust_Test extends \Test\Metrc_Case
{
	protected function setUp() : void
	{
		// Reset API Connection to Lab
		$this->ghc = $this->_api([
			'license' => $_ENV['metrc-g0-public'],
			'license-secret' => $_ENV['metrc-g0-secret'],
		]);
	}

}
