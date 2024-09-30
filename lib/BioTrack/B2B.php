<?php
/**
 * Interface to th BioTrack Trace 2.0 API for Manifest operations
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\BioTrack;

class B2B extends \OpenTHC\CRE\BioTrack\Base
{
	protected $_path = '/v1/manifest';
	protected $_training = 0;

	function create($obj)
	{
		// What does `last_manifest_id` even mean?
		// {{localaddress}}/v1/manifest/{{last_manifest_id}}/start
		$this->_path = "/v1/manifest/{$oid}/start";
	}

	/**
	 * Undo the XXX operation
	 */
	function undo($oid)
	{
		// /v1/manifest/{{last_manifest_id}}/start/undo
		$this->_path = "/v1/manifest/{$oid}/start/undo";
	}

	/**
	 * Void the transfer
	 */
	function void($oid)
	{
		// /v1/manifest/{{last_manifest_id}}/void
		$this->_path = "/v1/manifest/{$oid}/void";
	}

	/**
	 * Get manifest records
	 */
	function search()
	{
		// /v1/manifest?training=0&start=2021-06-01T15:03:00-07&end=2021-06-03T15:05:59-07&page=1
		$arg = [];
		if (abs(intval($this->training)) !== 0) {
			$arg['training'] = 1;
		}
		if ( ! empty($start)) {
			$arg['start'];
		}
		if ( ! empty($end)) {
			$arg['end'];
		}
		$arg = http_build_query($arg);
		$this->_path = $this->_path . '?' . $arg;
	}

	/**
	 * Set training mode
	 */
	function setTraining(int $training=0) : void
	{
		$this->_training = intval($training);
	}

}
