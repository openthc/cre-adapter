<?php
/**
 * BioTrackTHC Interface - HI
 * @see https://www.biotrack.com/hawaii/
 */

namespace OpenTHC\CRE\BioTrack;

class Hawaii extends \OpenTHC\CRE\BioTrack\Base
{
	protected $_api_base = 'https://hicsts.hawaii.gov/serverjson.asp';

	function getObjectList()
	{
		$obj_list = parent::getObjectList();

		unset($obj_list['id_preassign']);
		unset($obj_list['inventory_sample']);
		unset($obj_list['third_party_transporter']);

		return $obj_list;
	}

}
