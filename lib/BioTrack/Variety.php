<?php
/**
 * Strain in BioTrack as NO-OP
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\BioTrack;

class Variety extends \OpenTHC\CRE\BioTrack\Base
{
	/**
	 */
	function single($x)
	{
		throw new Exception('Not Implemented');
	}

	function create($x)
	{
		return [
			'data' => 'success',
		];
	}

	function delete($x)
	{
		return [
			'data' => 'success',
		];
	}
}
