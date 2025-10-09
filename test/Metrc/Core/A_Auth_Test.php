<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\E_Metrc\A_Core;

class A_Auth_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_auth()
	{
		$res = $this->cre->uomList();
		$this->assertNotEmpty($res);
		$this->assertValidResponse($res);
		$this->assertIsArray($res);
	}
}
