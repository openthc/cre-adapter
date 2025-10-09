<?php
/**
 * Contact/Patient Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Metrc2023;

class Contact extends \OpenTHC\CRE\Metrc2023\Base
{
	// protected $_path = '/patients/v2';

	/**
	 *
	 */
	function create($obj)
	{
		$url = $this->_client->_make_url(sprintf('%s/add', $this->_path));
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

	/**
	 *
	 */
	function search($arg=null)
	{
		// if (empty($arg)) {
		// 	$arg = 'active';
		// }
		$ret = [];

		$url = $this->_client->_make_url('/employees/v2/');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		$res = $this->formatResponse($res);

		$url = $this->_client->_make_url('/patients/v2/active');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		$res = $this->formatResponse($res);

		switch ($res['code']) {
		case 200:
			// OK
			break;
		case 401:
			// Ignore
			break;
		default:
			throw new \Exception('Invalid Response from Contact/Patients [CMC-054]', 500);
		}
		// var_dump($res);

		return $ret;
	}

	/**
	 *
	 */
	function update($arg)
	{
		$url = $this->_client->_make_url('/patients/v2/update');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $arg ]);
		return $res;
	}

}
