<?php
/**
 * BioTrackTHC Interface - ND
 * @see https://www.biotrack.com/north-dakota/
 */

namesapce OpenTHC\CRE\BioTrack;

class ND extends \OpenTHC\CRE\BioTrack
{
	protected $_name = 'NDDOH';
	protected $_api_base = 'https://mminventory.health.nd.gov/serverxml.asp';
}
