<?php
/**
 * LeafData Lab Result Interface
 */

class RBE_LeafData_Lab_Result extends RBE_LeafData_Base
{
	protected $_path = '/lab_results';

	function create($x)
	{
		$arg = array('lab_result' => array(0 => $x));
		$res = $this->_client->call('POST', '/lab_results', $arg);
		return $res;
	}

	function delete($x)
	{
		$res = $this->_client->call('DELETE', sprintf('/lab_results/%s', $x));
		return $res;
	}

	function update($x)
	{
		$arg = array('lab_result' => $x);
		$res = $this->_client->call('POST', '/lab_results/update', $arg);
		return $res;
	}

	function single($x)
	{
		$arg = http_build_query(array(
			'f_global_id' => $x,
		));
		$url = sprintf('%s?%s', $this->_path, $arg);
		$res = $this->_client->call('GET', $url);
		if ('success' == $res['status']) {
			$res = $res['result'];
			if (!empty($res['data'])) {
				$res = $res['data'];
				if (is_array($res)) {
					if (1 == count($res)) {
						return $res[0];
					}
				}
			}
		}
		return null;
	}

	/**
	 * Sync this Object
	 */
	function sync($x, $m)
	{
		$o = $this->single($x);
		$rls = new RBE_LeafData_Sync($this->_client);
		$rlsx = new RBE_LeafData_Sync_Lab_Result($rls, $this->_client);
		$r = $rlsx->single($o, $m);
		return $r;
	}

}
