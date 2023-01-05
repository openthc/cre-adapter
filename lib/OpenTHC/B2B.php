<?php
/**
 * Implementation for B2B Transactions
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class B2B extends Base
{
	protected $_path = '/b2b';

	/**
	 * Do the COMMIT thing
	 */
	function commit(string $oid)
	{
		$url = sprintf('%s/%s/commit', $this->_path, $oid);
		$arg = [
			'a' => 'commit',
		];
		$res = $this->_cre->post($url, $arg);
		return $res;
	}
}
