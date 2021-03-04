<?php
/**
 * Contact/Patient Interface
 */

namespace OpenTHC\CRE\Metrc;

class Contact extends \OpenTHC\CRE\Metrc\Base
{
	protected $_path = '/patients/v1';

	function create($obj)
	{
		$url = $this->_client->_make_url('/patients/v1/add');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

	/**
	 * Delete a Contact
	 * @note After Delete they simply disappear from the list, there is no query for archive/delete/inactive status
	 * @param [type] $id [description]
	 * @return [type] [description]
	 */
	function delete($oid)
	{
		$url = sprintf('%s/%s', $this->_path, $oid);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, 'DELETE');
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

	function search($arg=null)
	{
		$url = $this->_client->_make_url('/patients/v1/active');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

	function update($arg)
	{
		$url = $this->_client->_make_url('/patients/v1/update');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $arg ]);
		return $res;
	}

}
