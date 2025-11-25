<?php
/**
 * Crop Collect Interface - Harvest and Cure
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Metrc2023;

class Crop_Collect extends \OpenTHC\CRE\Metrc2023\Base
{
	function harvest($arg)
	{
		$url = $this->_client->_make_url('/plants/v2/harvest');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, $arg, 'PUT');
		return $res;
	}

	function manicure($arg)
	{
		$url = $this->_client->_make_url('/plants/v2/manicure');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, $arg, 'PUT');
		return $res;
	}

	/**
		@param $hid Harvest ID
		@param $dts Date Stamp YYYY-MM-DD
	*/
	function harvestFinish($arg)
	{
		$url = $this->_client->_make_url('/harvests/v2/finish');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $arg ], 'PUT');
		return $res;
	}

	function harvestFinishUndo($arg)
	{
		$url = $this->_client->_make_url('/harvests/v2/unfinish');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, $arg);
		return $res;
	}

	function harvestPackageCreate($arg)
	{
		$url = $this->_client->_make_url('/harvests/v2/packages');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $arg ]);
		return $res;
	}

	function harvestWasteRemove($arg)
	{
		$url = $this->_client->_make_url('/harvests/v2/waste');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, $arg);
		return $res;
	}


	/**
	 * Search All Plant Collections
	 * @param [type] $stat [description]
	 * @return [type] [description]
	 */
	function search($stat=null)
	{
		//switch ($a) {
		//case 'active':
		////case 'finish':
		//case 'onhold':
		//case 'inactive':
		////case 'packageharvestedproducts':
		////case 'removewastefromharvests':
		////case 'unfinish':
		//	$url = '/harvests/v2/' . $a;
		//	break;
		//}

		if (empty($stat)) {
			$stat = 'active';
		}

		$url = sprintf('/harvests/v2/%s', $stat);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		$res = $this->formatResponse($res);
		return $res;

	}

}
