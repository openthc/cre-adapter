<?php
/**
 * Testing the config file and making sure all the spots are correctly filled
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Config;

class A_Config_Test extends \OpenTHC\CRE\Test\Base_Case
{
	/**
	 * @test
	 */
	function env()
	{
		$this->markTestSkipped('FOO');

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
		$this->assertCount(53, $cre_list);

		foreach ($cre_list as $cre_code => $cre_data) {
			$this->config_check($cre_code, $cre_data);
		}
	}

	function test_config_app()
	{
		if (empty($_ENV['OPENTHC_TEST_APP_ROOT'])) {
			$this->markTestSkipped('Skipped, No App_TEST');
		}

		$cre_list = \OpenTHC\CRE::getEngineList($_ENV['OPENTHC_TEST_APP_ROOT']);
		foreach ($cre_list as $cre_code => $cre_data) {
			$this->config_check($cre_code, $cre_data);
		}

	}

	protected function config_check($cre_code, $cre_data)
	{
		$this->assertArrayHasKey('id', $cre_data, sprintf('CRE "%s" missing id', $cre_code));
		$this->assertArrayHasKey('tz', $cre_data, sprintf('CRE "%s" missing TZ', $cre_code));
		$this->assertArrayHasKey('code', $cre_data, sprintf('CRE "%s" missing code', $cre_code));
		$this->assertArrayHasKey('live', $cre_data, sprintf('CRE "%s" missing attribute "live"', $cre_code));
		$this->assertArrayHasKey('name', $cre_data, sprintf('CRE "%s" missing name', $cre_code));
		$this->assertArrayHasKey('class', $cre_data, sprintf('CRE "%s" missing class', $cre_code));
		$this->assertArrayHasKey('epoch', $cre_data, sprintf('CRE "%s" missing epoch', $cre_code));
		$this->assertArrayHasKey('engine', $cre_data, sprintf('CRE "%s" missing engine', $cre_code));

		$this->assertContains($cre_data['engine'], [ 'biotrack', 'metrc', 'openthc' ]);
		switch ($cre_data['engine']) {
			case 'biotrack':
				$this->assertArrayHasKey('server', $cre_data, sprintf('%s missing server', $cre_code));
				break;
			case 'metrc':
				$this->assertArrayHasKey('server', $cre_data, sprintf('%s missing server', $cre_code));
				$this->assertArrayHasKey('service-sk', $cre_data, sprintf('%s missing service-sk', $cre_code));
				break;
			case 'openthc':
				$this->assertArrayHasKey('server', $cre_data, sprintf('%s missing server', $cre_code));
				$this->assertArrayHasKey('service-sk', $cre_data, sprintf('%s missing service-sk', $cre_code));
				break;
			default:
				$this->assertTrue(false, sprintf('CRE "%s" has an unknown engine "%s"', $cre_code, $cre_data['engine']));
		}

	}
}
