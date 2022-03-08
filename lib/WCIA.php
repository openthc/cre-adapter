<?php
/**
 * WCIA Utility Class
 * For https://cannabisintegratorsalliance.com/
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE;

class WCIA extends \OpenTHC\CRE\Base
{
	const ENGINE = 'openthc';

	function __construct() { /* NO */ }


	/**
	 * Remaps the WCIA Product Type
	 */
	function _wcia_map_product_type_id($t0, $t1)
	{
		// $t0 = $x['meta']['_source']['inventory_category'];
		// $t1 = $x['meta']['_source']['inventory_type'];
		$pt = sprintf('%s/%s', $t0, $t1);
		switch ($pt) {
			case 'EndProduct/Capsules':                         return '018NY6XC00PT25F95HPG583AJB';
			case 'EndProduct/Solid Edible':                     return '018NY6XC00PTBNDY5VJ8JQ6NKP';
			case 'HarvestedMaterial/Flower Lot':                return '018NY6XC00PTAF3TFBB51C8HX6';
			case 'IntermediateProduct/CO2 Concentrate':         return '018NY6XC00PTR9M5Z9S4T31C4R';
			case 'IntermediateProduct/Hydrocarbon Concentrate': return '018NY6XC00PTCS5AZV189X1YRK';
			case 'IntermediateProduct/Marijuana Mix':           return '018NY6XC00PT63ECNBAZH32YC3';
			case 'IntermediateProduct/Non-Solvent Based Concentrate': return '018NY6XC00PTNPA4TPCYSKD5XN';
			case 'IntermediateProduct/Concentrate for Inhalation': return '018NY6XC00PTNPA4TPCYSKD5XN';
			default:
				// Failsafe
				switch ($t1) {
					case 'Topical Ointment';         return '018NY6XC00PT0WQP2XV5KNP395';
					case 'Capsules';                 return '018NY6XC00PT25F95HPG583AJB';
					case 'Plant';                    return '018NY6XC00PT2BKFPCEFB9G1Z2';
					case 'Plant';                    return '018NY6XC00PT3EZZ4GN6105M64';
					case 'Ethanol Concentrate';      return '018NY6XC00PT684JJSXN8RAWBM';
					case 'Liquid Edible';            return '018NY6XC00PT7N83PFNCX8ZFEF';
					case 'Waste';                    return '018NY6XC00PT8AXVZGNZN3A0QT';
					case 'Other Material Lot';       return '018NY6XC00PT8ZPGMPR8H2TAXH';
					case 'Suppository';              return '018NY6XC00PTBJ3G5FDAJN60EX';
					case 'Solid Edible';             return '018NY6XC00PTBNDY5VJ8JQ6NKP';
					case 'Tincture';                 return '018NY6XC00PTD9Q4QPFBH0G9H2';
					case 'Plant';                    return '018NY6XC00PTFY48D1136W0S0J';
					case 'Other Material Unlotted';  return '018NY6XC00PTGBW49J6YD3WM84';
					case 'Usable Marijuana';         return '018NY6XC00PTGMB39NHCZ8EDEZ';
					case 'Marijuana Mix Infused';    return '018NY6XC00PTGRX4Q9SZBHDA5Z';
					case 'Sample Jar';               return '018NY6XC00PTHE7GWB4QTG4JKZ';
					case 'Food Grade Solvent Concentrate';    return '018NY6XC00PTHP9NMJ1RE6TA62';
					case 'Transdermal';              return '018NY6XC00PTHPB8YG56S0MCAC';
					case 'Marijuana Mix Packaged';   return '018NY6XC00PTKYYGMRSKV4XNH7';
					case 'Non-Solvent Based Concentrate';
					case 'Plant';                    return '018NY6XC00PTRPPDT8NJY2MWQW';
					case 'Marijuana Mix Infused'; // Concentrate For Inhalation    return '018NY6XC00PTSF5NTC899SR0JF';
					case 'Infused Cooking Medium';   return '018NY6XC00PTY5XPA4KJT6W3K4';
					case 'Seed';                     return '018NY6XC00PTY9THKSEQ8NFS1J';
					case 'Flower Unlotted';          return '018NY6XC00PTZZWCH7XVREHK6T';
				}
				var_dump($x);
				throw new \Exception(_(sprintf('Unexpected Product Type "%s"', $pt)));
		}

		return '018NY6XC00PR0DUCTTYPE00001'; // -orphan-

	}

}
