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

	/**
	 * Could build a static map here?
	 */
	private static $product_type_map = [
		'018NY6XC00PT25F95HPG583AJB' => 'EndProduct/Capsule',
		'018NY6XC00PTBNDY5VJ8JQ6NKP' => 'EndProduct/Solid Edible',
		'018NY6XC00PTAF3TFBB51C8HX6' => 'HarvestedMaterial/Flower Lot',
		'018NY6XC00PTR9M5Z9S4T31C4R' => 'IntermediateProduct/CO2 Concentrate',
		'018NY6XC00PT2BKFPCEFB9G1Z2' => 'Bulk / Plant Tissue',
		'018NY6XC00PT3EZZ4GN6105M64' => 'Bulk / Immature Plant | Clone',
		'018NY6XC00PTRPPDT8NJY2MWQW' => 'Bulk / Mature Plant',
		'018NY6XC00PTFY48D1136W0S0J' => 'Bulk / Plant Sample',
		// '018NY6XC00PT2BKFPCEFB9G1Z2' => 'Plant Tissue',
		// '018NY6XC00PT3EZZ4GN6105M64' => 'Clones',
		// '018NY6XC00PTFY48D1136W0S0J' => 'Plant Sample/Non-Mandatory',
	];

	/**
	 *
	 */
	function __construct() { /* NO */ }

	/**
	 * Remaps the WCIA Product Type
	 * @return ULID OpenTHC Style Product Type ULID
	 */
	static function product_type_map_id($t0, $t1)
	{
		$pt = strtoupper(sprintf('%s/%s', $t0, $t1));
		switch ($pt) {
			case 'ENDPRODUCT/CAPSULE':                          return '018NY6XC00PT25F95HPG583AJB';
			case 'ENDPRODUCT/CAPSULES':                         return '018NY6XC00PT25F95HPG583AJB'; // Common Typo
			case 'ENDPRODUCT/CONCENTRATE FOR INHALATION':       return '018NY6XC00PTSF5NTC899SR0JF'; // Doesn't Exist but I think it Should
			case 'ENDPRODUCT/LIQUID EDIBLE':                    return '018NY6XC00PT7N83PFNCX8ZFEF';
			case 'ENDPRODUCT/MARIJUANA MIX INFUSED':            return '018NY6XC00PTGRX4Q9SZBHDA5Z';
			case 'ENDPRODUCT/MARIJUANA MIX PACKAGED':           return '018NY6XC00PTKYYGMRSKV4XNH7';
			case 'ENDPRODUCT/SAMPLE JAR':                       return '018NY6XC00PTHE7GWB4QTG4JKZ';
			case 'ENDPRODUCT/SOLID EDIBLE':                     return '018NY6XC00PTBNDY5VJ8JQ6NKP';
			case 'ENDPRODUCT/SUPPOSITORY':                      return '018NY6XC00PTBJ3G5FDAJN60EX';
			case 'ENDPRODUCT/TINCTURE':                         return '018NY6XC00PTD9Q4QPFBH0G9H2';
			case 'ENDPRODUCT/TOPICAL OINTMENT':                 return '018NY6XC00PT0WQP2XV5KNP395';
			case 'ENDPRODUCT/TRANSDERMAL':                      return '018NY6XC00PTHPB8YG56S0MCAC';
			case 'ENDPRODUCT/USABLE MARIJUANA':                 return '018NY6XC00PTGMB39NHCZ8EDEZ';
			case 'ENDPRODUCT/WASTE':                            return '018NY6XC00PT8AXVZGNZN3A0QT';
			case 'HARVESTEDMATERIAL/FLOWER LOT':                return '018NY6XC00PTAF3TFBB51C8HX6'; // Grade A Bulk/Lot
			case 'HARVESTEDMATERIAL/FLOWER UNLOTTED':           return '018NY6XC00PTZZWCH7XVREHK6T'; // Grade A Bulk/Net
			case 'HARVESTEDMATERIAL/MARIJUANA MIX':             return '018NY6XC00PT63ECNBAZH32YC3'; // Grade C Bulk
			case 'HARVESTEDMATERIAL/OTHER MATERIAL LOT':        return '018NY6XC00PT8ZPGMPR8H2TAXH'; // Grade B Bulk/Lot
			case 'HARVESTEDMATERIAL/OTHER MATERIAL UNLOTTED':   return '018NY6XC00PTGBW49J6YD3WM84'; // Grade B Bulk/Net
			case 'HARVESTEDMATERIAL/WASTE':                     return '018NY6XC00PT8AXVZGNZN3A0QT';
			case 'HARVESTEDMATERIAL/WET FLOWER':                return '018NY6XC00PTZZWCH7XVREHK6T'; // Grade A Bulk/Lot
			case 'HARVESTEDMATERIAL/WET OTHER MATERIAL':        return '018NY6XC00PTGBW49J6YD3WM84'; // Grade B Bulk/Net
			case 'INTERMEDIATEPRODUCT/CBD':	                    return '';
			case 'INTERMEDIATEPRODUCT/CO2 CONCENTRATE':         return '018NY6XC00PTR9M5Z9S4T31C4R';
			case 'INTERMEDIATEPRODUCT/CONCENTRATE FOR INHALATION': return '018NY6XC00PTNPA4TPCYSKD5XN';
			case 'INTERMEDIATEPRODUCT/ETHANOL CONCENTRATE':     return '018NY6XC00PT684JJSXN8RAWBM';
			case 'INTERMEDIATEPRODUCT/FOOD GRADE SOLVENT CONCENTRATE': return '018NY6XC00PTHP9NMJ1RE6TA62';
			case 'INTERMEDIATEPRODUCT/HYDROCARBON CONCENTRATE': return '018NY6XC00PTCS5AZV189X1YRK';
			case 'INTERMEDIATEPRODUCT/INFUSED COOKING MEDIUM':  return '018NY6XC00PTY5XPA4KJT6W3K4';
			case 'INTERMEDIATEPRODUCT/MARIJUANA MIX':           return '018NY6XC00PT63ECNBAZH32YC3';
			case 'INTERMEDIATEPRODUCT/NON-SOLVENT BASED CONCENTRATE': return '018NY6XC00PTNPA4TPCYSKD5XN';
			case 'INTERMEDIATEPRODUCT/WASTE':                   return '018NY6XC00PT8AXVZGNZN3A0QT';
			case 'PROPAGATIONMATERIAL/PLANT':                   return '018NY6XC00PTRPPDT8NJY2MWQW';
			case 'PROPAGATIONMATERIAL/SEED':                    return '018NY6XC00PTY9THKSEQ8NFS1J';
			default:
				var_dump($x);
				throw new \Exception(_(sprintf('Unexpected Product Type "%s"', $pt)));
		}

		return '018NY6XC00PR0DUCTTYPE00001'; // -orphan-

	}

	/**
	 *
	 */
	static function product_type_map_name($id)
	{
		return self::$product_type_map[ $id ];
	}

}
