<?php
/**
 * Franwell / Metrc Interface for Alaska
*/

namespace OpenTHC\CRE\Adapter\Metrc;

class AK extends \OpenTHC\CRE\Adapter\Metrc
{
	protected $_api_base = 'https://api-ak.metrc.com';

	function setTestMode()
	{
		$this->_api_base = 'https://sandbox-api-or.metrc.com/';

		$this->_api_base = 'https://pipe.openthc.com/stem/metrc/sandbox-api-or.metrc.com';

	}
}
