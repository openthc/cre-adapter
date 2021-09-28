<?php
/**
 * Create and Search for Products
 */
namespace Test\Metrc\C_Core;

class A_Product extends OpenTHC_Metrc_Test
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
		$res = $this->cre->product()->search();
		$this->assertValidResponse($res);
		foreach ($res['data'] as $rec) {
			if ($rec['Name'] == $_ENV['product-0-name']) {
				$Product = $rec;
			}
		}

		$this->assertIsArray($Product);
		$this->assertEquals($_ENV['product-0-name'], $Product['Name']);
		$this->assertEquals($_ENV['product-0-guid'], $Product['Id']);
	}

	public function testCreate() : void
	{
		$obj = array(
			'Name' => $_ENV['product-1-name'],
			'UnitOfMeasure' => $_ENV['product-1-uom'],
			'UnitWeight' => $_ENV['product-1-qom'],
			'ItemCategory' => $_ENV['product-type-0-name'],
			'Strain' => $_ENV['variety-0-name'],
			//'UnitWeightUnitOfMeasure' => 'Each',
			//'UnitThcContent' => null,
			//'UnitThcContentUnitOfMeasure' => null,
		);
		$res = $cre->product()->create($obj);
		$this->assertValidResponse($res);

		$res = $this->cre->product()->search();
		$this->assertValidResponse($res);
		foreach ($res['data'] as $rec) {
			if ($rec['Name'] == $_ENV['product-1-name']) {
				$Product = $rec;
			}
		}

		$this->assertIsArray($Product);
		$this->assertEquals($_ENV['product-1-name'], $Product['Name']);
		$this->assertEquals($_ENV['product-1-guid'], $Product['Id']);
		$this->assertEquals($_ENV['product-1-uom'], $Product['UnitOfMeasure']);
		$this->assertEquals($_ENV['product-1-qom'], $Product['UnitWeight']);
		$this->assertEquals($_ENV['product-type-0-name'], $Product['ItemCategory']);
		$this->assertEquals($_ENV['variety-0-name'], $Product['Strain']);
	}
}
