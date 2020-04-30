<?php
/**
 * BioTrackTHC Interface - HI
 * @see https://www.biotrack.com/hawaii/
 */

namespace OpenTHC\CRE\Adapter\Biotrack;

class HI extends \OpenTHC\CRE\Adapter\BioTrack
{
	protected $_api_base = 'https://hicsts.hawaii.gov/serverjson.asp';

	function listSyncObjects()
	{
		$obj_list = parent::listSyncObjects();

		unset($obj_list['id_preassign']);
		unset($obj_list['inventory_sample']);
		unset($obj_list['third_party_transporter']);

		return $obj_list;
	}

}
