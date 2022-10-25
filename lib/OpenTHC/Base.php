<?php
/**
 * Base Class for OpenTHC Data things
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class Base
{
	protected $_cre;

	function __construct($cre)
	{
		$this->_cre = $cre;
	}

	function create($oid)
	{
		throw new \Exception('Not Implemented [ROB#017]');
	}

	function delete($oid, $arg=null)
	{
		throw new \Exception('Not Implemented [ROB#022]');
	}

	function update($oid, $obj)
	{
		throw new \Exception('Not Implemented [ROB#027]');
	}

}
