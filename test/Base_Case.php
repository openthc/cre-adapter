<?php
/**
 * Test Helper
 */

namespace Test;

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

		// Dump on Errors
		switch ($res['code']) {
		case 422:
		case 500:
			if (empty($dump)) {
				$dump = sprintf('%d Response Code', $res['code']);
			}
			break;
		}

		// if (!empty($dump)) {
		// 	echo "\n<<<$dump<<<\n{$res}\n###\n";
		// }
		// $ret = \json_decode($this->raw, true);

		//$this->assertEquals('HTTPS', $res->getProtocol());
		$this->assertEquals($code, $res['code']);
		// $this->assertEquals('application/json', $res->getHeaderLine('content-type')); // RFCs
		// $this->assertEquals('text/json; charset=UTF-8', $res->getHeaderLine('content-type')); // LeafData
		// $this->assertIsArray($ret);
		// $this->assertCount(2, $ret);

		// $this->assertIsArray($ret['data']);
		// $this->assertIsArray($ret['meta']);

		return $res;

	}

}
