<?php
/**
 * LeafData Section
 */

namespace OpenTHC\CRE\LeafData;

class Section extends \OpenTHC\CRE\LeafData\Base
{
	protected $_path = '/areas';

	// Section Types
//	private $_type_list = array(
//		'veg' => 'Vegatative',
//		'propagation' => 'propagation',
//		'flower' => 'flower',
//		'sales floor' => 'sales floor',
//		'storage' => 'storage',
//		'quarantine' => 'quarantine',
//		'r&d' => 'r&d',
//	);

//	static function getTypeList()
//	{
//		$x = new self();
//		return $x->_type_list;
//	}

	/**
		Have to over-ride this one cause the f_global_id filter don't work here
		This call always returns the full list
	*/
	function single($x)
	{
		$url = $this->_path; // sprintf('%s?f_global_id=%s', $this->_path, $x);
		$res = $this->_client->call('GET', $url);
		if ('success' == $res['status']) {
			$res = $res['result'];
			if (!empty($res['data'])) {
				$res = $res['data'];
				if (is_array($res)) {
					foreach ($res as $rec) {
						if ($x == $rec['global_id']) {
							return $rec;
						}
					}
				}
			}
		}
		return null;
	}

	function create($x)
	{
		switch ($x['type']) {
		case 'non-quarantine':
		case 'quarantine':
			// OK
			break;
		default:
			$x['type'] = 'non-quarantine';
			if (!empty($x['quarantine'])) {
				$x['type'] = 'quarantine';
				unset($x['quarantine']);
			}
			break;
		}

		$arg = array('area' => array(0 => $x));
		$res = $this->_client->call('POST', '/areas', $arg);
		if ('success' == $res['status']) {
			if (is_array($res['result']) && (1 == count($res['result']))) {
				$res['result'] = $res['result'][0];
			}
		}

		return $res;
	}

	function update($obj)
	{
		$arg = array('area' => $obj);
		$res = $this->_client->call('POST', sprintf('%s/update', $this->_path), $arg);
		return $res;
	}

}
