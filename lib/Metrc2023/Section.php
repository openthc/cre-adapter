<?php
/**
 * Section Interface
 *
 * SPDX-License-Identifier: MIT
 *
 * @todo Reset Sections cause Metrc doesn't provide information about which sections are real?
 * @note you GEt a 403 Error on Sections when query as a Processor
 * @note METRC only has 'Plant' type sections
 */

namespace OpenTHC\CRE\Metrc2023;

class Section extends \OpenTHC\CRE\Metrc2023\Base
{
	protected $_path = '/locations/v2';

	function getTypeList()
	{
		$url = sprintf('%s/types', $this->_path);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		$res = $this->formatResponse($res);
		return $res;
	}

	/**
	 * Delete a Section
	 * @param [type] $id [description]
	 * @return [type] [description]
	 */
	function delete($id)
	{
		$url = sprintf('%s/%s', $this->_path, $id);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, 'DELETE');
		$res = $this->_client->_curl_exec($req);
		$res = $this->formatResponse($res);
		return $res;
	}

	/**
	 * [search description]
	 * @return [type] [description]
	 */
	function search($arg=null)
	{
		$url = sprintf('%s/active', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		$res = $this->formatResponse($res);
		return $res;

	}

	/**
	 * Get Single Section
	 * @param [type] $r [description]
	 * @return [type] [description]
	 */
	function single($x)
	{
		$url = sprintf('%s/%s', $this->_path, $x);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		$res = $this->formatResponse($res);
		return $res;
	}

}
