<?php
/**
 * Product Type Interface for WSLCB CCRS
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\CCRS\Product;

class Type
{
	private $_openthc_id = '';

	private $_ccrs_part_0 = '';

	private $_ccrs_part_1 = '';

	private $_map = [
		// '018NY6XC00PR0DUCTTYPE00000' => ['-system-', '-system-'],
		'018NY6XC00PR0DUCTTYPE5Q53T' => [ 'EndProduct', 'Usable Cannabis' ],
		'018NY6XC00PR0DUCTTYPEKHRP2' => [ 'IntermediateProduct', 'Other Material Unlotted' ],
		'018NY6XC00PR0DUCTTYPET87RH' => [ 'IntermediateProduct', 'Non-Solvent Based Concentrate' ],
		'018NY6XC00PR0DUCTTYPEWBZT7' => [ 'EndProduct', 'Solid Edible' ],
		'018NY6XC00PR0DUCTTYPEYZG3N' => [ 'HarvestedMaterial', 'Flower Unlotted' ],
		'018NY6XC00PT0WQP2XV5KNP395' => [ 'EndProduct', 'Topical Ointment' ],
		'018NY6XC00PT25F95HPG583AJB' => [ 'EndProduct', 'Capsule' ],
		'018NY6XC00PT2BKFPCEFB9G1Z2' => [ 'PropagationMaterial', 'Plant' ],
		'018NY6XC00PT3EZZ4GN6105M64' => [ 'PropagationMaterial', 'Plant' ],
		'018NY6XC00PT63ECNBAZH32YC3' => [ 'IntermediateProduct', 'Cannabis Mix' ],
		'018NY6XC00PT684JJSXN8RAWBM' => [ 'EndProduct', 'Ethanol Concentrate' ],
		'018NY6XC00PT6QKRGR3JYQX0BK' => [ 'IntermediateProduct', 'Non-Solvent Based Concentrate' ],
		'018NY6XC00PT7N83PFNCX8ZFEF' => [ 'EndProduct', 'Liquid Edible' ],
		'018NY6XC00PT8AXVZGNZN3A0QT' => [ 'HarvestedMaterial', 'Waste' ],
		'018NY6XC00PT8ZPGMPR8H2TAXH' => [ 'HarvestedMaterial', 'Other Material Lot' ],
		'018NY6XC00PTAF3TFBB51C8HX6' => [ 'HarvestedMaterial', 'Flower Lot' ],
		'018NY6XC00PTBJ3G5FDAJN60EX' => [ 'EndProduct', 'Suppository' ],
		'018NY6XC00PTBNDY5VJ8JQ6NKP' => [ 'EndProduct', 'Solid Edible' ],
		'018NY6XC00PTCS5AZV189X1YRK' => [ 'EndProduct', 'Hydrocarbon Concentrate' ],
		'018NY6XC00PTD9Q4QPFBH0G9H2' => [ 'EndProduct', 'Tincture' ],
		'018NY6XC00PTFY48D1136W0S0J' => [ 'PropagationMaterial', 'Plant' ],
		'018NY6XC00PTGBW49J6YD3WM84' => [ 'HarvestedMaterial', 'Other Material Unlotted' ],
		'018NY6XC00PTGMB39NHCZ8EDEZ' => [ 'EndProduct', 'Usable Cannabis' ],
		'018NY6XC00PTGRX4Q9SZBHDA5Z' => [ 'EndProduct', 'Cannabis Mix Infused' ],
		'018NY6XC00PTHE7GWB4QTG4JKZ' => [ 'EndProduct', 'Sample Jar' ],
		'018NY6XC00PTHP9NMJ1RE6TA62' => [ 'IntermediateProduct', 'Food Grade Solvent Concentrate' ],
		'018NY6XC00PTHPB8YG56S0MCAC' => [ 'EndProduct', 'Transdermal' ],
		'018NY6XC00PTKYYGMRSKV4XNH7' => [ 'EndProduct', 'Cannabis Mix Packaged' ],
		'018NY6XC00PTNPA4TPCYSKD5XN' => [ 'EndProduct', 'Non-Solvent Based Concentrate' ],
		'018NY6XC00PTR9M5Z9S4T31C4R' => [ 'EndProduct', 'CO2 Concentrate' ],
		'018NY6XC00PTRPPDT8NJY2MWQW' => [ 'PropagationMaterial', 'Plant' ],
		'018NY6XC00PTSF5NTC899SR0JF' => [ 'EndProduct', 'Cannabis Mix Infused' ],
		'018NY6XC00PTXB19AQ8N8RW33A' => [ 'IntermediateProduct', 'CBD' ],
		'018NY6XC00PTY5XPA4KJT6W3K4' => [ 'IntermediateProduct', 'Infused Cooking Medium' ],
		'018NY6XC00PTY9THKSEQ8NFS1J' => [ 'PropagationMaterial', 'Seed' ],
		'018NY6XC00PTYM8J81K9HFGEMQ' => [ 'HarvestedMaterial',  'Flower Lot' ],
		'018NY6XC00PTZZWCH7XVREHK6T' => [ 'HarvestedMaterial', 'Flower Unlotted' ],

	];

	function __construct($want)
	{
		if (preg_match('/^\w{26}$/', $want)) {
			// It's a ULID
			if ( ! empty($this->_map[$want])) {

				$this->_openthc_id = $want;

				$ccrs_type = $this->_map[$this->_openthc_id];

				// $ccrs_part_list = $this->map_from_ulid($this->_openthc_id);
				$this->_ccrs_part_0 = $ccrs_type[0];// $this->map_from_ulid($this->_openthc_id);
				$this->_ccrs_part_1 = $ccrs_type[1]; // $this->map_from_ulid($this->_openthc_id);

			}
		} elseif (preg_match('/^(\w+)\/(\w+)$/', $want, $m)) {

		}

		if (empty($this->_openthc_id)) {
			throw new \Exception("Product Type '$want' Not Handled [CPT-074]");
		}

	}

	function getCategoryName()
	{
		return $this->_ccrs_part_0;
	}

	function getTypeName()
	{
		return $this->_ccrs_part_1;
	}

}
