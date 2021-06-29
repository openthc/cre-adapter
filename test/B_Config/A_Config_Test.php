<?php
/**
 * Testing the config file and making sure all the spots are correctly filled
 */

namespace Test\B_Config;

class A_Config_Test extends \Test\Base_Case
{

	function test_config_lib()
	{
		$dir = dirname(dirname(__dir__))."/etc";

		$this->assertDirectoryExists($dir);
		$this->assertDirectoryIsReadable($dir);

		$ini_file_loc = sprintf('%s/cre.ini', $dir);

		$this->assertFileExists($ini_file_loc);
		$this->assertFileIsReadable($ini_file_loc);

		$cre_data = parse_ini_file($ini_file_loc, true, INI_SCANNER_RAW);

		$this->assertNotNull($cre_data);
		$this->assertNotEmpty($cre_data);
		$this->assertIsArray($cre_data);

	 	foreach ($cre_data as $cre) {

			$this->assertNotEmpty($cre);
			$this->assertArrayHasKey('name', $cre, sprintf('%s missing name', $cre['name']));
			$this->assertArrayHasKey('class', $cre, sprintf('%s missing class', $cre['name']));
			$this->assertArrayHasKey('epoch', $cre, sprintf('%s missing epoch', $cre['name']));
			$this->assertArrayHasKey('engine', $cre, sprintf('%s missing engine', $cre['name']));
			$this->assertContains($cre['engine'], ['metrc', 'biotrack', 'leafdata']);
			$this->assertArrayHasKey('server', $cre, sprintf('%s missing server', $cre['name']));

			if ($cre['engine'] == 'metrc') {
				$this->assertArrayHasKey(
					'service-key',
					$cre,
					sprintf('%s missing service-key', $cre['name'])
				);
			}
		}
	}
}
