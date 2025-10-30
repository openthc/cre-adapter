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
		$cre_info = self::getConfig($cfg['code']);

		$cfg = array_merge($cre_info, $cfg);

		if (empty($cfg['class'])) {
			throw new \Exception(sprintf('Cannot create CRE "%s" without Class', $cfg['code']));
		}

		$cre = new $cre_info['class']($cfg);

		return $cre;

	}

	/**
	 * Get Specific Engine Config
	 */
	static function getConfig(string $cre_code, string $app_root='')
	{
		// Use the / name as the canonical
		$cre_code = str_replace('-', '/', $cre_code);
		if ('usa/wa/ccrs' == $cre_code) {
			$cre_code = 'usa/wa';
		}

		$cre_list = self::getEngineList($app_root);
		if (empty($cre_list[$cre_code])) {
			throw new \Exception('Invalid CRE Configuration [CLC-041]');
		}
		$cre_data = $cre_list[$cre_code];
		return $cre_data;
	}

	/**
	 * Return CRE Engine Configuration
	 * Only Returns engines that are Live
	 */
	static function getEngineList(string $app_root='')
	{
		// Load Core Library Configuration
		$lib_root = dirname(__DIR__);
		$cre_file = sprintf('%s/etc/cre.yaml', $lib_root);
		$ret_data = yaml_parse_file($cre_file);

		// Use "default" app-root if none provided
		if (empty($app_root)) {
			if (defined('APP_ROOT')) {
				$app_root = APP_ROOT;
			}
		}

		// Merge the Other Data
		if ( ! empty($app_root)) {
			$cre_file = sprintf('%s/etc/cre.yaml', $app_root);
			if (is_file($cre_file)) {
				$cre_data = yaml_parse_file($cre_file);
				foreach ($cre_data as $k => $v) {
					$a = [];
					$b = $v;
					if ( ! empty($ret_data[$k])) {
						$a = $ret_data[$k];
					}
					$c = array_merge($a, $b);
					$ret_data[$k] = $c;
				}
			}

		}

		return $ret_data;

	}

}
