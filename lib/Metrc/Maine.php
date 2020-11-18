<?php
/**
 * Franwell / Metrc Interface for Maine
 */

namespace OpenTHC\CRE\Metrc;

class Maine extends \OpenTHC\CRE\Metrc
{
	protected $_api_base = 'https://api-me.metrc.com';

	protected $_epoch = '2020-01-01';

	function setTestMode()
	{
		$this->_api_base = 'https://sandbox-api-md.metrc.com';
		$this->_api_host = null;

		$this->_api_base = 'https://pipe.openthc.com/stem/metrc/sandbox-api-me.metrc.com';
	}
}
