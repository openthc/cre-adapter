<?php
/**
 * LCB CCRS Utility Class
 * For https://lcb.wa.gov/ccrs
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE;

class CCRS extends \OpenTHC\CRE\Base
{
	const ENGINE = 'openthc';

	function __construct() { /* NO */ }

	/**
	 * Get Data Hash of a CSV File
	 */
	static function csv_file_hash($source_file)
	{
		$source_file = $argv[1];
		if ( ! is_file($source_file)) {
			echo "Fail 009\n";
			exit(1);
		}

		if ( ! preg_match('/^(\w+)_/', basename($source_file), $m)) {
			echo "Fail 014\n";
			exit(1);
		}

		$source_mode = '';
		$source_type = strtolower($m[1]);
		switch ($source_type) {
			case 'area':
				$column_count = 9;
				break;
			case 'variety':
			case 'strain':
				$column_count = 4;
				break;
		}

		$fh = fopen($source_file, 'r');
		$map = [];
		$row0 = fgetcsv($fh);
		$row0_text = implode(',', $row0);

		// Not, we'll have to unscramble the column order from the error report back the "right" way (somehow)

		if ('SubmittedBy' == $row0[0]) {
			// Clear Row 1, 2, 3 and 4
			fgetcsv($fh);
			fgetcsv($fh);
			$map = fgetcsv($fh);
		} elseif (preg_match('/,ErrorMessage/i', $row0_text)) {
			// It's an Error File and Trim the Last Column
			$map = $row0;
			$source_mode = 'error';
		}

		$source_data = [];
		while ($row = fgetcsv($fh)) {

			$row = array_combine($map, $row);

			unset($row['ErrorMessage']);

			// Patch Values to be "right"
			foreach ($map as $key) {
				switch ($key) {
					case 'CreatedDate':
					case 'UpdatedDate':
						break;
					case 'IsQuarantine':
						if (empty($row['IsQuarantine'])) {
							$row['IsQuarantine'] = 'FALSE';
						}
						break;
					case 'Operation':
						$row['Operation'] = strtoupper($row['Operation']);
						break;
				}
			}

			$row_hash = md5(implode(',', array_values($row)));
			$csv_hash[] = $row_hash;

		}

		$csv_hash = md5(implode("\n", $csv_hash));

		return $csv_hash;

	}

	/**
	 * Output a CSV in a non-rfc4180 compliant way
	 * "fix" data to strip out some unsafe characters
	 * It's so sloppy it makes me sad :( /djb 2021-12-02
	 */
	static function fputcsv_stupidly($output_csv, $row)
	{
		// HURR-DURR
		// Can't use fputcsv because the LCB system is confused by quoted fields on this file
		array_walk($row, function(&$val, $key) {
			// surly this won't be a problem ;p /djb 2021-12-02
			$val = str_replace('"', '0x22', $val);
			$val = str_replace(',', '0x2c', $val);
			$val = trim($val);
		});

		// it's unquoted and not
		fwrite($output_csv, implode(',', $row));
		fwrite($output_csv, "\r\n"); // CRLF
	}

}
