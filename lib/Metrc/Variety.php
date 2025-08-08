<?php
/**
 * Variety Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Metrc;

class Variety extends \OpenTHC\CRE\Metrc\Base
{
	protected $_path = '/strains/v1';

	// @param $id ID of Variety to get, default 'active' or 'inactive'
	function search($arg=null)
	{
		if (empty($arg)) {
			$arg = 'active';
		}

		$url = sprintf('%s/%s', $this->_path, $arg);
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
