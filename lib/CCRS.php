<?php
/**
 * LCB CCRS Utility Class
 * For https://lcb.wa.gov/ccrs
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE;

use HeadlessChromium\BrowserFactory;
use HeadlessChromium\Communication\Message;

class CCRS extends \OpenTHC\CRE\Base
{
	const ENGINE = 'ccrs';

	protected $cookie_list = [];

	/**
	 *
	 */
	function auth($username, $password)
	{
		// Save the cookies in bong.sqlite
		// $bf = new BrowserFactory();
		$bf = new BrowserFactory('/usr/bin/chromium');
		// $bf = new BrowserFactory('node_modules/puppeteer/.local-chromium/linux-686378/chrome-linux/chrome');
		$b = $bf->createBrowser([
			// 'debugLogger' => 'php://stdout',
			'noSandbox' => true,
			// 'userDataDir' => sprintf('%s/chrome-profile', APP_ROOT)
		]);

		$page = $b->createPage();
		$page->setUserAgent('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36');

		// Main Page
		$page->navigate('https://cannabisreporting.lcb.wa.gov/')->waitForNavigation();
		$url0 = $page->getCurrentUrl();
		echo "url0:{$url0}\n";

		// Needs Authentication?
		if (preg_match('/^https:\/\/secureaccess\.wa\.gov\/FIM2\/sps\/auth/', $url0)) {

			// POST the Form?
			$code = sprintf('document.querySelector("#username").value = "%s";', $username);
			$page->evaluate($code);

			// Password
			$code = sprintf('document.querySelector("#password").value = "%s";', $password);
			$page->evaluate($code);

			// $page->screenshot()->saveToFile('ccrs0.png');
			// $page->evaluate('document.querySelector("#submit-button-row input").click()')->waitForPageReload();

			$page->mouse()->find('#submit-button-row input')->click();
			$page->waitForReload();

			$url1 = $page->getCurrentUrl();
			echo "url1:{$url1}\n";
			// $page->screenshot()->saveToFile('ccrs1.png');
		} elseif (preg_match('/https:\/\/secureaccess\.wa\.gov\/FIM2\/sps\/sawidp\/saml20\/login/', $url0)) {
			// OK ? Only see this one intermittently
		} elseif (preg_match('/^https:\/\/cannabisreporting\.lcb\.wa\.gov\//', $url0)) {
			// Authenticated
		} else {
			echo "No Match: $url0\n";
		}

		// Save Cookies
		$cookie_out = [];
		$cookie_jar = $page->getAllCookies();
		foreach ($cookie_jar as $c) {
			$a = (array)$c;
			$a = array_shift($a);
			$cookie_out[] = $a;
		}

		$this->cookie_list = $cookie_out;

		return $cookie_out;

	}

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
		// So we replace those with HEX notation.
		// And their exports are in TSV, so we make sure we don't send those either.
		array_walk($row, function(&$val, $key) {
			// surly this won't be a problem ;p /djb 2021-12-02
			$val = str_replace('"', '0x22', $val); // Double Quote
			$val = str_replace(',', '0x2c', $val); // Single Quote
			$val = str_replace("\t", '0x09', $val); // TAB
			$val = trim($val);
		});

		// it's unquoted and not
		fwrite($output_csv, implode(',', $row));
		fwrite($output_csv, "\r\n"); // CRLF
	}

	/**
	 *
	 */
	static function map_product_type0($x)
	{
		if (empty($x)) {
			return null;
		}

		switch ($x) {
			// case '018NY6XC00PR0DUCTTYPE00000': // -system-
			// case '018NY6XC00PR0DUCTTYPE00001': return 'HarvestedMaterial'; // -orphan-
			case '018NY6XC00PT0WQP2XV5KNP395': return 'EndProduct';
			case '018NY6XC00PT25F95HPG583AJB': return 'EndProduct';
			case '018NY6XC00PT2BKFPCEFB9G1Z2': return 'PropagationMaterial';
			case '018NY6XC00PT3EZZ4GN6105M64': return 'PropagationMaterial';
			case '018NY6XC00PT63ECNBAZH32YC3': return 'IntermediateProduct';
			case '018NY6XC00PT684JJSXN8RAWBM': return 'IntermediateProduct';
			case '018NY6XC00PT7N83PFNCX8ZFEF': return 'EndProduct';
			// case '': return null; // Waste
			case '018NY6XC00PT8ZPGMPR8H2TAXH': return 'HarvestedMaterial';
			case '018NY6XC00PTAF3TFBB51C8HX6': return 'HarvestedMaterial';
			case '018NY6XC00PTBJ3G5FDAJN60EX': return 'EndProduct';
			case '018NY6XC00PTBNDY5VJ8JQ6NKP': return 'EndProduct';
			case '018NY6XC00PTCS5AZV189X1YRK': return 'IntermediateProduct';
			case '018NY6XC00PTD9Q4QPFBH0G9H2': return 'EndProduct';
			case '018NY6XC00PTFY48D1136W0S0J': return 'PropagationMaterial';
			case '018NY6XC00PTGBW49J6YD3WM84': return 'HarvestedMaterial';
			case '018NY6XC00PTGMB39NHCZ8EDEZ': return 'EndProduct';
			case '018NY6XC00PTGRX4Q9SZBHDA5Z': return 'EndProduct';
			case '018NY6XC00PTHE7GWB4QTG4JKZ': return 'EndProduct';
			case '018NY6XC00PTHP9NMJ1RE6TA62': return 'IntermediateProduct';
			case '018NY6XC00PTHPB8YG56S0MCAC': return 'EndProduct';
			case '018NY6XC00PTKYYGMRSKV4XNH7': return 'EndProduct';
			case '018NY6XC00PTNPA4TPCYSKD5XN': return 'IntermediateProduct';
			case '018NY6XC00PTR9M5Z9S4T31C4R': return 'IntermediateProduct';
			case '018NY6XC00PTRPPDT8NJY2MWQW': return 'PropagationMaterial';
			case '018NY6XC00PTSF5NTC899SR0JF': return 'EndProduct';
			case '018NY6XC00PTY5XPA4KJT6W3K4': return 'IntermediateProduct';
			case '018NY6XC00PTY9THKSEQ8NFS1J': return 'PropagationMaterial';
			case '018NY6XC00PTZZWCH7XVREHK6T': return 'HarvestedMaterial';
			default:
				throw new \Exception("Type '$x' Not Handled [CLC-156]");
		}
	}

	static function map_product_type1($x)
	{
		if (empty($x)) {
			return null;
		}

		switch ($x) {
			// case '018NY6XC00PR0DUCTTYPE00000':
			// case '018NY6XC00PR0DUCTTYPE00001': return 'Flower Unlotted'; // -orphan-
			case '018NY6XC00PT0WQP2XV5KNP395': return 'Topical Ointment';
			case '018NY6XC00PT25F95HPG583AJB': return 'Capsule';
			case '018NY6XC00PT2BKFPCEFB9G1Z2': return 'Plant';
			case '018NY6XC00PT3EZZ4GN6105M64': return 'Plant';
			case '018NY6XC00PT63ECNBAZH32YC3': return 'Marijuana Mix';
			case '018NY6XC00PT684JJSXN8RAWBM': return 'Ethanol Concentrate';
			case '018NY6XC00PT7N83PFNCX8ZFEF': return 'Liquid Edible';
			// case '018NY6XC00PT8AXVZGNZN3A0QT': return 'Waste';
			case '018NY6XC00PT8ZPGMPR8H2TAXH': return 'Other Material Lot';
			case '018NY6XC00PTAF3TFBB51C8HX6': return 'Flower Lot';
			case '018NY6XC00PTBJ3G5FDAJN60EX': return 'Suppository';
			case '018NY6XC00PTBNDY5VJ8JQ6NKP': return 'Solid Edible';
			case '018NY6XC00PTCS5AZV189X1YRK': return 'Hydrocarbon Concentrate';
			case '018NY6XC00PTD9Q4QPFBH0G9H2': return 'Tincture';
			case '018NY6XC00PTFY48D1136W0S0J': return 'Plant';
			case '018NY6XC00PTGBW49J6YD3WM84': return 'Other Material Unlotted';
			case '018NY6XC00PTGMB39NHCZ8EDEZ': return 'Usable Marijuana';
			case '018NY6XC00PTGRX4Q9SZBHDA5Z': return 'Marijuana Mix Infused';
			case '018NY6XC00PTHE7GWB4QTG4JKZ': return 'Sample Jar';
			case '018NY6XC00PTHP9NMJ1RE6TA62': return 'Food Grade Solvent Concentrate';
			case '018NY6XC00PTHPB8YG56S0MCAC': return 'Transdermal';
			case '018NY6XC00PTKYYGMRSKV4XNH7': return 'Marijuana Mix Packaged';
			case '018NY6XC00PTNPA4TPCYSKD5XN': return 'Non-Solvent Based Concentrate';
			case '018NY6XC00PTR9M5Z9S4T31C4R': return 'CO2 Concentrate';
			case '018NY6XC00PTRPPDT8NJY2MWQW': return 'Plant';
			case '018NY6XC00PTSF5NTC899SR0JF': return 'Marijuana Mix Infused'; // Concentrate For Inhalation
			case '018NY6XC00PTY5XPA4KJT6W3K4': return 'Infused Cooking Medium';
			case '018NY6XC00PTY9THKSEQ8NFS1J': return 'Seed';
			case '018NY6XC00PTZZWCH7XVREHK6T': return 'Flower Unlotted';
			default:
				throw new \Exception("Type '$x' Not Handled [CLC-194]");
		}
	}

	/**
	 * Ping to BONG for License Status
	 */
	function ping()
	{
		$url = sprintf('%s/ping', $this->_api_base);
		$this->_req_head = [
			'authorization' => sprintf('Bearer %s', $this->_api_token)
		];
		$res = $this->get($url);
		return $res;
		// return [
		// 	'code' => 501,
		// 	'data' => null,
		// 	'meta' => [ 'detail' => 'Not a pingable platform' ]
		// ];
	}

}
