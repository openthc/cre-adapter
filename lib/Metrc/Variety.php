<?php
/**
 * Variety Interface
 */

namespace OpenTHC\CRE\Metrc;

class Variety extends \OpenTHC\CRE\Metrc\Base
{
	protected $_path = '/strains/v1';

	/**
	 * Delete Variety
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

	// @param $id ID of Variety to get, default 'active'
	function search($arg=null)
	{
		if (empty($arg)) {
			$arg = 'active';
		}

		$url = sprintf('%s/%s', $this->_path, $q);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);

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
