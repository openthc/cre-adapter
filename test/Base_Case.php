<?php
/**
 * Test Helper
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test;

class Base_Case extends \OpenTHC\Test\Base {

	function _data_stash_get() : array
	{
		if (is_file($f)) {
			if (is_readable($f)) {
				$x = file_get_contents($f);
				$x = json_decode($x, true);
				return $x;
			}
		}

		return null;

	}

	function _data_stash_put($f, $d) : bool
	{
		if (!is_string($d)) {
			$d = json_encode($d);
		}

		return file_put_contents($f, $d);
	}

	function assertValidResponse($res, $code_expect=200, $type_expect=null, $dump=null) {

		$ret = parent::assertValidResponse($res, $code_expect, $type_expect, $dump);
		switch ($type_expect) {
		case 'application/json':
			$this->assertIsArray($ret['data']);
			$this->assertIsArray($ret['meta']);
			break;
		default:
			// Nothing
		}

		return $ret;

	}

}
