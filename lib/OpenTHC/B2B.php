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
	 * Get file attachments
	 */
	function attachment(string $oid, string $fid = null)
	{
		$url = sprintf('%s/%s/file', $this->_path, rawurlencode($oid));
		if ($fid) {
			$url = sprintf('%s/%s', $url, rawurlencode($fid));
		}
		$res = $this->_cre->get($url);
		return $res;
	}

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

	/**
	 * Get the B2B Incoming interface
	 */
	function incoming()
	{
		return new \OpenTHC\CRE\OpenTHC\B2B\Incoming($this);
	}

	/**
	 * Get the B2B Outgoing interface
	 */
	function outgoing()
	{
		return new \OpenTHC\CRE\OpenTHC\B2B\Outgoing($this);
	}
}
