<?php
/**
 * BioTrack Base Class
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
