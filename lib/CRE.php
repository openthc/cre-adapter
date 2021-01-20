<?php
/**
 *
 */

namespace OpenTHC;

class CRE
{
	static function factory($cfg)
	{
		$cre_info = self::getEngine($cfg['code']);

		$cfg = array_merge($cre_info, $cfg);

		if (empty($cfg['class'])) {
			throw new \Exception(sprintf('Cannot create CRE "%s" without Class', $cfg['code']));
		}

		$cre = new $cre_info['class']($cfg);

		return $cre;

	}

	/**
	 * Return CRE Engine Configuration
	 */
	static function getEngineList()
	{
		$ini_file = '';

		// Use Application Specific
		if (defined('APP_ROOT')) {
			$dir = APP_ROOT;
			$ini_file = sprintf('%s/etc/cre.ini', $dir);
			if (!is_file($ini_file)) {
				$ini_file = '';
			}
		}

		// Use Default
		if (empty($ini_file)) {
			$dir = dirname(__DIR__);
			$ini_file = sprintf('%s/etc/cre.ini', $dir);
		}

		if (!is_file($ini_file)) {
			throw new \Exception('CRE configuration file not found [CLC-050]');
		}

		$ini_data = parse_ini_file($ini_file, true, INI_SCANNER_RAW);

		// Patch data to always have two fields
		$ret_data = [];
		foreach ($ini_data as $cre_code => $cre_info) {
			if (empty($cre_info['id'])) {
				$cre_info['id'] = $cre_code;
			}
			if (empty($cre_info['code'])) {
				$cre_info['code'] = $cre_code;
			}
			$ret_data[$cre_code] = $cre_info;
		}

		return $ret_data;
	}

	/**
	 * Get one Engine Config
	 */
	static function getEngine($code)
	{
		$res = self::getEngineList();
		$ret = $res[$code];
		return $ret;
	}

	/**
	 * Load CRE Definitions
	 */
	protected static function load_cre_data()
	{
		$cre_data = [];

		$lib_path = dirname(__DIR__);

		$ini_file_list = [];

		if (defined('APP_ROOT')) {
			$ini_file_list[] = sprintf('%s/etc/cre.ini', APP_ROOT);
		}

		$ini_file_list[] = sprintf('%s/etc/cre.ini', $lib_path);


		foreach ($ini_file_list as $ini_file) {
			if (is_file($ini_file)) {
				$cre_data = parse_ini_file($ini_file, true, INI_SCANNER_RAW);
				break;
			}
		}

		return $cre_data;
	}
}
