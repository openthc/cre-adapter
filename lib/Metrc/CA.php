<?php
/**
 * Franwell / Metrc Interface for California
 */

namespace OpenTHC\CRE\Adapter\Metrc;

class CA extends \OpenTHC\CRE\Adapter\Metrc
{
	protected $_api_base = 'https://api-ca.metrc.com';

	protected $_epoch = '2019-01-01';

	function setTestMode()
	{
		$this->_api_base = 'https://sandbox-api-ca.metrc.com';
		$this->_api_host = null;

		//$this->_api_base = 'https://pipe.openthc.com/stem/metrc/sandbox-api-ca.metrc.com';
	}
}
