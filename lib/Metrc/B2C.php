<?php
/**
 * B2C Transactions Interface
 */

namespace OpenTHC\CRE\Metrc;

class B2C extends \OpenTHC\CRE\Metrc\Base
{
	protected $_path = '/sales/v1/receipts';

	function create($obj)
	{
		$url = $this->_client->_make_url($this->_path);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

	/**
	 * Delete B2C Sale
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

	/**
	 *
	 */
	function search($arg=null)
	{
		if (empty($arg)) {
			$arg = 'active';
		}

		$url = sprintf('/%s/%s', $this->_path, $arg);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);

		return $res;

	}

	function update($arg)
	{
		$url = $this->_client->_make_url($this->_path);
		$req = $this->_client->_curl_init($url);
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, 'PUT');
		$res = $this->_client->_curl_exec($req, [ $arg ]);
		return $res;
	}

}
