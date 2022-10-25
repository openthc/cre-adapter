<?php
/**
 * License Adapter
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class License extends Base
{
	protected $_path = '/license';

	function ping($id)
	{
		$res = $this->_cre->get(sprintf('/license/%s', $id));
		return $res;
	}

}
