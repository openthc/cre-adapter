<?php
/**
 * Testing the config file and making sure all the spots are correctly filled
 */

class A_Config_Test extends \Test\OpenTHC_Base_TestCase
{

	private $cre_data;

	protected function setUp(): void 
	{
		$dir = dirname(dirname(__dir__))."/etc";

		$this->assertDirectoryExists($dir);
		$this->assertDirectoryIsReadable($dir);

		$ini_file_loc = sprintf('%s/cre.ini', $dir);

		$this->assertFileExists($ini_file_loc);
		$this->assertFileIsReadable($ini_file_loc);

		$this->cre_data = parse_ini_file($ini_file_loc, true, INI_SCANNER_RAW);

		$this->assertNotNull($this->cre_data);
		$this->assertNotEmpty($this->cre_data);
		$this->assertIsArray($this->cre_data);
	}

	function test_config_lib()
	{
	 	foreach ($this->cre_data as $cre) {
			//if engine is metrc assert service key

			$this->assertNotEmpty($cre);
			$this->assertArrayHasKey('name', $cre, sprintf('%s missing name', $cre['name']));
			// $this->assertMatchesRegularExpression('/([A-Za-z])///',$cre['name']);
			$this->assertArrayHasKey('class', $cre, sprintf('%s missing class', $cre['name']));
			
			$this->assertArrayHasKey('epoch', $cre, sprintf('%s missing epoch', $cre['name']));
			
			$this->assertArrayHasKey('engine', $cre, sprintf('%s missing engine', $cre['name']));
			$this->assertContains($cre['engine'], ['metrc', 'biotrack', 'leafdata']);
			
			$this->assertArrayHasKey('server', $cre, sprintf('%s missing server', $cre['name']));
			
			if($cre['engine'] == 'metrc') {
				$this->assertArrayHasKey(
					'service-key', 
					$cre, 
					sprintf('%s missing service-key', $cre['name'])	
				);
			}
		}
	}
}
