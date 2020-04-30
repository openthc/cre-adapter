<?php
/**
 * Franwell / Metrc Interface for Colorado
 */

namespace OpenTHC\CRE\Adapter\Metrc;

class CO extends \OpenTHC\CRE\Adapter\Metrc
{
	protected $_api_base = 'https://api-co.metrc.com';

	protected $_epoch = '2014-01-01';

	function setTestMode()
	{
		$this->_api_base = 'https://sandbox-api-co.metrc.com';
		$this->_api_host = null;
	}
}
