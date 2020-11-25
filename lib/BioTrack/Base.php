<?php
/**
 * BioTrack Base Class
 */

namespace OpenTHC\CRE\BioTrack;

class RBE_BioTrack_Base
{
	protected $_client;
	protected $_path = '';

	function __construct($c)
	{
		$this->_client = $c;
	}

}
