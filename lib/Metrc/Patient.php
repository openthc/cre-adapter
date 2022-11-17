<?php
/**
 * Patient Interface
 */

namespace OpenTHC\CRE\Metrc;

class Patient extends \OpenTHC\CRE\Metrc\Base
{
	protected $_path = '/patients/v1';

	function create($obj) {
		$url = sprintf("%s/%s", $this->_path, 'add');
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req, [ $obj ]);
		return $res;

	}

	function delete($obj) {
		// DELETE /patients/v1/{id}
	}
	
	function search($arg=null) {
		if (empty($arg)) {
			$arg = 'active';
		}

		$url = sprintf('/%s/%s', $this->_path, $arg);
		$url = $this->_client->_make_url($url);
		$req = $this->_client->_curl_init($url);
		$res = $this->_client->_curl_exec($req);

		return $res;
	}
	
	function update($obj) {
		// POST /patients/v1/update
	}
}
