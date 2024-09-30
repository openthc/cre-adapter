<?php
/**
 * Contact in BioTrack
 *
 * SPDX-License-Identifier: MIT
 *
 * This can be both Employees and Patients
 */

namespace OpenTHC\CRE\BioTrack;

class Contact extends \OpenTHC\CRE\BioTrack\Base
{
	/**
	 *
	 */
	function create($obj)
	{
		if (empty($obj['id'])) {
			throw new \Exception('Invalid Contact, ID Required [CBC-020]');
		}
		if (empty($obj['name'])) {
			throw new \Exception('Invalid Contact, Name Required [CBC-023]');
		}
		if (empty($obj['dob'])) {
			throw new \Exception('Invalid Contact, Date-of-Birth Required [CBC-026]');
		}
		if (empty($obj['doh'])) {
			throw new \Exception('Invalid Contact, Date-of-Hire Required [CBC-029]');
		}

		$dt_dob = new \DateTime($obj['dob']);
		$dt_doh = new \DateTime($obj['doh']);

		$arg = array(
			'action'        => 'employee_add',
			'employee_id'   => $obj['id'],
			'employee_name' => $obj['name'],
			'birth_year'    => $dt_dob->format('Y'),
			'birth_month'   => $dt_dob->format('m'),
			'birth_day'     => $dt_dob->format('d'),
			'hire_year'     => $dt_doh->format('Y'),
			'hire_month'    => $dt_doh->format('m'),
			'hire_day'      => $dt_doh->format('d'),
		);

		$res = $this->_client->_curl_exec($arg);

		return [
			'code' => 200,
			'data' => $res,
			'meta' => [],
		];
	}

	/**
	 * @param string $oid Employee ID
	 */
	function delete($oid)
	{
		$res = $this->_client->_curl_exec(array(
			'action' => 'employee_remove',
			'employee_id' => $oid,
		));

		return [
			'code' => 200,
			'data' => $res,
			'meta' => [],
		];
	}


	/**
	 * Lookup Customer or Employee
	 */
	function search($arg)
	{
		if (empty($arg['type'])) {
			throw new \Exception('Invalid Contact Type [CBC-080]');
		}

		switch (strtoupper($arg['type'])) {
			case 'CUSTOMER':
			case 'PATIENT':
				// /**
				//  * Lookup a Customer or Patient in the RBE
				//  * @param string $mp Medical Patient
				//  * @param string $cg Care Giver
				//  */
				// function card_lookup($mp, $cg)
				// {
				// 	$arg = array(
				// 		'action' => 'card_lookup',
				// 		'card_id' => $mp,
				// 		'caregiver_card_id' => $cg,
				// 	);
				// 	$res = $this->_curl_exec($arg);
				// 	return $res;
				// }
				break;
			case 'EMPLOYEE':
				break;
		}
	}

	/**
	 *
	 */
	function single($x)
	{
		throw new \Exception('Not Implemented');
	}

	/**
	 *
	 */
	function update(string $oid, $obj)
	{
		// if (empty($obj['type'])) {
		// 	throw new \Exception('Invalid Contact Type [CBC-121]');
		// }

		if (empty($oid)) {
			throw new \Exception('Invalid Contact, ID Required [CBC-125]');
		}
		if (empty($obj['name'])) {
			throw new \Exception('Invalid Contact, Name Required [CBC-128]');
		}
		if (empty($obj['dob'])) {
			throw new \Exception('Invalid Contact, Date-of-Birth Required [CBC-131]');
		}
		if (empty($obj['doh'])) {
			throw new \Exception('Invalid Contact, Date-of-Hire Required [CBC-134]');
		}

		$dt_dob = new \DateTime($obj['dob']);
		$dt_doh = new \DateTime($obj['doh']);

		$res = $this->_client->_curl_exec(array(
			'action'        => 'employee_modify',
			'employee_id'   => $oid,
			'employee_name' => $obj['name'],
			'birth_year'    => $dt_dob->format('Y'),
			'birth_month'   => $dt_dob->format('m'),
			'birth_day'     => $dt_dob->format('d'),
			'hire_year'     => $dt_doh->format('Y'),
			'hire_month'    => $dt_doh->format('m'),
			'hire_day'      => $dt_doh->format('d'),
		));

		return $res;

	}

}
