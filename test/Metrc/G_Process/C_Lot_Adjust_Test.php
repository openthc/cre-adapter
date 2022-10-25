<?php
/**
 * Test Lot Adjust
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Metrc\G_Process;

class C_Lot_Adjust_Test extends \OpenTHC\CRE\Test\Metrc_Case
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
