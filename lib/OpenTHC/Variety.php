<?php
/**
 * Variety Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class Variety extends Base
{
	protected $_path = '/variety';

	function single($oid)
	{
		$url = sprintf('/variety/%s', rawurlencode($oid));
		$res = $this->_cre->get($url);
		return $res;
	}

	/**
	 * Delete the Variety
	 * @param [type] $oid [description]
	 * @param [type] $arg [description]
	 * @return [type] [description]
	 */
	function delete($oid, $arg=null)
	{
		$url = sprintf('/variety/%s', $oid);
		$ret = $this->_cre->delete($url);
		return $ret;
	}

	function sync($oid, $msg)
	{
		$res = $this->single($oid);

		if (200 === $res['code']) {
			$rec = $res['data'];
		}

		if (empty($rec['id'])) {
			syslog(LOG_ERR, "Variety '{$rec['name']}' missing global ID");
			return(null);
		}

		$S = Variety::findByGUID($rec['id']);
		if (empty($S['id'])) {
			$S = new Variety();
			$S['guid'] = $rec['id'];
		}

		// Skip if Known
		$hashA = $S['hash'];
		$hashB = sha1(json_encode($rec));
		if ($hashA == $hashB) {
		// 	// syslog(LOG_DEBUG, "Product $hashA == $hashB");
			return($S);
		}

		$L = $this->_cre->getLicense();
		$S['license_id'] = $L['id'];
		$S['name'] = $rec['name'];
		$S->save();

		return $S;
	}
}
