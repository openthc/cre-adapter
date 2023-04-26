<?php
/**
 * Lab Result Helper for BioTrack
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\BioTrack;

class Lab_Metric
{
	private $_t;

	// Mapping
	static $_type_map = [
		'1' => [
			'id' => '018NY6XC00LMT0BY5GND653C0C',
			'name' => 'Moisture Content',
		],
		'2' => [
			'id' => '018NY6XC00LMT0HRHFRZGY72C7',
			'name' => 'Potency Analysis',
		],
		'3' => [
			'id' => '',
			'name' => 'Foreign Matter Inspection',
		],
		'4' => [
			'id' => '018NY6XC00LMT0B7NMK7RGYAMN',
			'name' => 'Microbiological Screening',
		],
		'5' => [
			'id' => '018NY6XC00LMT0AQAMJEDSD0NW',
			'name' => 'Residual Solvent',
		],
		'6' => [
			'id' => '018NY6XC00LMT0GDBPF0V9B71Z',
			'name' => 'Mycotoxin Screening',
		],
	];

	static $_map = [
		'moisture' => [
			'id' => '018NY6XC00LM0PXPG4592M8J14',
			'name' => 'Moisture Content',
			'type' => '1',
			'uom' => 'ppm',
		],
		'thc' => [
			'id' => '018NY6XC00PXG4PH0TXS014VVW',
			'name' => 'THC',
			'type' => '2',
			'uom' => 'pct',
		],
		'thc-total' => [
			'id' => '01FQ28WTJTE5ZMWXW2D2E4TRNC',
			'name' => 'THC Total',
			'type' => '2',
			'uom' => 'pct',
		],
		'thca' => [
			'id' => '018NY6XC00LMB0JPRM2SF8F9F2',
			'name' => 'THCA',
			'type' => '2',
			'uom' => 'pct',
		],
		'cbd' => [
			'id' => '018NY6XC00DEEZ41QBXR2E3T97',
			'name' => 'CBD',
			'type' => '2',
			'uom' => 'pct',
		],
		'cbd-total' => [
			'id' => '01FQ28WNW8744EJ6WDW70XPAMZ',
			'name' => 'CBD Total',
			'type' => '2',
			'uom' => 'pct',
		],
		'cbda' => [
			'id' => '018NY6XC00LMENDHEH2Y32X903',
			'name' => 'CBDA Content',
			'type' => '2',
			'uom' => 'pct',
		],
		'total' => [
			'id' => '018NY6XC00SAE8Q4JSMF40YSZ3',
			'name' => 'Total Cannabinoid Profile',
			'type' => '2',
			'uom' => 'pct',
		],
		'stems' => [
			'id' => '018NY6XC00LMQAZZSDXPYH62SS',
			'name' => 'Stems',
			'type' => '1',
			'uom' => 'bool',
		],
		'other' => [
			'id' => '018NY6XC00LMHGENRW0DAPFQRZ',
			'name' => 'Other Material',
			'type' => '1',
			'uom' => 'bool',
		],
		'aerobic_bacteria' => [
			'id' => '018NY6XC00LMFPY3XH8NNXM9TH',
			'name' => 'Total viable aerobic bacteria count',
			'type' => '4',
			'uom' => 'cfu',
		],
		'yeast_and_mold' => [
			'id' => '018NY6XC00LMCPKZ3QB78GQXWP',
			'name' => 'Total yeast and mold count',
			'type' => '4',
			'uom' => 'cfu',
		],
		'coliforms' => [
			'id' => '018NY6XC00LMTMR8TN8WE86JVY',
			'name' => 'Total coliforms count',
			'type' => '4',
			'uom' => 'cfu',
		],
		'bile_tolerant' => [
			'id' => '018NY6XC00LM638QCGB50ZKYKJ',
			'name' => 'Bile-tolerant gram-negative bacteria',
			'type' => '4',
			'uom' => 'cfu',
		],
		'e_coli_and_salmonella' => [
			'id' => '018NY6XC00LM7S8H2RT4K4GYME',
			'name' => 'E. coli and Salmonella',
			'type' => '4',
			'uom' => 'cfu',
		],
		'residual_solvent' => [
			'id' => '',
			'name' => 'Residual Solvents',
			'type' => '5',
			'uom' => '',
		],
		'total_mycotoxins' => [
			'id' => '018NY6XC00LMR9PB7SNBP97DAS',
			'name' => 'Total Mycotoxins',
			'type' => '6',
			'uom' => 'ppb',
		],
	];
}
