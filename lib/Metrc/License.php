<?php
/**
 * License Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Metrc;

class License extends \OpenTHC\CRE\Metrc\Base
{
	protected $_path = '/facilities/v1';

	/**
	 * Search All Plant Collections
	 * @param [type] $stat [description]
	 * @return [type] [description]
	 */
	function search($stat=null)
	{
		$req = $this->_client->_curl_init('/facilities/v1');
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

}
