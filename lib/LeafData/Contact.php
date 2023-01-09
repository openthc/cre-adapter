<?php
/**
	A Contact
	What they call a User, we call a Contact
*/

class RBE_LeafData_Contact extends RBE_LeafData_Base
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
