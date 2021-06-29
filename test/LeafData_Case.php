<?php
/**
 * Test Helper for LeafData
 */

namespace Test;

class LeafData_Case extends \Test\Base_Case
{
	protected $cre;

	protected function setUp() : void
	{
		// Default is Connection to Grower License
		$this->cre = $this->_api([
			'license' => $_ENV['leafdata-g0-public'],
			'license-secret' => $_ENV['leafdata-g0-secret'],
		]);
	}

	function find_random_batch_of_type($t) : array
	{
		$res = $this->cre->get('batches?f_type=' .$t);
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);
		$this->assertGreaterThan(2, $res['data']);

		$rnd_list = [];
		foreach ($res['data'] as $b) {
			if ('open' == $b['status']) {
				$rnd_list[] = $b;
			}
		}

		$i = array_rand($rnd_list);
		$B = $rnd_list[$i];

		return $B;

	}

	/**
	 *
	 * @param [type] $f [description]
	 * @return [type] [description]
	 */
	function find_random_lot($f=null) : array
	{
		// @todo Handle Multiple Pages?
		$res = $this->cre->get('inventories');
		$this->assertCount(9, $res);
		$this->assertIsArray($res['data']);
		https://pipe.openthc.com/stem/leafdata/wa/test
		$rnd_list = [];
		foreach ($res['data'] as $x) {
			$rnd_list[] = $x;
		}

		$i = array_rand($rnd_list);
		$r = $rnd_list[$i];

		return $r;

	}


	function find_random_plant($f=null) : array
	{
		$res = $this->cre->get('plants?f_stage=growing');
		$this->assertNotEmpty($res);
		$this->assertCount(9, $res);
		$this->assertIsArray($res['data']);

		// echo "\nWe Found: " . count($res['data']) . " Plants\n";
		// var_dump($res['next_page_url']);

		$rnd_list = [];
		foreach ($res['data'] as $x) {
			if ('growing' == $x['stage']) {
				$rnd_list[] = $x;
			}
		}

		$i = array_rand($rnd_list);
		$r = $rnd_list[$i];

		return $r;

	}

	function find_random_strain() : array
	{
		$res = $this->cre->get('strains');
		$this->assertCount(9, $res);
		$this->assertIsArray($res['data']);

		$rnd_list = [];
		foreach ($res['data'] as $x) {
			$rnd_list[] = $x;
		}

		$i = array_rand($rnd_list);
		$r = $rnd_list[$i];

		return $r;

	}

	/**
	 */
	protected function _api()
	{
		$cfg = \OpenTHC\CRE::getEngine('usa/wa/test');
		$cfg['license'] = 'bunk-license';
		$cfg['license-key'] = 'bunk-license-key';
		return \OpenTHC\CRE::factory($cfg);
	}

}
