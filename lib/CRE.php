<?php
/**
 * A Compliance Reporting Engine Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC;

class CRE
{
	private static $cre_list = [];
	private static $ini_data = [];

	/**
	 * Factory
	 */
	static function factory(array $cfg)
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
		$ret_data = [];

		$ini_data = self::load_config_ini();

		// Patch data to always have two fields
		foreach ($ini_data as $cre_code => $cre_info) {
			$ret_data[$cre_code] = $cre_info;
		}

		// Load From YAML Data-Set
		$lib_root = dirname(__DIR__);
		$cre_list = glob(sprintf('%s/etc/cre/*.yaml', $lib_root));
		foreach ($cre_list as $cre_file) {

			$cre_code = basename($cre_file, '.yaml');
			$cre_code = str_replace('-', '/', $cre_code); // Use '/' for legacy reasons

			$cre_data = self::load_config_yaml($cre_code);
			$ret_data[$cre_code] = $cre_data;

		}

		ksort($ret_data);

		return $ret_data;

	}

	/**
	 * Get one Engine Config
	 */
	static function getEngine(string $cre_code)
	{
		// Legacy INI Data
		$cre_data0 = self::load_config_ini($cre_code);
		if (empty($cre_data0)) {
			$cre_data0 = [];
		}

		$cre_data1 = self::load_config_yaml($cre_code);

		$cre_data = array_merge($cre_data0, $cre_data1);

		return $cre_data;

	}

	/**
	 *
	 */
	protected static function load_config_ini(?string $cre_code=null)
	{
		if (empty(self::$ini_data)) {

			$ini_file = null;

			// Use Application Specific
			if (defined('APP_ROOT')) {
				$chk = sprintf('%s/etc/cre.ini', APP_ROOT);
				if (is_file($chk)) {
					$ini_file = $chk;
				}
			}

			// Use Default
			if (empty($ini_file)) {
				$lib_root = dirname(__DIR__);
				$ini_file = sprintf('%s/etc/cre.ini', $lib_root);
			}

			if ( ! is_file($ini_file)) {
				throw new \Exception('CRE configuration file not found [CLC-050]');
			}

			$ini_data = parse_ini_file($ini_file, true, INI_SCANNER_RAW);
			foreach ($ini_data as $tmp_code => $tmp_data) {
				if (empty($tmp_data['id'])) {
					$tmp_data['id'] = $tmp_code;
				}
				if (empty($tmp_data['code'])) {
					$tmp_data['code'] = $tmp_code;
				}
				self::$ini_data[$tmp_code] = $tmp_data;
			}
		}

		if ( ! empty($cre_code)) {
			return self::$ini_data[$cre_code];
		}

		return self::$ini_data;

	}

	/**
	 *
	 */
	protected static function load_config_yaml(string $cre_code)
	{
		$cre_code = str_replace('/', '-', $cre_code);

		// Trim this SHIT name
		if ('usa-wa-ccrs' == $cre_code) {
			$cre_code = 'usa-wa';
		}

		// YAML Data from This Library
		$lib_root = dirname(__DIR__);
		$cre_file = sprintf('%s/etc/cre/%s.yaml', $lib_root, $cre_code);
		if ( ! is_file($cre_file)) {
			throw new \Exception(sprintf('Invalid CRE Adapter Config "%s" [CLC-093]', $cre_code));
		}

		$cre_data0 = yaml_parse_file($cre_file);
		if ( ! is_array($cre_data0)) {
			throw new \Exception('Invalid CRE Configuration [CLC-114]');
		}
		$cre_data0['id'] = $cre_code;
		$cre_data0['code'] = $cre_code;

		$cre_data1 = [];
		if (defined('APP_ROOT')) {
			$cre_file = sprintf('%s/etc/cre/%s.yaml', APP_ROOT, $cre_code);
			if (is_file($cre_file)) {
				$tmp = yaml_parse_file($cre_file);
				if (is_array($tmp)) {
					$cre_data1 = $tmp;
				}
			}
		}

		$cre_data = array_merge($cre_data0, $cre_data1);

		return $cre_data;
	}

}
