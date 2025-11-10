<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Metrc\Base;

class C_Variety_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_variety_create() : array
	{
		$variety_list = [];

		// Update Test
		$variety = [
			'Name' => sprintf('TEST VARIETY %s', $this->create_test_id()),
			'IndicaPercentage' => 12,
			'SativaPercentage' => 88,
			'ThcLevel' => 12.3456,
			'CbdLevel' => 1.2345,
			'TestingStatus' => 'None',
		];
		$res = $this->cre->variety()->create($variety);
		$this->assertValidResponse($res);
		$variety_list[] = $variety;

		// Delete Test
		$variety['Name'] = sprintf('TEST VARIETY %s DELETE', $this->create_test_id());
		$res = $this->cre->variety()->create($variety);
		$this->assertValidResponse($res);
		$variety_list[] = $variety;

		return $variety_list;

	}

	/**
	 * @depends test_variety_create
	 */
	function test_variety_search($variety_list) : array
	{
		$this->assertCount(2, $variety_list);

		$res = $this->cre->variety()->search();
		$this->assertValidResponse($res);
		foreach ($res['data'] as $rec) {
			foreach ($variety_list as $idx => $variety) {
				if ($rec['Name'] == $variety['Name']) {
					$variety_list[$idx] = $rec;
				}
			}
		}

		foreach ($variety_list as $idx => $variety) {
			$this->assertIsArray($variety);
			$this->assertNotEmpty($variety['Id']);
		}

		return $variety_list;

	}

	/**
	 * @depends test_variety_search
	 */
	function test_variety_update($variety_list) : array
	{
		$variety = $variety_list[0];

		$this->assertNotEmpty($variety['Id']);

		$variety['Name'] = sprintf('%s UPDATE', $variety['Name']);
		$res = $this->cre->variety()->update($variety);
		$this->assertValidResponse($res);

		return $variety_list;
	}

	/**
	 * @depends test_variety_update
	 */
	function test_variety_delete($variety_list)
	{
		$variety = $variety_list[1];

		$res = $this->cre->variety()->delete($variety['Id']);
		$this->assertValidResponse($res);

	}

	public function testIndicaSativaNotEqual100() : void
	{
		$res = $this->cre->variety()->create(array(
			'Name' => sprintf('TEST VARIETY %s', $this->create_test_id()),
			'IndicaPercentage' => 25,
			'SativaPercentage' => 25,
			'CbdLevel' => 0,
			'ThcLevel' => 0,
		));
		$this->assertValidResponse($res, 400);

		$this->assertEquals(0, $res['data']['row']);
		$this->assertEquals('Indica and Sativa Percentages combined must equal 100%.', $res['data']['message']);
	}

}
