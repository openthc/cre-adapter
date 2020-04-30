<?php
/**
 * BioTrackTHC Interface - IL
 * @see https://www.biotrack.com/illinois/
 */

namespace OpenTHC\CRE\Adapter\Biotrack;

class IL extends \OpenTHC\CRE\Adapter\BioTrack
{
	protected $_api_base = 'https://mcmonitoring.agr.illinois.gov/serverjson.asp';
}
