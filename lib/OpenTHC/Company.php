<?php
/**
 * Company Adapter
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class Company extends Base
{
	protected $_path = '/company';

	function ping($id)
	{
		$url = sprintf('%s/%s', $this->_path, $id);
		$res = $this->_cre->head($url);
		return $res;
	}

}
