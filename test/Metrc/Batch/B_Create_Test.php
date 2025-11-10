<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Metrc\Batch;

class B_Create_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_create()
	{
		// Only Works when License is a GROWER TYPE
		$res = $this->cre->batch()->create([
			'Name' => sprintf('Plant Batch %04x - Alpha', $this->pid),
			'Type' => 'Seed',
			'Count' => 3,
			'Strain' => 'abe8b3bb - UPDATE',
			'ActualDate' => date('Y-m-d'),
		]);
		$this->assertValidResponse($res);

		$res = $this->cre->batch()->create([
			'Location' => 'TEST0 a6f63bd6',
			'Name' => sprintf('Plant Batch %04x - Alpha', $this->pid),
			'Type' => 'Clone',
			'Count' => 6,
			'Strain' => 'abe8b3bb - UPDATE',
			'ActualDate' => date('Y-m-d'),
		]);
		var_dump($res);
		$this->assertValidResponse($res);

	}
}
