<?php
/**
 * Create and Search for Products
 *
 * SPDX-License-Identifier: MIT
 */
namespace OpenTHC\CRE\Test\Metrc\G_Process;

class A_Lot extends \OpenTHC\CRE\Test\Base
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

	public function testSearch() : void {}

	public function testCreate() : void {}

	/**
	 * Convert harvest flower into lotted material
	 */
	public function testConvert() : void
	{
		$obj = [
			'Tag' => $_ENV['lot-convert-0-output-tag'],
			'Location' => $_ENV['section-0-name'],
			'Item' => $_ENV['product-0-name'],
			'Quantity' => $_ENV['lot-convert-0-output-qty'],
			'UnitOfMeasure' => 'Grams',
			'IsProductionBatch' => false,
			'ProductionBatchNumber' => '',
			'ProductRequiresRemediation' => false,
			'ActualDate' => date('Y-m-d', time()),
			'Ingredients' => [
				'Package' => $_ENV['lot-convert-0-source-guid'],
				'Quantity' => $_ENV['lot-convert-0-source-qty'],
				'UnitOfMeasure' => 'Grams',
			],
		];
		$res = $cre->packageCreate($obj);
		$this->assertValidResponse($res);
	}
}
