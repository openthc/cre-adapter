<?php
/**
 * LeafData Variety API Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\LeafData;

class Variety extends \OpenTHC\CRE\LeafData\Base
{
	protected $_path = '/strains';


	/**
		@override
		Had to Over-Ride this cause Variety doesn't filter in LeafData
	*/
	function single($x)
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

	/**
		Sync this Object
	*/
	function sync($x, $m)
	{
		$rls = new RBE_LeafData_Sync($this->_client);
		$rlsx = new RBE_LeafData_Sync_Variety($rls, $this->_client);
		$x = $this->single($x);
		$r = $rlsx->single($x, $m);
		return $r;
	}

}
