<?php
/**
 * Test Helper
 */

namespace Test;

class OpenTHC_Base_TestCase extends \PHPUnit\Framework\TestCase
{
	protected $ghc; // API Guzzle HTTP Client
	protected $raw; // Raw Response Buffer

	// protected function setUp() : void
	// {
	// 	$this->ghc = $this->_api();
	// }


	/**
		HTTP Utility
	*/
	function get($url)
	{
		$res = $this->ghc->get($url);
		$ret = $this->assertValidResponse($res, 200); // , "GET FAILED to $url");
		return $ret;
	}


	/**
		HTTP Utility
	*/
	function post($url, $arg)
	{
		$res = $this->ghc->post($url, array('json' => $arg));
		return $res;
	}


	function _data_stash_get()
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

	function _data_stash_put($f, $d)
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
	function assertValidResponse($res, $code=200, $dump=null)
	{
		$this->assertNotEmpty($res);

		// Dump on Errors
		$hrc = $res->getStatusCode();
		switch ($hrc) {
		case 422:
		case 500:
			if (empty($dump)) {
				$dump = sprintf('%d Response Code', $hrc);
			}
			break;
		}

		$this->raw = $res->getBody()->getContents();

		if (!empty($dump)) {
			echo "\n<<<$dump<<<\n{$this->raw}\n###\n";
		}

		$ret = \json_decode($this->raw, true);

		//$this->assertEquals('HTTPS', $res->getProtocol());
		$this->assertEquals($code, $res->getStatusCode());
		// $this->assertEquals('application/json', $res->getHeaderLine('content-type')); // RFCs
		$this->assertEquals('text/json; charset=UTF-8', $res->getHeaderLine('content-type')); // LeafData
		$this->assertIsArray($ret);
		$this->assertCount(2, $ret);

		$this->assertIsArray($ret['data']);
		$this->assertIsArray($ret['meta']);

		return $ret;

	}

}
