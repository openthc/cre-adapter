<?php
/**
 * BioTrackTHC Interface - ND
 * @see https://www.biotrack.com/north-dakota/
 */

namespace OpenTHC\CRE\Adapter\Biotrack;

class ND extends \OpenTHC\CRE\Adapter\BioTrack
{
	protected $_api_base = 'https://mminventory.health.nd.gov/serverxml.asp';
}
