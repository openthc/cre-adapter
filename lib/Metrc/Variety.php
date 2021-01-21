<?php
/**
 * Variety Interface
 */

namespace OpenTHC\CRE\Metrc;

class Variety extends \OpenTHC\CRE\Metrc\Base
{
	protected $_path = '/strains/v1';

	/**
	 * Delete Strain
	 * @param  [type] $id [description]
	 * @return [type]     [description]
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

	// function strainList()
	// @param $id ID of Strain to get, default 'active'
	function search($q=null)
	{
		if (empty($q)) {
			$q = 'active';
		}

		$url = sprintf('%s/%s', $this->_path, $q);
		$url = $this->_client->_make_url($url);

		$x = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($x);

		return $res;

	}

	function update($obj)
	{
		$url = sprintf('/%s/update', $this->_path);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

}
