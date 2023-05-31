<?php
/**
 * Test Helper
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test;

class Base_Case extends \PHPUnit\Framework\TestCase
{
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

		/**
	* Intends to become an assert wrapper for a bunch of common response checks
	* @param $res, Response Object
	* @return void
	*/
	function assertValidResponse($res, $code=200, $dump=null) : array
	{
		$this->assertNotEmpty($res);
		$this->assertIsArray($res);

		// Dump on Errors
		switch ($res['code']) {
		case 400:
		case 422:
		case 500:
			if (empty($dump)) {
				$dump = sprintf('%d Response Code', $res['code']);
			}
			break;
		}

		if ( ! empty($dump)) {
			echo "\n<<<$dump<<<\n";
			var_dump($res);
			echo "\n###\n";
		}
		// $ret = \json_decode($this->raw, true);

		$this->assertEquals($code, $res['code']);

		// $this->assertCount(2, $res);

		$this->assertIsArray($res['data']);
		$this->assertIsArray($res['meta']);

		return $res;

	}

}
