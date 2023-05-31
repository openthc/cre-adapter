<?php
/**
 * A Contact
 * What they call a User, we call a Contact
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\LeafData;

class Contact extends \OpenTHC\CRE\LeafData\Base
{
	protected $_path = '/users';

	function create($x)
	{
		$arg = array('user' => array());
		$arg['user'][] = $x;
		$res = $this->_client->call('POST', '/users', $arg);
		return $res;
	}

	function delete($x)
	{
		$res = $this->_client->call('DELETE', sprintf('/users/%s', $x));
		return $res;
	}

	function update($x)
	{
		$res = $this->_client->call('POST', '/users/update', $x);
		return $res;
	}

}
