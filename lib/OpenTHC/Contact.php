<?php
/**
 * Contact Adapter
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class Contact extends Base
{
	function search($filter=null)
	{
		$url = '/contact';

		if (!empty($filter)) {
			$url.= '?' . http_build_query($filter);
		}

		$res = $this->_cre->get($url);
		return $res;
	}
}
