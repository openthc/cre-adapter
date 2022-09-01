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
	function __construct($x)
	{
		if (empty($x['server-url'])) {
			throw new \Exception('Parameter "server-url" is required');
		}

		$this->_api_base = $x['server-url'];
		$this->_pk = $x['public-key'];
		$this->_sk = $x['secret-key'];

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
		$url = sprintf('%s/%s', $this->_api_base, $url);

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

}
