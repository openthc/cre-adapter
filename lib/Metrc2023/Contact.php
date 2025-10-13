<?php
/**
 * Contact/Patient Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Metrc2023;

class Contact extends \OpenTHC\CRE\Metrc2023\Base
{
	const TYPE_CLIENT = 'CLIENT';
	const TYPE_EMPLOYEE = 'EMPLOYEE';
	const TYPE_PATIENT = 'PATIENT';

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
	function search(string $type='EMPLOYEE')
	{
		$ret = [];

		switch ($type) {
			case self::TYPE_EMPLOYEE:
				$url = $this->_client->_make_url('/employees/v2/');
				$req = $this->_client->_curl_init($url);
				$res = $this->_client->_curl_exec($req);
				$res = $this->formatResponse($res);
				break;
			case self::TYPE_PATIENT:
				$url = $this->_client->_make_url('/patients/v2/active');
				$req = $this->_client->_curl_init($url);
				$res = $this->_client->_curl_exec($req);
				$res = $this->formatResponse($res);
				break;
		}

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

		return $res;

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
