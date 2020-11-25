<?php
/**
 * A License in the LeafData World
 */

namespace OpenTHC\CRE\LeafData;

class License extends \OpenTHC\CRE\LeafData\Base
{
	protected $_path = '/mmes';

	/**
	 * Override for License Data
	 * This one doesn't page, and puts all the objects at the TOP level
	 * Doesn't have the 'meta' and 'data' type responses like the others
	 * So we fake it.
	 */
	function search($arg=null)
	{
		$res = parent::all($arg);
		return [
			'code' => $res['code'],
			'data' => [
				'total' => -1,
				'per_page' => -1,
				'current_page' => -1,
				'last_page' => -1,
				'next_page_url' => '',
				'prev_page_url' => '',
				'from' => -1,
				'to' => -1,
				'data' => $res['data'],
			],
			'meta' => [],
		];
	}

	/**
		@param $x the License GUID
	*/
	function single($x)
	{
		$url = sprintf('%s/%s', $this->_path, $x);
		$res = $this->_client->call('GET', $url);
		return $res;
	}

	function create($x)
	{
		$res = $this->_client->call('POST', '/mmes', $x);
		return $res;
	}

	function update($x)
	{
		$res = $this->_client->call('POST', '/mmes/update', $x);
		return $res;
	}

}
