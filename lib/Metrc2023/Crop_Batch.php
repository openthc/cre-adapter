<?php
/**
 * Crop Batch
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Metrc2023;

class Crop_Batch extends \OpenTHC\CRE\Metrc2023\Base
{
	protected $_path = '/plantbatches/v2';

	function change($obj)
	{
		$url = sprintf('%s/changegrowthphase', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

	function create($obj)
	{
		$url = sprintf('%s/createplantings', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

	function destroy($obj)
	{
		$url = sprintf('%s/destroy', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

	/**
	 * Taking Clone or Seed into Package for Sale
	 * @param $obj Data Array
	 */
	function package($obj)
	{
		$url = sprintf('%s/createpackages', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		$res = $this->formatResponse($res);
		return $res;
	}

	/**
	 * Search All Plant Batches
	 * @param [type] $stat Status or ID of Batch to Get
	 */
	function search($stat=null)
	{
		if (empty($stat)) {
			$stat = 'active';
		}

		$url = sprintf('/plantbatches/v2/%s', $stat);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		$res = $this->formatResponse($res);
		return $res;

	}

}
