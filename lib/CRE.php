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

		// Load from Library YAML dataset
		$lib_root = dirname(__DIR__);
		$cre_list = glob(sprintf('%s/etc/cre/*.yaml', $lib_root));
		foreach ($cre_list as $cre_file) {

			$cre_code = basename($cre_file, '.yaml');

			$cre_data = self::load_config_yaml($cre_code);
			$ret_data[$cre_code] = $cre_data;

		}

		// Load from Application YAML dataset
		if (defined('APP_ROOT')) {

			$cre_list = glob(sprintf('%s/etc/cre/*.yaml', APP_ROOT));
			foreach ($cre_list as $cre_file) {

				$cre_code = basename($cre_file, '.yaml');

				$cre_data = self::load_config_yaml($cre_code);
				$ret_data[$cre_code] = $cre_data;

			}

		}


		ksort($ret_data);

		return $ret_data;

	}

	/**
	 * Get Specific Engine Config
	 */
	static function getConfig(string $cre_code)
	{
		$cre_data = self::load_config_yaml($cre_code);
		return $cre_data;
	}

	/**
	 * Get one Engine Config
	 * @deprecated this is basically like the Factory?
	 */
	static function getEngine(string $cre_code)
	{
		return self::getConfig($cre_code);
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

		// Add a Third Layer?

		// Merge the Overlay Data
		$cre_data = array_merge($cre_data0, $cre_data1);

		if (empty($cre_data['id'])) {
			$cre_data['id'] = str_replace('-', '/', $cre_code);
		}
		if (empty($cre_data['code'])) {
			$cre_data['code'] = $cre_data['id'];
		}

		return $cre_data;

	}

}
