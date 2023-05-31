<?php
/**
 * Contact/Patient Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Metrc;

class Contact extends \OpenTHC\CRE\Metrc\Base
{
	protected $_path = '/patients/v1';

	function create($obj)
	{
		$url = $this->_client->_make_url(sprintf('%s/add', $this->_path));
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

	function search($arg=null)
	{
		if (empty($arg)) {
			$arg = 'active';
		}

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
