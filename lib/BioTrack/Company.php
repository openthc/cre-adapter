<?php
/**
 * Company Adapter for BioTrack
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\BioTrack;

class Company extends Base
{
	protected $_path = '';

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
