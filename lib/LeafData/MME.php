<?php
/**
	A License in the LeafData World
*/

namespace OpenTHC\CRE\LeafData;

class MME extends \OpenTHC\CRE\LeafData\Base
{
	protected $_path = '/mmes';

	/**
		@param $x the License GUID
	*/
	function one($x)
	{
		$res = $this->_client->call('GET', sprintf('%s/%s', $this->_path, $x));
		return $res;
	}

	function create($x)
	{
		/*
		{"mme" :[{
			"name": "Simpsons Cultivator",
			"type": "cultivator",
			"code": "C999",
			"certificate_number": "12345",
			"address1": "742 Evergreen Terrace",
			"city": "Springfield",
			"state_code": "KY",
			"postal_code": "12345"
		}]}*/
		$res = $this->_client->call('POST', $this->_path, $x);
		return $res;
	}

	function update($x)
	{
		$res = $this->_client->call('POST', sprintf('%s/update', $this->_path), $x);
		return $res;
	}

}
