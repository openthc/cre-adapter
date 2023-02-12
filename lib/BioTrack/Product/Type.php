<?php
/**
 * Product Type Helper for BioTrack
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\BioTrack\Product;

class Type
{
	private $_t;

	// Mapping
	private $_map = [
		'5' => [
			'id' => '018NY6XC00PTNPA4TPCYSKD5XN',  // Bulk / Kief
			'name' => 'Kief',
		],
		'6' => [
			'id' => '018NY6XC00PTZZWCH7XVREHK6T',  // Bulk / Flower
			'name' => 'Flower',
		],
		'7' => [
			'id' => '018NY6XC00PT3EZZ4GN6105M64',  // Bulk / Clone
			'name' => 'Clone',
		],
		'9' => [
			'id' => '018NY6XC00PTGBW49J6YD3WM84',  // Bulk / Other Material
			'name' => 'Other Material',
		],
		'10' => [
			'id' => '018NY6XC00PTY9THKSEQ8NFS1J', // Bulk / Seed
			'name' => 'Seed',
		],
		'11' => [
			'id' => '018NY6XC00PT2BKFPCEFB9G1Z2', // Bulk / Plant Tissue
			'name' => 'Plant Tissue',
		],
		'12' => [
			'id' => '018NY6XC00PTRPPDT8NJY2MWQW',
			'name' => 'Mature Plant',
		],
		'13' => [
			'id' => '018NY6XC00PTAF3TFBB51C8HX6',
			'name' => 'Flower Lot',
		],
		'14' => [
			'id' => '018NY6XC00PT8ZPGMPR8H2TAXH',
			'name' => 'Other Material Lot',
		],
		'15' => [
			'id' => '018NY6XC00PT6QKRGR3JYQX0BK',
			'name' => 'Bubble Hash',
		],
		'16' => [
			'id' => '018NY6XC00PTACC942KY9DCERR',
			'name' => 'Hash',
		],
		'17' => [
			'id' => '018NY6XC00PTCS5AZV189X1YRK',
			'name' => 'Hydrocarbon Wax',
		],
		'18' => [
			'id' => '018NY6XC00PTR9M5Z9S4T31C4R',
			'name' => 'CO2 Hash Oil',
		],
		'19' => [
			'id' => '018NY6XC00PTHP9NMJ1RE6TA62',
			'name' => 'Food Grade Solvent Extract',
		],
		'20' => [
			'id' => '018NY6XC00PTNZQPYNH74BPZ1K',
			'name' => 'Infused Dairy Butter or Fat in Solid Form',
		],
		'21' => [
			'id' => '018NY6XC00PTY5XPA4KJT6W3K4',
			'name' => 'Infused Cooking Oil',
		],
		'22' => [
			'id' => '018NY6XC00PTBNDY5VJ8JQ6NKP',
			'name' => 'Solid Marijuana Infused Edible',
		],
		'23' => [
			'id' => '018NY6XC00PT7N83PFNCX8ZFEF',
			'name' => 'Liquid Marijuana Infused Edible',
		],
		'24' => [
			'id' => '018NY6XC00PTSF5NTC899SR0JF',
			'name' => 'Marijuana Extract for Inhalation',
		],
		'25' => [
			'id' => '018NY6XC00PT0WQP2XV5KNP395',
			'name' => 'Marijuana Infused Topicals',
		],
		'26' => [
			'id' => '018NY6XC00PTHE7GWB4QTG4JKZ',
			'name' => 'Sample Jar',
		],
		'27' => [
			'id' => '018NY6XC00PT8AXVZGNZN3A0QT',
			'name' => 'Waste',
		],
		'28' => [
			'id' => '018NY6XC00PTGMB39NHCZ8EDEZ',
			'name' => 'Usable Marijuana',
		],
		'29' => [
			'id' => '018NY6XC00PTYM8J81K9HFGEMQ',
			'name' => 'Wet Flower',
		],
		'30' => [
			'id' => '018NY6XC00PT63ECNBAZH32YC3',
			'name' => 'Marijuana Mix',
		],
		'31' => [
			'id' => '018NY6XC00PTKYYGMRSKV4XNH7',
			'name' => 'Marijuana Mix Packaged',
		],
		'32' => [
			'id' => '018NY6XC00PTGRX4Q9SZBHDA5Z',
			'name' => 'Marijuana Mix Infused',
		],
		'33' => [
			'id' => '018NY6XC00PTFY48D1136W0S0J',
			'name' => 'Non-Mandatory QA Sample',
		],
		'34' => [
			'id' => '018NY6XC00PT25F95HPG583AJB',
			'name' => 'Capsule',
		],
		'35' => [
			'id' => '018NY6XC00PTD9Q4QPFBH0G9H2',
			'name' => 'Tincture',
		],
		'36' => [
			'id' => '018NY6XC00PTHPB8YG56S0MCAC',
			'name' => 'Transdermal Patch',
		],
		'37' => [
			'id' => '018NY6XC00PTBJ3G5FDAJN60EX',
			'name' => 'Suppository',
		],
		// Forgot what these are
		// '39' => [
		// 	'id' => '018NY6XC00PR0DUCTTYPE5BV22', // Usable Trim == Grade-B / Package,
		// 	'name' => '',
		// ],
		// '40' => [
		// 	'id' => '018NY6XC00PR0DUCTTYPE7FH3Z',
		// 	'name' => '',
		// ],
		// '41' => [
		// 	'id' => '018NY6XC00PR0DUCTTYPEF14Q4',
		// 	'name' => '',
		// ],
	];

	/**
	 *
	 */
	function __construct(int $t)
	{
		$this->_t = intval($t);
		if ( ! isset($this->_map[ $this->_t ])) {
			throw new \Exception('Invalid Product Type [BPT-058]');
		}
	}

	/**
	 *
	 */
	function getName()
	{

	}

	/**
	 *
	 */
	function getOpenTHCID()
	{
		return self::$_map[ $this->_t ]['id'];
	}

	function getPackageType()
	{
		if ($this->isBulk()) {
			return 'bulk';
		} elseif ($this->isEach()) {
			return 'each';
		}

		throw new \Exception('Impossible Product Type [BPT-193]');
	}

	function isBulk()
	{

	}

	function isEach()
	{

	}

}
