<?php
/**
 * Disposal
 */

namespace OpenTHC\CRE\LeafData;

class Disposal extends \OpenTHC\CRE\LeafData\Base
{
	protected $_path = '/disposals';

	function create($x)
	{
		$arg = array('disposal' => array($x));
		$res = $this->_client->call('POST', '/disposals', $arg);
		return $res;
	}

	function update($obj)
	{
		$arg = [ 'disposal' => [ $obj ] ];
		$res = $this->_client->call('POST', sprintf('%s/update', $this->_path), $arg);
		return $res;
	}

	function confirm($x)
	{
		$arg = array(
			'global_id' => $x,
			'disposal_at' => _date(RBE_LeafData::FORMAT_DATE_TIME, $_SERVER['REQUEST_TIME'], 'America/Los_Angeles'),
		);
		$res = $this->_client->call('POST', '/disposals/dispose', $arg);
		return $res;
	}

}
