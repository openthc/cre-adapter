<?php
/**
 * Handle Batch Types
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\LeafData;

class Batch extends \OpenTHC\CRE\LeafData\Base
{
	protected $_path = '/batches';

	function create($x)
	{
		$arg = array('batch' => array(0 => $x));
		$res = $this->_client->call('POST', '/batches', $arg);
		return $res;
	}

	function delete($x)
	{
		$res = $this->_client->call('DELETE', sprintf('/batches/%s', $x));
		return $res;
	}

	function update($x)
	{
		if ('plant' == $x['type']) {
			if (empty($x['origin'])) {
				$x['origin'] = 'plant';
			// 	throw new Exception('Missing "origin" [RLB#027]');
			}
			if (!isset($x['num_plants'])) {
				throw new Exception('Missing "num_plants" [RLB#030]');
			}
		}

		$arg = array('batch' => $x);
		$res = $this->_client->call('POST', '/batches/update', $arg);
		return $res;
	}

	function finish($arg)
	{
		$res = $this->_client->call('POST', '/batches/finish_lot', $arg);
		return $res;
	}

	/**
		Over-Ride

		If the parent query fails it maybe because the batch is closed.
		When the batch is closed, the Global ID filter fails and we re-attempt with closed-status
		The batch is questions is still visible in the List request
		/djb 20181124

		@param $x The GUID to Fetch
	*/
	function single($x)
	{
		$res = parent::single($x);
		if (!empty($res)) {
			return $res;
		}

		$arg = http_build_query(array(
			'f_global_id' => $x,
			'f_status' => 'closed',
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
		Sync this Object
	*/
	function sync($x, $m)
	{
		$rls = new RBE_LeafData_Sync($this->_client);
		$rlsx = new RBE_LeafData_Sync_Batch($rls, $this->_client);
		$o = $this->single($x);
		$r = $rlsx->single($o, $m);
		return $r;
	}

}
