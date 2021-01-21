<?php
/**
 * Section Interface
 * @todo Reset Sections cause Metrc doesn't provide information about which sections are real?
 * @note you GEt a 403 Error on Sections when query as a Processor
 * @note METRC only has 'Plant' type sections
 */

namespace OpenTHC\CRE\Metrc;

class Section extends \OpenTHC\CRE\Metrc\Base
{
	// protected $_path = 'rooms'; // Used to be called this
	protected $_path = '/locations/v1';

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
		return $res;
	}

	/**
	 * [search description]
	 * @return [type] [description]
	 */
	function search()
	{
		$url = sprintf('%s/active', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
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
		return $res;
	}

}
