<?php
/**
 * Contact Adapter
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

namespace OpenTHC\CRE\OpenTHC;

class Contact extends Base
{
	protected $_path = '/contact';

	function sync()
	{
		$res = $this->_cre->get($url, $obj);
	}
}
