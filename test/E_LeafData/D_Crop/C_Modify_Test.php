<?php
/**
 * Plant Modify
 */

namespace Test\E_LeafData\D_Crop;

class C_Modify_Test extends \Test\LeafData_Case
{
	/**
	 * Get a random Plant by the GUID suffix only
	 */
	public function testGetPlantByShortGUID()
	{
		$P = $this->find_random_plant();
		$plant_id = $P['global_id'];

		// Parse the suffix identifier from the Strain ID so that it does not contain the License ID
		preg_match('/^WA\w+\.(\w+)$/', $plant_id, $matches);
		$this->assertEqual(1, count($matches));
		$plant_id = $matches[1];

		$P1 = $this->cre->get(sprintf('/plants?f_global_id=%s', $plant_id));
		$this->assertEqual($P['global_id'], $P1['global_id']);
	}

	/**
	 * Do a simple Modify call to a random Plant
	 */
	public function testSimplePlantModify()
	{
		$P = $this->find_random_plant();
		$S = $this->find_random_strain();

		$strain_id = $P['global_strain_id'];
		$this->assertNotEqual($S['gloal_id'], $strain_id);

		$mod_plant = array(
			'global_id' => $P['global_id'],
			'global_area_id' => $P['global_area_id'],
			'global_batch_id' => $P['global_batch_id'],
			'plant_created_at' => $P['plant_created_at'],
			'origin' => $P['origin'],
			'stage' => $P['stage'],
			'is_mother' => $P['is_mother'],
			// Modify the strain only
			'global_strain_id' => $strain_id, // Full, Cannonical GUID
		);

		// Update
		$arg = array('plant' => $mod_plant);
		$ret = $this->cre->post('/plants/update', $arg);

		$this->assertEquals(200, $ret['code']);
		$this->assertEquals('success', $ret['status']);

		$P1 = $this->cre->get('plants?f_global_id=' . $P['global_id']);

		$this->assertNotEqual($P['global_strain_id'], $P1['global_strain_id']);
		$this->assertEqual($S['global_id'], $P1['global_strain_id']);
	}

	 /**
	  * Do a simple Modify call to a random Plant
	  * Only pass the PL number of the object and not the full guid with a license
	  */
	  public function testPlantModifyPartialGUID()
	  {
		$P = $this->find_random_plant();
		$S = $this->find_random_strain();

		$strain_id = $P['global_strain_id'];
		$this->assertNotEqual($S['gloal_id'], $strain_id);

		// Parse the suffix identifier from the Strain ID so that it does not contain the License ID
		preg_match('/^WA\w+\.(\w+)$/', $strain_id, $matches);
		$this->assertEqual(1, count($matches));
		$strain_id = $matches[1];

		$mod_plant = array(
			'global_id' => $P['global_id'],
			'global_area_id' => $P['global_area_id'],
			'global_batch_id' => $P['global_batch_id'],
			'plant_created_at' => $P['plant_created_at'],
			'origin' => $P['origin'],
			'stage' => $P['stage'],
			'is_mother' => $P['is_mother'],
			// Modify the strain only
			'global_strain_id' => $strain_id,
		);

		// Update
		$arg = array('plant' => $mod_plant);
		$ret = $this->cre->post('/plants/update', $arg);

		$this->assertEquals(200, $ret['code']);
		$this->assertEquals('success', $ret['status']);

		$P1 = $this->cre->get('plants?f_global_id=' . $P['global_id']);

		$this->assertNotEqual($P['global_strain_id'], $P1['global_strain_id']);
		$this->assertEqual($S['global_id'], $P1['global_strain_id']);
	  }
}
