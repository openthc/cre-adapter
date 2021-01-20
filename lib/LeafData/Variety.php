<?php
/**
 * LeafData Strain API Interface
 */

namespace OpenTHC\CRE\LeafData;

class Variety extends \OpenTHC\CRE\LeafData\Base
{
	protected $_path = '/strains';


	/**
		@override
		Had to Over-Ride this cause Strain doesn't filter in LeafData
	*/
	function one($x)
	{
		$arg = http_build_query(array(
			'f_global_id' => $x,
		));
		$url = sprintf('%s?%s', $this->_path, $arg);
		$res = $this->_client->call('GET', $url);
		//var_dump($res);
		if ('success' == $res['status']) {
			$res = $res['result'];
			if (!empty($res['data'])) {
				$res = $res['data'];
				if (is_array($res)) {
					foreach ($res as $s) {
						if ($x == $s['global_id']) {
							return $s;
						}
					}
				}
			}
		}
	}


	function create($x)
	{
		$arg = array('strain' => array(0 => $x));
		$res = $this->_client->call('POST', '/strains', $arg);
		return $res;
	}

	function delete($x)
	{
		$res = $this->_client->call('DELETE', sprintf('/strains/%s', $x));
		return $res;
	}

	function update($x)
	{
		$arg = array('strain' => $x);
		$res = $this->_client->call('POST', '/strains/update', $arg);
		return $res;
	}

}
