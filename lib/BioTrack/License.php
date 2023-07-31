<?php
/**
 * License Adapter for BioTrack
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\BioTrack;

class License extends Base
{
	protected $_path = '';

	/**
	 *
	 */
	function ping($id)
	{
		// return [
		// 	'code' => '501',
		// 	'data' => null,
		// 	'meta' => [
		// 		'note' => 'Not Implemented'
		// 	]
		// ];

		return $this->_client->auth();

	}

}
