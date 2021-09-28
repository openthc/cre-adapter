<?php
/**
 * Create and Search for Varieties
 */
namespace Test\Metrc\C_Core;

class A_Variety extends OpenTHC_Metrc_Test
{
	protected function setUp() : void
	{
		$cfg = [
			'code' => $_ENV['metrc-cfg-code'],
			'service-key' => $_ENV['metrc-cfg-service-key'],
			'license-key' => $_ENV['metrc-license-g'],
		];
		$this->cre = \OpenTHC\CRE::factory($cfg);
	}

	public function testSearch() : void
	{
		// Fetch all and Find by Name because METRC doesn't return an ID
		$res = $this->cre->variety()->search();
		$this->assertValidResponse($res);

		foreach ($res['data'] as $s1) {
			if ($s1['Name'] == $_ENV['variety-0-name']) {
				$Variety = $s1;
			}
		}

		$this->assertIsArray($Variety);
		$this->assertEquals($_ENV['variety-0-name'], $Variety['Name']);
		$this->assertEquals($_ENV['variety-0-guid'], $Variety['Id']);
	}

	public function testCreate() : void
	{
		$res = $this->cre->variety()->create(array(
			'Name' => $_ENV['variety-1-name'],
			// 'IndicaPercentage' => $S_meta['indica-pct'],
			// 'SativaPercentage' => $S_meta['sativa-pct'],
			// 'ThcLevel' => $S_meta['thc-estimate'],
			// 'CbdLevel' => $S_meta['cbd-estimate'],
		));
		$this->assertValidResponse($res);

		foreach ($res['data'] as $s1) {
			if ($s1['Name'] == $_ENV['variety-1-name']) {
				$Variety = $s1;
			}
		}

		$this->assertIsArray($Variety);
		$this->assertEquals($_ENV['variety-1-name'], $Variety['Name']);
	}
}
