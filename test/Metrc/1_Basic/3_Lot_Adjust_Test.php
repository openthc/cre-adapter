<?php
/**
 * Test Lot Adjust
 */

namespace Test\LeafData\Basic;

class Lot_Adjust_Test extends \Test\OpenTHC_Metrc_Test
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
