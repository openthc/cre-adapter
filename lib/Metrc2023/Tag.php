<?php
/**
 * RFID Tags Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Metrc2023;

use Edoceo\Radix\DB\SQL;

class Tag extends \OpenTHC\CRE\Metrc2023\Base
{
	protected $_path = '/tags/v2';

	function search($type='PLANT')
	{
		$url = '';
		switch ($type) {
			case 'INVENTORY':
				$url = sprintf('%s/package/available', $this->_path);
				break;
			case 'PLANT':
				$url = sprintf('%s/plant/available', $this->_path);
				break;
			case 'STAGED':
				$url = sprintf('%s/staged', $this->_path);
				break;
			default:
				throw new \Exception('Invalid Tag Type [CMT-030]');
		}

		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

}
