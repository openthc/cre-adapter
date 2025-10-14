<?php
/**
 * Test Helper for Metrc
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test;

class Metrc_Case extends \OpenTHC\CRE\Test\Base_Case
{
	protected $cre;

	protected function setUp() : void
	{
		// Default is Connection to Grower License
		$this->cre = $this->_api();
	}

	function create_test_id()
	{
		return substr(_ulid(), 0, 12);
	}

	/**
	 *
	 * @param [type] $f [description]
	 * @return [type] [description]
	 */
	function find_random_lot($f=null)
	{

	}

	function find_random_plant($f=null)
	{

	}

	function get_random_variety($count_want=1)
	{
		$res = $this->cre->variety()->search();
		$this->assertValidResponse($res);

		$key = array_rand($res['data']);

		$variety = $res['data'][$key];

		return $variety;

	}

	/**
		@param $b The Base URL
	*/
	protected function _api()
	{
		$opt = [
			'service-sk' => getenv('OPENTHC_TEST_METRC_SERVICE_SK'),
			'license-sk' => getenv('OPENTHC_TEST_METRC_LICENSE_SK'),
			'license' => [
				'id' => getenv('OPENTHC_TEST_METRC_LICENSE_PK'),
				'code' => getenv('OPENTHC_TEST_METRC_LICENSE_PK'),
				'guid' => getenv('OPENTHC_TEST_METRC_LICENSE_PK'),
				'name' => getenv('OPENTHC_TEST_METRC_LICENSE_PK'),
			]
		];
		$cfg = \OpenTHC\CRE::getConfig(getenv('OPENTHC_TEST_METRC_CRE'));
		$cfg = array_merge($cfg, $opt);
		return \OpenTHC\CRE::factory($cfg);
	}

}
