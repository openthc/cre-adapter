<?php
/**
 * License Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Metrc2023;

class License extends \OpenTHC\CRE\Metrc2023\Base
{
	protected $_path = '/facilities/v2/';

	/**
	 * Search All Plant Collections
	 * @param [type] $stat [description]
	 * @return [type] [description]
	 */
	function search($stat=null)
	{
		$req = $this->_client->_curl_init($this->_path);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

}
