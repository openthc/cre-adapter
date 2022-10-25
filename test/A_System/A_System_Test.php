<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\A_System;

class A_System_Test extends \OpenTHC\CRE\Test\Base_Case
{
	function test_metrc_tag_list()
	{
		$file = $_ENV['metrc-tag-file'];
		$this->assertTrue(is_file($file));
	}
}
