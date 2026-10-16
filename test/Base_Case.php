<?php
/**
 * Test Helper
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

namespace OpenTHC\CRE\Test;

class Base_Case extends \OpenTHC\Test\Base
{
	/**
	 *
	 */
	function assertValidResponse($res, $code_expect=200, $type_expect=null, $dump=null)
	{
		$ret = parent::assertValidResponse($res, $code_expect, $type_expect, $dump);
		switch ($type_expect) {
		case 'application/json':
			$this->assertIsArray($ret['data']);
			$this->assertIsArray($ret['meta']);
			break;
		default:
			// Nothing
		}

		return $ret;

	}

}
