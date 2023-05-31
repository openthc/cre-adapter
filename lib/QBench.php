<?php
/**
 * QBench Lab API Connector
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

namespace OpenTHC\CRE;

class QBench extends Base
{
	const ENGINE = 'qbench';

	private $_access_token;

	protected $_pk; // Public Key
	protected $_sk; // Secret Key

	/**
	 * @param $x Array of RBE Options
	 */
	function __construct(array $cfg)
	{
		if (empty($cfg['server-url'])) {
			throw new \Exception('Parameter "server-url" is required');
		}

		$this->_api_base = $cfg['server-url'];
		$this->_pk = $cfg['public-key'];
		$this->_sk = $cfg['secret-key'];
		$this->_access_token = $cfg['access-token'];

	}

	/**
	 * Authenticate
	 */
	function auth()
	{
		$t0 = time();

		$jwt = [];
		$jwt[] = __base64_encode_url(json_encode([ 'alg' => 'HS256', 'typ' => 'JWT' ]));
		$jwt[] = __base64_encode_url(json_encode([
			'exp' => $t0 + 3600 - 1
			, 'iat' => $t0
			, 'sub' => $this->_pk
		]));
		$sig = hash_hmac('sha256', implode('.', $jwt), $this->_sk, $raw=true);
		$sig = __base64_encode_url($sig);
		$jwt[] = $sig;

		$req = __curl_init($this->_api_base . '/oauth2/v1/token');
		curl_setopt($req, CURLOPT_POST, true);
		curl_setopt($req, CURLOPT_POSTFIELDS, [
			'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
			'assertion' => implode('.', $jwt)
		]);
		$res = curl_exec($req);
		$inf = curl_getinfo($req);

		$tok = json_decode($res, true);

		$this->_access_token = $tok['access_token'];

		return $tok;

	}

	/**
	 * Get Something
	 */
	function get($url)
	{
		$url = ltrim($url, '/');
		$url = sprintf('%s/%s', $this->_api_base, $url);

		$req = __curl_init($url);

		$req_head = [];
		$req_head[] = sprintf('authorization: Bearer %s', $this->_access_token);
		curl_setopt($req, CURLOPT_HTTPHEADER, $req_head);

		// curl_setopt($req, CURLOPT_VERBOSE, true);
		// $ofh = fopen(__DIR__ . '/curl-output.log', 'a');
		// curl_setopt($req, CURLOPT_STDERR, $ofh);

		$res = curl_exec($req);
		$inf = curl_getinfo($req);
		$res = json_decode($res, true);

		return $res;

	}

	/**
	 * HEAD Something
	 */
	function head($url)
	{
		$url = ltrim($url, '/');
		switch (substr($url, 0, 7)) {
		case 'http://':
		case 'https:/':
			// It's a full URL
			break;
		default:
			$url = sprintf('%s/%s', $this->_api_base, $url);
		}

		$req = __curl_init($url);

		$req_head = [];
		$req_head[] = sprintf('authorization: Bearer %s', $this->_access_token);
		curl_setopt($req, CURLOPT_HTTPHEADER, $req_head);

		curl_setopt($req, CURLOPT_CUSTOMREQUEST, 'HEAD');
		curl_setopt($req, CURLOPT_NOBODY, true);

		$res = curl_exec($req);
		$inf = curl_getinfo($req);
		$res = json_decode($res, true);

		return $res;

	}

	/**
	 * Get the List of Accessioning Type
	 */
	function getAccessionList()
	{
		static $lab_accession_list = [];

		$res = $qbc->get('/api/v1/accessioningtype');
		foreach ($res['data'] as $x) {
			$x['@id'] = sprintf('qbench:%d', $x['id']);
			// var_dump($x);
		}

		return $lab_accession_list;

	}

	/**
	 * Get the List of Assay
	 * The Assay is used as the Name of a Lab_Result
	 */
	function getAssayList()
	{
		static $lab_assay_list = [];

		$res = $qbc->get('/api/v1/assay');
		foreach ($res['data'] as $x) {
			$x['@id'] = sprintf('qbench:%d', $x['id']);
			$lab_assay_list[$x['@id']] = $x;
			// printf("Assay: %s / '%s'\n", $x['@id'], $x['title']);
		}

		return $lab_assay_list;

	}

	/**
	 * Get the List of Panels
	 */
	function getPanelList()
	{
		static $lab_panel_list = [];

		if (empty($lab_panel_list)) {
			$res = $qbc->get('/api/v1/panel');
			foreach ($res['data'] as $x) {
				$x['@id'] = sprintf('qbench:%d', $x['id']);
				$lab_panel_list[$x['@id']] = $x;
				// printf("Panel: %s / '%s'\n", $x['@id'], $x['title']);
			}
		}

		return $lab_panel_list;
	}

}
