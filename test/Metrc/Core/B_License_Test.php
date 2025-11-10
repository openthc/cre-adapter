<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Metrc\Core;

class B_License_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	/**
	 *
	 */
	function test_license_search()
	{
		$res = $this->cre->license()->search();
		$this->assertValidResponse($res);
	}

	/**
	 *
	 */
	function test_license_select()
	{
		$res = $this->cre->license()->search();
		$this->assertValidResponse($res);
		$this->assertIsArray($res['data']);
		// $this->assertCount()

		// Just Use the First One
		$L = $res['data'][0];
		$this->cre->setLicense([
			'id' => $L['License']['Number'],
			'code' => $L['License']['Number'],
			'guid' => $L['License']['Number'],
			'name' => $L['DisplayName']
		]);

		$res = $this->cre->section()->search();
		$this->assertValidResponse($res);
	}

}
