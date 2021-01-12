<?php
/**
 * PHP Unit Test Bootstrap
 */

error_reporting(E_ALL & ~ E_NOTICE);

// Autoload
require_once(dirname(__DIR__) . '/vendor/autoload.php');

// Test Base Classes
require_once(__DIR__ . '/Base_Test.php');
require_once(__DIR__ . '/BioTrack_Test.php');
require_once(__DIR__ . '/LeafData_Test.php');
require_once(__DIR__ . '/Metrc_Test.php');
// require_once(__DIR__ . '/OpenTHC_Test.php');
