<?php
/**
 * Interface for Cova
 *
 * SPDX-License-Identifier: MIT
 *
 * @see https://api.covasoft.net/Documentation
 */

namespace OpenTHC\CRE;

class Cova extends \OpenTHC\CRE\Base
{
	const ENGINE = 'cova';

	/**
	 *
	 */
	function __construct($cfg)
	{
		parent::__construct($cfg);

		$cfg = array(
			'base_uri' => $this->_api_base,
			'allow_redirects' => false,
			'cookies' => true,
			// 'headers' => $head,
			'http_errors' => false,
		);

		$this->_c = new \GuzzleHttp\Client($cfg);

	}

	/**
	 *
	 */
	function post_v2($url, $arg)
	{
		// The $handler variable is the handler passed in the
		// options to the client constructor.
		$res = $this->_c->request('POST', $url, $arg);

		// $res = $this->_c->post($url, $arg);

		$this->_res_code = $res->getStatusCode();
		$this->_res_body = $res->getBody()->getContents();

		return [
			'code' => $this->_res_code,
			'data' => $this->_res_body,
		];

	}

}
