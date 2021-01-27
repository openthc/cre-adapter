<?php
/**
 */

class A_Config_Test extends \Test\OpenTHC_Base_TestCase
{

	private $cre_data;

	protected function setUp(): void 
	{
		$dir = APP_ROOT;

		$this->assertDirectoryExists($dir);
		$this->assertDirectoryIsReadable($dir);

		$ini_file_loc = sprintf('%s/etc/cre.ini', $dir);

		$this->assertFileExists($ini_file_loc);
		$this->assertFileIsReadable($ini_file_loc);

		$ini_file_list[] = sprintf('%s/etc/cre.ini', $dir);

		foreach ($ini_file_list as $ini_file) {
			$cre_data = parse_ini_file($ini_file, true, INI_SCANNER_RAW);
			break;
		}
	}

	function test_config_lib()
	{
		// Test out Bundled cre.ini file
	}
}
