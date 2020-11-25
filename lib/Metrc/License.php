<?php
/**
 * License Interface
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

	function sync()
	{
		$res = $this->search();
		foreach ($res['data'] as $rec) {
			$Lic = License::findByGUID($rec['License']['Number']);
			if (empty($Lic['id'])) {
				$Lic = new License();
				$Lic['code'] = $rec['License']['Number'];
				$Lic['guid'] = $rec['License']['Number'];
			}
			$Lic['hash'] = RBE_base::recHash($rec);
			$Lic['name'] = $rec['Name'];
			$Lic['type'] = $rec['License']['LicenseType'];
			$Lic['meta'] = json_encode($rec);
			$Lic->setFlag(License::FLAG_MINE);
			$Lic->save();
		}
	}

}
