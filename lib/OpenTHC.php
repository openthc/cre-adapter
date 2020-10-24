<?php
/**
 * Interface to the OpenTHC CRE
 */

namespace OpenTHC\CRE;

class OpenTHC extends \OpenTHC\CRE\Base
{
	const ENGINE = 'openthc';

	protected $_api_base = 'https://cre.openthc.dev';
	protected $_api_host = 'cre.openthc.dev';

	/**
	 * Array of Arguments
	 */
	function __construct($sid=null)
	{
		// @todo Make this Session Persistent?
		$jar = new \GuzzleHttp\Cookie\CookieJar();
		if (!empty($sid)) {
			$c = new \GuzzleHttp\Cookie\SetCookie(array(
				'Domain' => $this->_api_host,
				'Name' => 'openthc',
				'Value' => $sid,
				'Secure' => true,
				'HttpOnly' => true,
			));
			$jar->setCookie($c);
		}

		$cfg = array(
			'base_uri' => $this->_api_base,
			'allow_redirects' => false,
			'cookies' => $jar,
			'headers' => array(
				'user-agent' => sprintf('OpenTHC/%s', APP_BUILD),
			),
			'http_errors' => false,
			'verify' => false,
		);
		//var_dump($cfg);

		// Override Host Header Here
		// @see https://github.com/guzzle/guzzle/issues/1678#issuecomment-281921604
		// $host = $this->_api_host;
		// $ghhs = \GuzzleHttp\HandlerStack::create();
		// $ghhs->push(\GuzzleHttp\Middleware::mapRequest(function (\Psr\Http\Message\RequestInterface $R) use ($host) {
		// 	return $R->withHeader('host', $host);
		// }));
		// $cfg['handler'] = $ghhs;

		$this->_c = new \GuzzleHttp\Client($cfg);

	}

	/**
		Format Error
	*/
	function formatError($e)
	{
		if (is_array($e)) {
			if (!empty($e['detail'])) {
				return $e['detail'];
			}
		}

		return json_encode($e, JSON_PRETTY_PRINT);

	}

	/**
	*/
	function listSyncObjects()
	{
		return array(
			'license' => 'License',
			'section' => 'Section',
			'plant' => 'Plant',
			'lot' => 'Lot',
			'transfer' => 'Transfer',
		);
	}


	/**
	 * HTTP GET Utility
	*/
	function get($url)
	{
		$res = $this->_c->get($url);

		$ret = null;
		switch ($res->getStatusCode()) {
		case 200:
		case 201:
		case 403:
		case 404:
		case 410:
		case 423:
			$ret = json_decode($res->getBody(), true);
			$ret['code'] = $res->getStatusCode();
			break;
		default:
			// _exit_text($res->getStatusCode() . ': ' . $res->getBody());
			throw new Exception('Invalid Response from OpenTHC [LRO#080]');
		}

		return $ret;
	}

	/**
	 * HTTP GET Utility
	*/
	function head($url)
	{
		$res = $this->_c->head($url);
		return $res;
	}

	/**
	 * HTTP POST Utility
	 */
	function post($url, $arg)
	{
		$res = $this->_c->post($url, [ 'form_params' => $arg ]);

		$hsc = $res->getStatusCode();

		$ret = null;
		switch ($hsc) {
		case 200:
		case 201:
		case 202:
		case 404:
		case 409:
		case 410:
			$ret = json_decode($res->getBody(), true);
			$ret['code'] = $hsc;
			break;
		default:
			$buf = $res->getBody()->getContents();
			var_dump($buf);
			echo '<div>';
			echo $buf;
			echo '</div>';
			throw new Exception(sprintf('Invalid Response Code: %03d from OpenTHC [LRO#103]', $hsc));
		}

		return $ret;
	}

	/**
	 * HTTP Patch utility
	 */
	function patch($url, $arg)
	{
		$res = $this->_c->patch($url, [ 'json' => $arg ]);

		// Copied from $this->post() /mbw
		$hsc = $res->getStatusCode();

		$ret = null;
		switch ($hsc) {
		case 200:
			$ret = json_decode($res->getBody(), true);
			$ret['code'] = $hsc;
			break;
		default:
			$buf = $res->getBody()->getContents();
			var_dump($buf);
			echo '<div>';
			echo $buf;
			echo '</div>';
			throw new Exception(sprintf('Invalid Response Code: %03d from OpenTHC [LRO#103]', $hsc));
		}

		return $ret;
	}

	/**
	 * HTTP DELETE Utility
	 */
	function delete($url, $arg=null)
	{
		if (!empty($arg)) {
			$arg = array('form_params' => $arg);
		}

		$res = $this->_c->delete($url, $arg);

		$hsc = $res->getStatusCode();

		$ret = null;
		switch ($hsc) {
		case 200:
		case 202:
		case 204:
		case 404:
		case 410:
		case 423:
			$ret = json_decode($res->getBody(), true);
			$ret['code'] = $hsc;
			break;
		default:
			// _exit_text([ 'fail' => 'Invalid Response from OpenTHC [LRO#176]', 'code' => $hsc, 'body' => $res->getBody() ]);
			throw new Exception('Invalid Response from OpenTHC [LRO#176]');
		}

		return $ret;
	}

	/**
		Authnentication Interfaces
	*/
	function auth($p)
	{
		$r = $this->post('/auth/open', $p);
		return $r;
	}

	function ping()
	{
		$r = $this->get('/auth/ping');
		return $r;
	}

	/**
		Get the Company interface
	*/
	function company()
	{
		// return new RBE_OpenTHC_Company($this->_c);
		//$r = $this->_c->get('/config/company');
		//echo $r->getBody()->__toString();
		//return json_decode($r->getBody(), true);
		return new RBE_OpenTHC_Company($this);
	}

	/**
		Get the Contact interface
	*/
	function contact()
	{
		//$r = $this->_c->get('/config/contact');
		//echo $r->getBody()->__toString();
		//return json_decode($r->getBody(), true);
		return new RBE_OpenTHC_Contact($this);
	}

	/**
		Get the License interface
	*/
	function license()
	{
		//$r = $this->_c->get('/config/license');
		//echo $r->getBody()->__toString();
		//return json_decode($r->getBody(), true);
		return new RBE_OpenTHC_License($this);
	}

	/**
		Get the Batch interface
	*/
	// function batch()
	// {
	// 	return new RBE_OpenTHC_Batch($this);
	// }

	/**
		Get the Lot interface
	*/
	function lot()
	{
		return new RBE_OpenTHC_Lot($this);
	}
	function inventory() // Legacy Alias
	{
		return new RBE_OpenTHC_Lot($this);
	}

	/**
		Get the Plant interface
	*/
	function plant()
	{
		return new RBE_OpenTHC_Plant($this);
	}

	/**
		Get the Product interface
	*/
	function product()
	{
		return new RBE_OpenTHC_Product($this);
	}

	/**
		Wholesale & Retail
	*/
	function b2c()
	{
		return new RBE_OpenTHC_Sales($this);
	}
	function sales()
	{
		return new RBE_OpenTHC_Sales($this);
	}

	/**
		Get the Strain interface
	*/
	function strain()
	{
		return new RBE_OpenTHC_Strain($this);
	}

	/**
		Get the B2B interface
	*/
	function b2b()
	{
		return new RBE_OpenTHC_Transfer($this);
	}
	function transfer() // Legacy Name
	{
		return new RBE_OpenTHC_Transfer($this);
	}

	/**
		Get the Section interface
	*/
	function section()
	{
		return new RBE_OpenTHC_Section($this);
	}

}
