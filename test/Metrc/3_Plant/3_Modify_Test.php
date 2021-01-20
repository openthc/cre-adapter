<?php
/**
 * Plant Modify
 */

namespace Test\Metrc\Plant;

class Modify_Test extends \Test\OpenTHC_Metrc_Test
{
	/**
	 * Get a random Plant by the GUID suffix only
	 */
	public function testGetPlantByShortGUID()
	{}

	/**
	 * Do a simple Modify call to a random Plant
	 */
	public function testSimplePlantModify()
	{}

	public function testSimplePlantMove()
	{
		// From CO Eval
		// $res = $rbe->plantbatchCreatePlantings(array(array(
		// 	'Name' => sprintf('Plant Batch %s - Alpha', $code),
		// 	'Type' => 'Seed',
		// 	'Count' => 3,
		// 	'Strain' => 'Strain 78b705e8 - Alpha',
		// 	'ActualDate' => date('Y-m-d'),
		// )));
		// var_dump($res);

		// Plant Move Room
		// $res = $rbe->plant()->move([[
		// 	'Id' => '305001',
		// 	'Label' => 'ABCDEF012345670000016303',
		// 	'Room' => 'Eval 78b705e8 - Rename - Bravo',
		// 	'ActualDate' => date('Y-m-d'),
		// ]]);
		// var_dump($res);
	}

	public function testSimplePlantDestroy()
	{
		// Plant Destroy
		// $res = $rbe->plant()->destroy([[
		// 	'Id' => '302801',
		// 	'Label' => '1A4FFFC303D7E32000001838',
		// 	'ReasonNote' => 'Destroy a plant using: POST /plants/v1/destroyplants',
		// 	'ActualDate' => date('Y-m-d'),
		// ]]);
		// var_dump($res);
	}

	/**
	 * Do a simple Modify call to a random Plant
	 * Only pass the PL number of the object and not the full guid with a license
	*/
	public function testPlantModifyPartialGUID()
	{}

}
