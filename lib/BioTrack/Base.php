<?php
/**
 * BioTrack Base Class
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

namespace OpenTHC\CRE\BioTrack;

class Base
{
	protected $_client;
	protected $_path = '';

	function __construct($c)
	{
		$this->_client = $c;
	}

}
