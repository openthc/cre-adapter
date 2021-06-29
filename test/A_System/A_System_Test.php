<?php
/**
 */

namespace Test\A_System;

class A_System_Test extends \Test\Base_Case
{
	function test_metrc_tag_list()
	{
		$file = $_ENV['metrc-tag-file'];
		$this->assertTrue(is_file($file));
	}
}
