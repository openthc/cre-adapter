<?php
/**
 * Testing the config file and making sure all the spots are correctly filled
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\B_Config;

class A_Config_Test extends \OpenTHC\CRE\Test\Base_Case
{
	/**
	 * @test
	 */
	function env()
	{
		$key_list = [
			'OPENTHC_TEST_METRC_CRE',
			'OPENTHC_TEST_METRC_SERVICE_SK',
			'OPENTHC_TEST_METRC_LICENSE_PK',
			'OPENTHC_TEST_METRC_LICENSE_SK',
		];

		foreach ($key_list as $key) {
			$this->assertNotEmpty(getenv($key), sprintf('ENV "%s" is empty', $key));
		}

	}

	/**
	 *
	 */
	function test_config_lib()
	{
		$cre_list = \OpenTHC\CRE::getEngineList();
		$this->assertNotNull($cre_list);
		$this->assertIsArray($cre_list);
		$this->assertNotEmpty($cre_list);

		foreach ($cre_list as $cre_code => $cre_data) {

			$this->assertEquals($cre_code, $cre_data['id']);
			$this->assertEquals($cre_code, $cre_data['code']);

			// 	$this->assertNotEmpty($cre);
			$this->assertArrayHasKey('id', $cre_data, sprintf('%s missing id', $cre_code));
			$this->assertArrayHasKey('code', $cre_data, sprintf('%s missing code', $cre_code));
			$this->assertArrayHasKey('name', $cre_data, sprintf('%s missing name', $cre_code));
			$this->assertArrayHasKey('class', $cre_data, sprintf('%s missing class', $cre_code));
			// 	$this->assertArrayHasKey('tz', $cre, sprintf('%s missing TZ', $cre_code));
			// 	// $this->assertArrayHasKey('epoch', $cre, sprintf('%s missing epoch', $cre_code));
			// 	// $this->assertArrayHasKey('engine', $cre, sprintf('%s missing engine', $cre_code));

			if ( ! empty($cre['engine'])) {
				$this->assertContains($cre_data['engine'], [ 'biotrack', 'leafdata', 'metrc', 'openthc' ]);
				switch ($cre['engine']) {
					case 'biotrack':
						$this->assertArrayHasKey('server', $cre_data, sprintf('%s missing server', $cre_code));
						break;
					case 'leafdata':
						$this->assertArrayHasKey('server', $cre_data, sprintf('%s missing server', $cre_code));
						break;
					case 'metrc':
						$this->assertArrayHasKey('server', $cre_data, sprintf('%s missing server', $cre_code));
						$this->assertArrayHasKey('service-sk', $cre_data, sprintf('%s missing service-sk', $cre_code));
						break;
				}
			}

		}
	}
}
