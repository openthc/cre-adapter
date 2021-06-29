<?php
/**
 */

class A_System_Test extends \Test\OpenTHC_Base_TestCase
{
	function test_metrc_tag_list()
	{
		$file = $_ENV['metrc-tag-file'];
		$this->assertTrue(is_file($file));
	}
}
