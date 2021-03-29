<?php
/**
 * METRC Lab Result
 */

namespace OpenTHC\CRE\Metrc;

class Lab_Result extends \OpenTHC\CRE\Metrc\Base
{
	protected $_path = '/labtests/v1';

	function create($obj)
	{
		$url = $this->_client->_make_url($this->_path . '/record');
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;
	}

	function search($pkg)
	{
		$arg = [
			'packageId' => $pkg
		];
		$url = $this->_client->_make_url($this->_path . '/results', $arg);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);

		return $res;

	}

}
