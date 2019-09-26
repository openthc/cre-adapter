<?php
/**
 * BioTrackTHC Interface - New Mexico
 * socat -v TCP-LISTEN:8080,bind=localhost,fork OPENSSL:mcp-tracking.nmhealth.org:443,verify=0 2>&1|tee -a nm-api.log
 */

namesapce OpenTHC\CRE\BioTrack;

class NM extends \OpenTHC\CRE\BioTrack
{
	// SSL Cert uses this name
	protected $_name = 'NMDOH';
	protected $_api_base = 'https://mcp-tracking.nmhealth.org/serverjson.asp';
	protected $_api_host = 'mcp-tracking.nmhealth.org';

	//protected $_api_base = 'http://localhost:8080/serverjson.asp';

	/**
	 * listSyncObjects but strips out the two objects not used in New Mexito
	 * @return array of syncObjects
	 */
	function listSyncObjects()
	{
		$ret = parent::listSyncObjects();
		unset($ret['third_party_transporter']);
		unset($ret['id_preassign']);
		return $ret;
	}

}
