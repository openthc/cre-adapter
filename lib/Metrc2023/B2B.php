<?php
/**
 * B2B Transactions Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Metrc2023;

class B2B extends \OpenTHC\CRE\Metrc2023\Base
{
	function create($obj)
	{
		$url = null;

		if (empty($obj['ShipperLicenseNumber']) && !empty($obj['Name'])) {
			// It's a Template
			$url = $this->_client->_make_url('/transfers/v2/templates');
		} else {
			// It's an INCOMING
			$url = $this->_client->_make_url('/transfers/v2/external/incoming');
		}

		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);

		return $res;

	}

	/**
	 *
	 */
	function single($oid)
	{
		$ret = array();
		$x = $this->deliveries($oid);
		$ret['deliveries'] = $x['data'];
		if (count($ret['deliveries']) > 1) {
			throw new \Exception('Cannot Handle METRC Multistop [RMT-021]');
		}
		foreach ($ret['deliveries'] as $d) {
			$x = $this->packages($d['Id']);
			$ret['packages'] = $x['data'];
		}
		return $ret;
	}

	/**
	 * Find the Outgoing Transfers
	 */
	function outgoing()
	{
		$url = '/transfers/v2/outgoing';
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		$res = $this->formatResponse($res);
		return $ret;
	}

	/**
	 * Find the Incoming Transfers
	 */
	function incoming()
	{
		$url = '/transfers/v2/incoming';
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		$res = $this->formatResponse($res);
		return $ret;
	}

	/**
	 * Find the Incoming Transfers
	 */
	function rejected()
	{
		$url = '/transfers/v2/rejected';
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		$res = $this->formatResponse($res);
		return $ret;
	}

	/**
	 * Find the Transfer Templates
	 */
	function templates()
	{
		$url = '/transfers/v2/templates';
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

	/**
		Find the Transfer Types
	*/
	function getTypeList()
	{
		$url = '/transfers/v2/types';
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		$res = $this->formatResponse($res);
		return $res;
	}

	/**
	 * Deliveries for a Specific Transfer
	 * @param $oid The Transfer ID
	*/
	function deliveries($oid)
	{
		$url = '/transfers/v2/%s/deliveries';
		$url = sprintf($url, $oid);

		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

	/**
		Find the Incoming Transfers
		@param $oid Delivery Identifier
	*/
	function packages($oid)
	{
		$url = '/transfers/v2/delivery/%s/packages';
		$url = sprintf($url, $oid);

		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);
		return $res;
	}

}
