<?php
/**
 * BioTrackTHC Interface - ME
 * @see https://www.maine.gov/dafs/omp/
 * @see https://www.biotrack.com/maine/
 */

class RBE_BioTrack_ME extends RBE_BioTrack
{
	protected $_name = 'MEOMP';
	protected $_api_base = 'https://me-qa01.biotrackthc.net/serverjson.asp';
}
