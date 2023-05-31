<?php
/**
 * Create and Search for Sections
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Metrc\C_Core;

class A_Section extends \OpenTHC\CRE\Test\OpenTHC_Metrc_Test
{

	protected function setUp() : void
	{
		$cfg = [
			'code' => $_ENV['metrc-cfg-code'],
			'service-sk' => $_ENV['metrc-cfg-service-sk'],
			'license-sk' => $_ENV['metrc-license-g-sk'],
		];
		$this->cre = \OpenTHC\CRE::factory($cfg);
	}

	public function testSearch() : void
	{

		$res = $this->cre->section()->search();
		$this->assertValidResponse($res);
		foreach ($res['data'] as $s) {
			if ($s['Name'] == $_ENV['section-0-name']) {
				$Section = $s;
			}
		}

		$this->assertIsArray($Section);
		$this->assertEquals($_ENV['section-0-name'], $Section['Name']);
		$this->assertEquals($_ENV['section-0-guid'], $Section['Id']);
	}

	public function testCreate() : void
	{
		$res = $this->cre->section()->create(array(
			'Name' => $_ENC['section-1-name'],
			'LocationTypeName' => $_ENV['section-1-type'],
		));
		$this->assertValidResponse($res);

		$res = $this->cre->section()->search();
		foreach ($res['data'] as $s) {
			if ($s['Name'] == $_ENV['section-1-name']) {
				$Section = $s;
			}
		}

		$this->assertIsArray($Section);
		$this->assertEquals($_ENV['section-1-name'], $Section['Name']);
	}
}
