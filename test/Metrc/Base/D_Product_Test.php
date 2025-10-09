<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\E_Metrc\B_Base;

class D_Product_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	/**
	 * Get Product Type List
	 */
	function test_product_type_list() : array
	{
		$res = $this->cre->product()->getTypeList();
		$this->assertValidResponse($res);

		return $res['data'];
	}

	/**
	 * @depends test_product_type_list
	 */
	function test_product_create($product_type_list) : array
	{
		$variety = $this->get_random_variety();

		foreach ($product_type_list as $product_type) {
			// Create Test
			$product = [
				'Name' => sprintf('TEST PRODUCT %s %s', $product_type['Name'], $this->create_test_id()),
				'ItemCategory' => $product_type['Name'], // 'Immature Plants',
				'UnitOfMeasure' => 'Each',
				'Strain' => $variety['Name'],
				// 'UnitWeight' => 1234.5678,
				// 'UnitWeightUnitOfMeasure' => 'Grams',
				// 'ItemBrand' => null,
				// 'AdministrationMethod' => null,
				// 'UnitCbdPercent' => null,
				// 'UnitCbdContent' => null,
				// 'UnitCbdContentUnitOfMeasure' => null,
				// 'UnitCbdContentDose' => null,
				// 'UnitCbdContentDoseUnitOfMeasure' => null,
				// 'UnitThcPercent' => null,
				// 'UnitThcContent' => null,
				// 'UnitThcContentUnitOfMeasure' => null,
				// 'UnitThcContentDose' => null,
				// 'UnitThcContentDoseUnitOfMeasure' => null,
				'UnitVolume' => 1234.56,
				'UnitVolumeUnitOfMeasure' => 'Milliliters',
				'UnitWeight' => 12.34,
				'UnitWeightUnitOfMeasure' => 'Grams',
				// 'ServingSize' => null,
				// 'SupplyDurationDays' => null,
				// 'NumberOfDoses' => null,
				'Ingredients' => 'SOME STUFF',
			];
			switch ($product_type['QuantityType']) {
			// 	case 'CountBased':
			// 		$product['UnitOfMeasure'] = 'Each';
			// 		break;
				case 'WeightBased':
					$product['UnitOfMeasure'] = 'Grams';
			// 		$product['UnitWeightUnitOfMeasure'] = 'Grams';
					break;
			// 	default:
			// 		throw new \Exception('Invalid Quantty Type');
			}

			// if ($product_type['RequiresUnitVolume']) {
				// $product['UnitVolume'] = 1234.56;
				// $product['UnitWeightUnitOfMeasure'] = 'ml';
			// }

			// var_dump($product);
			// var_dump($product_type);

			$res = $this->cre->product()->create($product);
			$this->assertValidResponse($res);

			$product_list[] = $product;
		}

		// // Delete Test
		// $product['Name'] = sprintf('TEST PRODUCT %s DELETE', $this->create_test_id());
		// // $product['ItemCategory'] = '';
		// // $product['UnitOfMeasure'] = '';
		// // $product['LocationTypeName'] = 'Default Location Type'; // Oklahoma
		// $res = $this->cre->product()->create($product);
		// $this->assertValidResponse($res);
		// $product_list[] = $product;

		return $product_list;

	}

	/**
	 * @depends test_product_create
	 */
	function test_product_search(array $product_list) : array
	{
		$this->assertCount(28, $product_list);

		$res = $this->cre->product()->search();
		$this->assertValidResponse($res);
		foreach ($res['data'] as $rec) {
			foreach ($product_list as $idx => $product) {
				if ($rec['Name'] == $product['Name']) {
					$product_list[$idx] = $rec;
				}
			}
		}

		foreach ($product_list as $idx => $product) {
			$this->assertIsArray($product);
			$this->assertNotEmpty($product['Id']);
		}

		return $product_list;

	}

	/**
	 * @depends test_product_search
	 */
	function test_product_update(array $product_list) : array
	{
		$product = array_shift($product_list);

		$this->assertNotEmpty($product['Id']);

		$product['Name'] = sprintf('%s-UPDATE', $product['Name']);
		// Their Response uses a different field than the Request
		$product['ItemCategory'] = $product['ProductCategoryName'];
		$product['UnitOfMeasure'] = $product['UnitOfMeasureName'];

		$res = $this->cre->product()->update($product);
		$this->assertValidResponse($res);

		// Find The Updated
		$product_update = null;
		$res = $this->cre->product()->search();
		$this->assertValidResponse($res);
		foreach ($res['data'] as $rec) {
			// var_dump($rec);
			if ($rec['Id'] == $product['Id']) {
				$product_update = $rec;
				break;
			}
		}

		$this->assertNotEmpty($product_update);
		$this->assertEquals($product['Id'], $product_update['Id']);
		$this->assertEquals($product['Name'], $product_update['Name']);

		return $product_list;
	}

	/**
	 * @depends test_product_update
	 */
	function test_product_delete(array $product_list) : array
	{
		$product = array_shift($product_list);
		$res = $this->cre->product()->delete($product['Id']);
		$this->assertValidResponse($res);

		return $product_list;
	}

	/**
	 * Change an item’s unit of measure
	 *
	 * @depends test_product_delete
	 */
	function test_product_update_unitofmeasure(array $product_list) : array
	{
		// Find One to Fuck Up
		foreach ($product_list as $product0) {
			if ('Grams' == $product0['UnitOfMeasureName']) {
				$product = $product0;
				break;
			}
		}

		$product['ItemCategory'] = $product['ProductCategoryName'];
		$product['UnitOfMeasure'] = 'Ounces';
		$product['UnitWeightUnitOfMeasure'] = 'Ounces';

		// The Error When Something? is Missing
		// array(3) {
		// 	'code' =>
		// 	int(400)
		// 	'data' =>
		// 	string(58) "{"Message":"Value cannot be null.\r\nParameter name: key"}"
		// 	'meta' =>
		// 	array(1) {
		// 	  'note' =>
		// 	  string(42) "Value cannot be null.
		//   Parameter name: key"
		// 	}
		//   }
		// $product['UnitWeightUnitOfMeasure'] = 'Grams';
		// 'UnitWeightUnitOfMeasure' => 'Grams',
		$res = $this->cre->product()->update($product);
		$this->assertValidResponse($res);

		return $product_list;

	}

}
