<?php
/**
 * Implementation for B2C Transactions
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class B2C extends Base
{
	function all($filter=null)
	{
		$url = '/retail';
		if (!empty($filter)) {
			$url.= '?' . http_build_query($filter);
		}
		$res = $this->_cre->get($url);
		return $res;
	}

}
