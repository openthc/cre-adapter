<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\E_Metrc\C_Batch;

class A_Alpha_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_type_list()
	{
		$res = $this->cre->get('/plantbatches/v1/types');
		$this->assertIsArray($res);
		$this->assertContains('Clone', $res);
		$this->assertContains('Seed', $res);

		// $res = $this->cre->batch()->getTypeList();
		// $this->assertValidResponse($res);
		// $this->assertArrayHasKey('Clone', $res['data']);
		// $this->assertArrayHasKey('Seed', $res['data']);

	}
}
