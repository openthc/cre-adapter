<?php
/**
 * Test Helper for LeafData
 */

namespace Test;

class OpenTHC_LeafData_Test extends OpenTHC_Base_TestCase
{
	/**
	 * Intends to become an assert wrapper for a bunch of common response checks
	 * @param $res, Response Object
	 * @return void
	 */
	function assertValidResponse($res, $code=200, $dump=null)
	{}

	/**
	 *
	 * @param [type] $f [description]
	 * @return [type] [description]
	 */
	function find_random_lot($f=null)
	{}

	function find_random_plant($f=null)
	{}

	function find_random_strain()
	{}

	/**
		@param $b The Base URL
	*/
	protected function _api($opt=null)
	{}

}