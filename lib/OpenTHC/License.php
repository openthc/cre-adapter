<?php
/**
 * License Adapter
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class License extends Base
{
	function search($filter=null)
	{
		$url = '/license';

		if (!empty($filter)) {
			$url.= '?' . http_build_query($filter);
		}

		$res = $this->_cre->get($url);
		return $res;
	}

	function ping($id)
	{
		$res = $this->_cre->get(sprintf('/license/%s', $id));
		return $res;
	}

}
