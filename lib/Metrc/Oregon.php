<?php
/**
 * Franwell / Metrc Interface for Oregon
 * @see http://www.oregon.gov/olcc/marijuana/Documents/BusinessReadinessGuide_RecreationalMarijuana.pdf
 * @see http://media.wix.com/ugd/73c73e_e930c713c46f4c08b07e7c2e2d29a2b5.pdf
*/

namespace OpenTHC\CRE\Metrc;

class Oregon extends \OpenTHC\CRE\Metrc
{
	protected $_api_base = 'https://api-or.metrc.com';

	protected $_epoch = '2017-01-01';

	function setTestMode()
	{
		$this->_api_base = 'https://sandbox-api-or.metrc.com';
		$this->_api_host = null;

		//$this->_api_base = 'https://pipe.openthc.com/stem/metrc/sandbox-api-or.metrc.com';
	}
}
