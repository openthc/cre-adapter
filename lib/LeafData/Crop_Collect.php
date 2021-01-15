<?php
/**
 * We Call Crop Collect, they call Harvest & Cure & Waste
 */

namespace OpenTHC\CRE\LeafData;

class Crop_Collect extends \OpenTHC\CRE\LeafData\Base
{

	function setPlantBatch($b)
	{
		$this->_batch_p = $b;
	}

	function setHarvestBatch($b)
	{
		$this->_batch_h = $b;
	}

	function setSection($s)
	{
		$this->_section = $s;
	}

	function addPlant($p)
	{
		$this->_plant_list[] = $p;
	}

	function wet_1300($buds, $trim=0, $junk=0)
	{
		$data = array(
			'global_area_id' => $this->_section,
			'global_harvest_batch_id' => null,
			'global_plant_ids' => array(),
			'qty_harvest' => 0,
			'flower_wet_weight' => $buds,
			'other_wet_weight' => $trim,
			'waste' => $junk,
			'uom' => 'gm',
			'harvested_at' => _date('%Y-%m-%d', $_SERVER['REQUEST_TIME']),
		);
		if (!empty($this->_batch_h)) {
			$data['global_harvest_batch_id'] = $this->_batch_h['guid'];
		}

		foreach ($this->_plant_list as $p) {
			$data['global_plant_ids'][] = array(
				'global_plant_id' => $p,
			);
		}
		$data['qty_harvest'] = count($this->_plant_list);

		/*
			Their System also Submits:
			Batch Fields
			waste	0
			flower_wet_weight	0
			flower_dry_weight	0
			other_wet_weight	0
			other_dry_weight	0
			harvested_at
			harvested_end_at
		*/

		$res = $this->_client->call('POST', '/plants/harvest_plants', $data);
		return $res;
	}

	function wet($buds, $trim=0, $junk=0)
	{
		$arg = array(
			'global_area_id' => $this->_section, // @todo Errors from System show this is required but the documentation says it's not
			'global_flower_area_id' => $this->_section,
			'global_other_area_id' => $this->_section,
			'global_harvest_batch_id' => null,
			'global_plant_ids' => array(),
			'qty_harvest' => sprintf('%0.2f', $buds + $trim),
			'flower_wet_weight' => sprintf('%0.2f', $buds),
			'other_wet_weight' => sprintf('%0.2f', $trim),
			// 'waste' => sprintf('%0.2f', $junk), // removed in 1375
			'uom' => 'gm',
			'harvested_at' => date('Y-m-d'),
		);

		foreach ($this->_plant_list as $p) {
			$arg['global_plant_ids'][] = array(
				'global_plant_id' => $p,
			);
		}

		$res = $this->_client->call('POST', '/plants/harvest_plants', $arg);

		return $res;
	}

	/**
		A Dry Collect Simply Updates the Batch
	*/
	function dry_1300($buds, $trim=0, $junk=0)
	{
		// For Version <= 1.37.5
		$buds = floatval($buds);
		$trim = floatval($trim);
		$junk = floatval($junk);

		$arg = array(
			'global_id' => $this->_batch_h['guid'],
			'harvested_end_at' => strftime('%Y-%m-%d'),
			'harvest_stage' => 'cure',
			'flower_dry_weight' => $buds,
			'other_dry_weight' => $trim,
			'qty_cure' => $buds + $trim,
		);
		$res = $this->_client->call('POST', '/batches/update', $arg);
		return $res;
	}

	function dry($a=0, $b=0)
	{
		$arg = array(
			'global_batch_id' => $this->_batch_h['guid'],
			'global_flower_area_id' => $this->_section,
			'flower_dry_weight' => sprintf('%0.2f', $a),
			'flower_waste' => 0, // sprintf('%0.2f', $buds_w),
			'global_other_area_id' => $this->_section,
			'other_dry_weight' => sprintf('%0.2f', $b),
			'other_waste' => 0, // sprintf('%0.2f', $trim_w),
		);
		// var_dump($arg);
		$res = $this->_client->call('POST', '/batches/cure_lot', $arg);
		return $res;
	}

	function waste($w, $uom)
	{
	}
}
