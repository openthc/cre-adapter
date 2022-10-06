<?php
/**
 * B2B Transactions Interface
 */

namespace OpenTHC\CRE\Metrc;

class B2B extends \OpenTHC\CRE\Metrc\Base
{
	function create($obj)
	{
		$url = null;

		if (empty($obj['ShipperLicenseNumber']) && !empty($obj['Name'])) {
			// It's a Template
			$url = $this->_client->_make_url('/transfers/v1/templates');
		} else {
			// It's an INCOMING
			$url = $this->_client->_make_url('/transfers/v1/external/incoming');
		}

		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);

		return $res;



	}

	function single($id)
	{
		$ret = array();
		$x = $this->deliveries($id);
		$ret['deliveries'] = $x['data'];
		if (count($ret['deliveries']) > 1) {
			throw new Exception('Cannot Handle METRC Multistop [RMT#021]');
		}
		foreach ($ret['deliveries'] as $d) {
			$x = $this->packages($d['Id']);
			$ret['packages'] = $x['data'];
		}
		return $ret;
	}

	/**
		Find the Outgoing Transfers
	*/
	function outgoing()
	{
		$url = '/transfers/v1/outgoing';
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

	/**
		Find the Incoming Transfers
	*/
	function incoming()
	{
		$url = '/transfers/v1/incoming';
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

	/**
		Find the Incoming Transfers
	*/
	function rejected()
	{
		$url = '/transfers/v1/rejected';
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}


	/**
		Find the Transfer Types
	*/
	function types()
	{
		$url = '/transfers/v1/types';
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

	/**
	 * Deliveries for a Specific Transfer
	 * @param $guid The Transfer ID
	*/
	function deliveries($guid)
	{
		$url = '/transfers/v1/%s/deliveries';
		$url = sprintf($url, $guid);

		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

	/**
		Find the Incoming Transfers
		@param $oid Delivery Identifier
	*/
	function packages($guid)
	{
		$url = '/transfers/v1/delivery/%s/packages';
		$url = sprintf($url, $guid);

		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

}
