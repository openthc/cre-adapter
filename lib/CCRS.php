<?php
/**
 * Interface for WSLCB CCRS
 *
 * SPDX-License-Identifier: MIT
 *
 * https://lcb.wa.gov/ccrs
 */

namespace OpenTHC\CRE;

use HeadlessChromium\BrowserFactory;
use HeadlessChromium\Communication\Message;

class CCRS extends \OpenTHC\CRE\Base
{
	const ENGINE = 'ccrs';

	const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36';

	protected $cookie_list = [];

	protected $_service_key;

	function __construct(array $cfg)
	{
		parent::__construct($cfg);

		if ( ! empty($this->_cfg['cookie-list'])) {
			$this->cookie_list = $this->_cfg['cookie-list'];
		}

		$this->_service_key = $this->_cfg['service-sk'];

	}

	/**
	 *
	 */
	function auth($username, $password)
	{
		// $bf = new BrowserFactory();
		$bf = new BrowserFactory('/usr/bin/chromium');
		// $bf = new BrowserFactory('node_modules/puppeteer/.local-chromium/linux-686378/chrome-linux/chrome');
		$b = $bf->createBrowser([
			// 'debugLogger' => 'php://stdout',
			'noSandbox' => true,
			// 'userDataDir' => sprintf('%s/chrome-profile', APP_ROOT)
		]);

		$page = $b->createPage();
		$page->setUserAgent(self::USER_AGENT);

		// Main Page
		$page->navigate($this->_api_base)->waitForNavigation();
		$url0 = $page->getCurrentUrl();
		$rex1 = sprintf('/%s/', preg_quote($this->_api_base, '/'));

		// Needs Authentication?
		if (preg_match('/secureaccess\.wa\.gov\/FIM2\/sps\/auth/', $url0)) {

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
			// echo "url1:{$url1}\n";
			// $page->screenshot()->saveToFile('ccrs1.png');
		} elseif (preg_match('/secureaccess\.wa\.gov\/FIM2\/sps\/sawidp\/saml20\/login/', $url0)) {
			// OK ? Only see this one intermittently
		} elseif (preg_match($rex1, $url0)) {
			// Authenticated
		} else {
			throw new \Exception("Unexpected URL: $url0 != $rex1");
		}

		// Save Cookies
		$cookie_out = [];
		$cookie_jar = $page->getAllCookies();
		foreach ($cookie_jar as $c) {
			$c = (array)$c;
			$c = array_shift($c);
			if (preg_match('/lcb\.wa\.gov/', $c['domain'])) {
				$cookie_out[] = $c;
			}
		}

		$this->cookie_list = $cookie_out;

		return $this->cookie_list;

	}

	/**
	 *
	 */
	function ping()
	{
		// Build CURL cookies from configured cookies
		$cookie_list = [];
		foreach ($this->cookie_list as $c) {
			$cookie_list[] = sprintf('%s=%s', $c['name'], $c['value']);
		}
		sort($cookie_list);

		// Get to Verify Access and get RVT
		$req = _curl_init($this->_api_base);
		// curl_setopt($req, CURLOPT_VERBOSE, true);
		curl_setopt($req, CURLOPT_USERAGENT, self::USER_AGENT);
		curl_setopt($req, CURLOPT_HTTPHEADER, [
			'accept: text/html',
			sprintf('authority: %s', parse_url($this->_api_base, PHP_URL_HOST)),
			sprintf('cookie: %s', implode('; ', $cookie_list)),
			sprintf('origin: %s', $this->_api_base),
			sprintf('referer: %s', $this->_api_base),
		]);
		$res = curl_exec($req);
		$inf = curl_getinfo($req);

		switch ($inf['http_code']) {
			case 200:
				// Hate "parsing" with regex
				$csrf = preg_match('/<input name="__RequestVerificationToken" type="hidden" value="([^"]+)" \/>/', $res, $m) ? $m[1] : null;
				return [
					'code' => 200,
					'csrf' => $csrf,
				];
			default:
				return [
					'code' => $inf['http_code'],
					'data' => $res,
					'meta' => $inf,
				];
		}

	}

	/**
	 * Upload into the CCRS Platform
	 */
	function upload($file_info)
	{
		if (empty($file_info['name'])) {
			throw new \Exception('Invalid file-name for upload [CLC-160]');
		}
		if (empty($file_info['data'])) {
			throw new \Exception('Invalid file-data for upload [CLC-163]');
		}

		// Get Main Page (with Auth?)
		$res0 = $this->ping();
		switch ($res0['code']) {
			case 200:
				// OK
				break;
			default:
				return $res0;
		}

		// Make POST
		$post = $this->_upload_make_post($res0['csrf'], $file_info['name'], $file_info['data']);

		// Send POST
		// $upload_html = _post_home_upload($cookie_list1, $mark, $post);

		$cookie_list = [];
		foreach ($this->cookie_list as $c) {
			$cookie_list[] = sprintf('%s=%s', $c['name'], $c['value']);
		}
		sort($cookie_list);

		$base_url = rtrim($this->_api_base, '/');
		$req = __curl_init(sprintf('%s/Home/Upload', $base_url));
		// curl_setopt($req, CURLOPT_VERBOSE, true);
		curl_setopt($req, CURLOPT_STDERR, fopen('php://stderr', 'a'));
		curl_setopt($req, CURLOPT_USERAGENT, self::USER_AGENT);
		curl_setopt($req, CURLOPT_POST, true);
		curl_setopt($req, CURLOPT_POSTFIELDS, $post['body']);
		curl_setopt($req, CURLOPT_HTTPHEADER, [
			'accept: text/html',
			'accept-language: en-US,en;q=0.9',
			sprintf('authority: %s', parse_url($base_url, PHP_URL_HOST)),
			'cache-control: max-age=0',
			sprintf('content-length: %d', strlen($post['body'])),
			sprintf('content-type: multipart/form-data; boundary=%s', $post['mark']),
			sprintf('cookie: %s', implode('; ', $cookie_list)),
			sprintf('origin: %s', $base_url),
			sprintf('referer: %s', $base_url),
		]);

		$res_body = curl_exec($req);
		$res_info = curl_getinfo($req);

		$ret = [
			'code' => $res_info['http_code'],
			'data' => $res_body,
			'meta' => [
				'created_at' => '',
				'created_at_cre' => '',
			]
		];

		if (preg_match('/Your Files Could Not Be Uploaded/', $res_body)) {
			$ret['code'] = 400;
		}

		if (preg_match('/(Your submission was received at (.+) Pacific Time)/', $res_body, $m)) {

			$dt0 = new \DateTime();
			$dt0->setTimezone(new \DateTimezone('America/Los_Angeles'));
			$ret['meta']['created_at'] = $dt0->format(\DateTime::RFC3339);

			$dt1 = new \DateTime($m[2], new \DateTimezone('America/Los_Angeles'));
			// $dt1->setTimezone(new \DateTimezone('America/Los_Angeles'));

			$ret['meta']['created_at_cre'] = $dt1->format(\DateTime::RFC3339);

		}

		// Return Result
		return $ret;

	}

	/**
	 *
	 */
	private function _upload_make_post($csrf, $src_name, $src_data)
	{
		$mark = '----WebKitFormBoundaryAAAA8cKhBUv35ObB';
		$post = [];

		// Fix Name on Upload
		$src_name = basename($src_name);

		$post[] = sprintf('--%s', $mark);
		$post[] = sprintf('content-disposition: form-data; name="files"; filename="%s"', $src_name);
		// $post[] = 'content-transfer-encoding: binary';
		$post[] = 'content-type: text/csv';
		$post[] = '';
		$post[] = $src_data;

		// Username
		$post[] = sprintf('--%s', $mark);
		$post[] = 'content-disposition: form-data; name="username"';
		$post[] = '';
		$post[] = $this->_cfg['username'];

		// RVT
		$post[] = sprintf('--%s', $mark);
		$post[] = 'content-disposition: form-data; name="__RequestVerificationToken"';
		$post[] = '';
		$post[] = $csrf; // Where to get this text?

		// Closer and Combine
		$post[] = sprintf('--%s--', $mark);
		$post = implode("\r\n", $post);

		return [
			'body' => $post,
			'mark' => $mark,
		];
	}

	/**
	 * Get time From the CSV Filename
	 *
	 * @return DateTime
	 */
	function csv_file_date(string $csv_file)
	{
		/*
		 * error-response-file from the LCB sometimes are missing the
		 * milliseconds portion of the time in the file name
		 * So we have to patch it so it parses the same as their "normal"
		 */
		$csv_time = preg_match('/(\w+_)?\w+_(\d+T\d+)\.csv/i', $csv_file, $m) ? $m[2] : null;
		if (strlen($csv_time) == 15) {
			$csv_time = $csv_time . '000';
		}

		$csv_time = \DateTime::createFromFormat('Ymd\TGisv', $csv_time, $this->_tz);

		return $csv_time;

	}

	/**
	 * Create an ID for use in CCRS
	 */
	static function create_id() : string
	{
		return substr(_ulid(), 0, 16);
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
		// So we replace those with SPACE notation.
		// And their exports are in TSV, so we make sure we don't send those either.
		array_walk($row, function(&$val, $key) {
			$val = self::sanatize($val);
			// $val = str_replace("\t", ' ', $val); // TAB
			// $val = str_replace('"', ' ', $val); // Double Quote
			// $val = str_replace(',', ' ', $val); // Comma
			// $val = trim($val);
		});

		// it's unquoted and not
		fwrite($output_csv, implode(',', $row));
		fwrite($output_csv, "\r\n"); // CRLF
	}

	/**
	 * Take FileName Part and return our table and path names
	 */
	static function map_filename_to_object($t) : string {

		switch (strtoupper($t)) {
		case 'AREA':
			return 'section';
		case 'MANIFEST':
			return 'b2b/outgoing/file';
		case 'PLANT':
			return 'crop';
		case 'SALE':
			return 'b2b/outgoing';
		case 'STRAIN':
			return 'variety';
		case 'INVENTORY':
		case 'PRODUCT';
			return strtolower($t);
		default:
			throw new \Exception(sprintf('Invalid Object Type "%s"', $t));
		}
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
			case '018NY6XC00PR0DUCTTYPEWBZT7': return 'EndProduct';
			case '018NY6XC00PT0WQP2XV5KNP395': return 'EndProduct';
			case '018NY6XC00PT25F95HPG583AJB': return 'EndProduct';
			case '018NY6XC00PT2BKFPCEFB9G1Z2': return 'PropagationMaterial';
			case '018NY6XC00PT3EZZ4GN6105M64': return 'PropagationMaterial';
			case '018NY6XC00PT63ECNBAZH32YC3': return 'IntermediateProduct';
			case '018NY6XC00PT684JJSXN8RAWBM': return 'EndProduct';
			case '018NY6XC00PT7N83PFNCX8ZFEF': return 'EndProduct';
			case '018NY6XC00PT8ZPGMPR8H2TAXH': return 'HarvestedMaterial';
			case '018NY6XC00PTAF3TFBB51C8HX6': return 'HarvestedMaterial';
			case '018NY6XC00PTYM8J81K9HFGEMQ': return 'HarvestedMaterial';
			case '018NY6XC00PTBJ3G5FDAJN60EX': return 'EndProduct';
			case '018NY6XC00PTBNDY5VJ8JQ6NKP': return 'EndProduct';
			case '018NY6XC00PTCS5AZV189X1YRK': return 'EndProduct';
			case '018NY6XC00PTD9Q4QPFBH0G9H2': return 'EndProduct';
			case '018NY6XC00PTFY48D1136W0S0J': return 'PropagationMaterial';
			case '018NY6XC00PTGBW49J6YD3WM84': return 'HarvestedMaterial';
			case '018NY6XC00PTGMB39NHCZ8EDEZ': return 'EndProduct';
			case '018NY6XC00PTGRX4Q9SZBHDA5Z': return 'EndProduct';
			case '018NY6XC00PTHE7GWB4QTG4JKZ': return 'EndProduct';
			case '018NY6XC00PTHP9NMJ1RE6TA62': return 'IntermediateProduct';
			case '018NY6XC00PTHPB8YG56S0MCAC': return 'EndProduct';
			case '018NY6XC00PTKYYGMRSKV4XNH7': return 'EndProduct';
			case '018NY6XC00PTNPA4TPCYSKD5XN': return 'EndProduct';
			case '018NY6XC00PTR9M5Z9S4T31C4R': return 'EndProduct';
			case '018NY6XC00PTRPPDT8NJY2MWQW': return 'PropagationMaterial';
			case '018NY6XC00PTSF5NTC899SR0JF': return 'EndProduct';
			case '018NY6XC00PTXB19AQ8N8RW33A': return 'IntermediateProduct';
			case '018NY6XC00PTY5XPA4KJT6W3K4': return 'IntermediateProduct';
			case '018NY6XC00PTY9THKSEQ8NFS1J': return 'PropagationMaterial';
			case '018NY6XC00PTZZWCH7XVREHK6T': return 'HarvestedMaterial';
			case '018NY6XC00PT8AXVZGNZN3A0QT': return 'HarvestedMaterial';
			case '018NY6XC00PR0DUCTTYPEYZG3N': return 'HarvestedMaterial';
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
			case '018NY6XC00PR0DUCTTYPEWBZT7': return 'Solid Edible';
			case '018NY6XC00PT0WQP2XV5KNP395': return 'Topical Ointment';
			case '018NY6XC00PT25F95HPG583AJB': return 'Capsule';
			case '018NY6XC00PT2BKFPCEFB9G1Z2': return 'Plant';
			case '018NY6XC00PT3EZZ4GN6105M64': return 'Plant';
			case '018NY6XC00PT63ECNBAZH32YC3': return 'Cannabis Mix';
			case '018NY6XC00PT684JJSXN8RAWBM': return 'Ethanol Concentrate';
			case '018NY6XC00PT7N83PFNCX8ZFEF': return 'Liquid Edible';
			case '018NY6XC00PT8ZPGMPR8H2TAXH': return 'Other Material Lot';
			case '018NY6XC00PTAF3TFBB51C8HX6': return 'Flower Lot';
			case '018NY6XC00PTYM8J81K9HFGEMQ': return 'Flower Lot';
			case '018NY6XC00PTBJ3G5FDAJN60EX': return 'Suppository';
			case '018NY6XC00PTBNDY5VJ8JQ6NKP': return 'Solid Edible';
			case '018NY6XC00PTCS5AZV189X1YRK': return 'Hydrocarbon Concentrate';
			case '018NY6XC00PTD9Q4QPFBH0G9H2': return 'Tincture';
			case '018NY6XC00PTFY48D1136W0S0J': return 'Plant';
			case '018NY6XC00PTGBW49J6YD3WM84': return 'Other Material Unlotted';
			case '018NY6XC00PTGMB39NHCZ8EDEZ': return 'Usable Cannabis';
			case '018NY6XC00PTGRX4Q9SZBHDA5Z': return 'Cannabis Mix Infused';
			case '018NY6XC00PTHE7GWB4QTG4JKZ': return 'Sample Jar';
			case '018NY6XC00PTHP9NMJ1RE6TA62': return 'Food Grade Solvent Concentrate';
			case '018NY6XC00PTHPB8YG56S0MCAC': return 'Transdermal';
			case '018NY6XC00PTKYYGMRSKV4XNH7': return 'Cannabis Mix Packaged';
			case '018NY6XC00PTNPA4TPCYSKD5XN': return 'Non-Solvent Based Concentrate';
			case '018NY6XC00PTR9M5Z9S4T31C4R': return 'CO2 Concentrate';
			case '018NY6XC00PTRPPDT8NJY2MWQW': return 'Plant';
			case '018NY6XC00PTSF5NTC899SR0JF': return 'Cannabis Mix Infused'; // Concentrate For Inhalation
			case '018NY6XC00PTXB19AQ8N8RW33A': return 'CBD';
			case '018NY6XC00PTY5XPA4KJT6W3K4': return 'Infused Cooking Medium';
			case '018NY6XC00PTY9THKSEQ8NFS1J': return 'Seed';
			case '018NY6XC00PTZZWCH7XVREHK6T': return 'Flower Unlotted';
			case '018NY6XC00PT8AXVZGNZN3A0QT': return 'Waste';
			case '018NY6XC00PR0DUCTTYPEYZG3N': return 'Flower Unlotted';
			default:
				throw new \Exception("Type '$x' Not Handled [CLC-194]");
		}
	}

	/**
	 * Sanatize a value for save usage in CCRS
	 */
	static function sanatize(?string $t, $l=-1)
	{

		// Character Cleanup
		$t = str_replace([ "\t" ], ' ', $t);
		$t = str_replace([ '"', ',', ], '', $t);
		$t = trim($t);

		// Length Trim
		if ($l > 0) {
			$t = substr($t, 0, $l);
		}

		$t = trim($t);

		return $t;

	}

}
