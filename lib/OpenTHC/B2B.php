<?php
/**
 * Implementation for B2B Transactions
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class B2B extends Base
{
	function search($filter=null)
	{
		$url = '/transfer';

		if (!empty($filter)) {
			$url.= '?' . http_build_query($filter);
		}

		$ret = $this->_cre->get($url);
		return $ret;

		// $res0 = $this->export()->search();

		// $res1 = $this->import()->search();

	}

	function sync()
	{
		// NO-OP
	}
}
