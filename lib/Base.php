<?php
/**
 * A Base Class for an RBE
 */

class RBE_Base
{
	protected $_api_base;

	protected $_License;

	const ENGINE = null;

	function getLicense()
	{
		return $this->_License;
	}

	/**
	 * Set License
	 * @param array $l License Data Array
	 */
	function setLicense($l)
	{
		// New Preferred Way
		if (is_array($l) || is_object($l)) {

			if (is_object($l)) {
				$l = $l->toArray();
			}

			// OK
			if (empty($l['id'])) {
				throw new Exception('License Missing ID');
			}

			if (empty($l['code'])) {
				throw new Exception('License Missing CODE');
			}

			if (empty($l['guid'])) {
				throw new Exception('License Missing GUID');
			}

			$this->_License = $l;

		} else {
			throw new Exception('Invalid Parameters [LRB#066]');
		}

		return $this->_License;
	}

	/**
	 * Normalize record data array and return a hash
	 * @param [type] $a [description]
	 * @return [type] [description]
	 */
	static function recHash($a)
	{
		if (!is_array($a)) {
			if (is_object($a)) {
				if (method_exists($a, 'toArray')) {
					$a = $a->toArray();
				}
			}
		}

		self::_ksort_r($a);

		return md5(json_encode($a));
	}

	/*
	 * Key-Sort Array, Recursively
	 */
	static function _ksort_r($a)
	{
		foreach ($a as &$v) {
			if (is_array($v)) {
				_ksort_r($v);
			}
		}

		return ksort($a);
	}

}
