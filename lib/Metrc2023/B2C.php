<?php
/**
 * B2C Transactions Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Metrc2023;

class B2C extends \OpenTHC\CRE\Metrc2023\Base
{
	protected $_path = '/sales/v2/receipts';

	function create($obj)
	{
		$url = $this->_client->_make_url($this->_path);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
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

	function customers($arg=null)
	{
		$url = '/sales/v2/customertypes';
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

	function patientRegistrationLocations($arg=null)
	{
		$url = '/sales/v2/patientregistration/locations';
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}
}
