<?php
/**
 * License Adapter
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

namespace OpenTHC\CRE\OpenTHC;

class License extends Base
{
	protected $_path = '/license';

	/**
	 *
	 */
	function ping(string $oid)
	{
		$url = sprintf('/%s/%s', $this->_path, rawurlencode($oid));
		$res = $this->_cre->head($url);
		return $res;
	}

}
