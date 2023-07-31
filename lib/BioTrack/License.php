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

	/**
	 * Somehow get just One License?
	 */
	function single(string $oid)
	{
		$this->_client->auth();
		$res = $this->_client->sync_vendor(0);
		switch ($res['code']) {
			case 200:
				// OK
				foreach ($res['vendor'] as $rec) {
					if ($rec['location'] == $oid) {
						$rec['stat'] = 200;
						return [
							'code' => 200,
							'data' => $rec,
							'meta' => [],
						];
					}
				}
				break;
			case 403:
				return $res;
		}

		return [
			'code' => 500,
			'data' => $res,
			'meta' => [
				'note' => 'Invalid Response from CRE [LBL-059]'
			]
		];
	}

}
