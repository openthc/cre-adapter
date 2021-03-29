<?php
/**
 * B2C Transactions Interface
 */

namespace OpenTHC\CRE\Metrc;

class B2C extends \OpenTHC\CRE\Metrc\Base
{
	function create($obj)
	{
		$url = $this->_client->_make_url('/sales/v1/receipts');
		$req = $this->_client->_curl_init($url);
		$arg = [ $arg ];
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

	/**
	 * Delete Strain
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function delete($id)
	{
		$url = sprintf('/sales/v1/receipts/%s', $id);
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
		$url = sprintf('/sales/v1/receipts/%s', $q);
		$url = rtrim($url, ' /');
		$url = $this->_client->_make_url($url);

		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);

		return $res;

	}

	function update($arg)
	{
		$url = $this->_client->_make_url('/sales/v1/receipts');
		$req = $this->_client->_curl_init($url);
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, 'PUT');
		$res = $this->_client->_curl_exec($req, [ $arg ]);
		return $res;
	}

}
