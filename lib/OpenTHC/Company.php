<?php
/**
 * Company Adapter
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class Company extends Base
{
	private $_api_path = '/config/company';

	function search($filter=null)
	{
		$url = $this->_api_path;
		if (!empty($filter)) {
			$url.= '?' . http_build_query($filter);
		}
		$res = $this->_rbe->get($url);
		return $res;

	}

	function create($obj)
	{
		$url = $this->_api_path;
		$res = $this->_cre->post($url, $obj);
		return $res;
	}

	function ping($id)
	{
		$url = sprintf('%s/%s', $this->_api_path, $id);
		$res = $this->_cre->head($url);
		return $res;
	}

}
