<?php
/**
 * Disposal
 */

class RBE_LeafData_Disposal extends RBE_LeafData_Base
{
	protected $_path = '/disposals';

	function confirm($x)
	{
		$arg = array(
			'global_id' => $x,
			'disposal_at' => _date(RBE_LeafData::FORMAT_DATE_TIME, $_SERVER['REQUEST_TIME'], 'America/Los_Angeles'),
		);
		$res = $this->_client->call('POST', '/disposals/dispose', $arg);
		return $res;
	}

	function create($x)
	{
		$arg = array('disposal' => array($x));
		$res = $this->_client->call('POST', '/disposals', $arg);
		return $res;
	}

	function update($x)
	{
		$res = $this->_client->call('POST', '/disposals/update', $x);
		return $res;
	}

	function delete($x)
	{
		$res = $this->_client->call('DELETE', sprintf('/disposals/%s', $x));
		return $res;
	}

	function sync($x, $m)
	{
		$rls = new RBE_LeafData_Sync($this->_client);
		$rlsx = new RBE_LeafData_Sync_Disposal($rls, $this->_client);
		$o = $this->single($x);
		$r = $rlsx->single($o, $m);
		return $r;
	}

}
