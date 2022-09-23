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
	const ENGINE = 'wcia';

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
	function __construct() { /* Not Yet */ }

	/**
	 *
	 */
	static function b2b_get($url)
	{
		$ret = [];
		$res = self::url_get($url);
		switch ($res['code']) {
			case 200:
				return [
					'data' => json_decode($res['body'], true),
					'meta' => [],
				];
				break;
			default:
		}

		return $ret;

	}


	/**
	 * Inflates the document, checks values
	 * returns inflated
	 */
	static function b2b_to_openthc($doc) : array
	{
		$ret = [];
		$ret['id'] = $doc['transfer_id'];
		$ret['depart_at'] = $doc['est_departed_at'];
		$ret['arrive_at'] = $doc['est_arrival_at'];
		$ret['source'] = [
			'id' => '',
			'code' => $doc['from_license_number'],
			'name' => $doc['from_license_name'],
		];
		$ret['target'] = [
			'id' => '',
			'code' => $doc['to_license_number'],
			'name' => $doc['to_license_name'],
		];
		$ret['item_list'] = [];


		foreach ($doc['inventory_transfer_items'] as $b2b_item0) {

			$b2b_item1 = [];
			$b2b_item1['id'] = _ulid();
			$b2b_item1['product'] = [
				'id' => $b2b_item0['product_sku'],
				'name' => $b2b_item0['product_name'],
				// 'unit' => '',
				'unit_uom' => $b2b_item0['unit_weight_uom']
			];
			$b2b_item1['product_type'] = [
				'id' => self::product_type_map_id($b2b_item0['inventory_category'], $b2b_item0['inventory_type'])
			];
			$b2b_item1['variety'] = [
				'id' => '',
				'name' => $b2b_item0['strain_name'],
			];
			$b2b_item1['lab_result'] = [
				'id' => '',
				'@source' => $b2b_item1[''],
			];
			$b2b_item1['inventory'] = [];
			$b2b_item1['qty'] = $b2b_item0['qty'];
			$b2b_item1['uom'] = $b2b_item0['uom'];

			// Decipher Lab Result Bullshit
			$lab_link0 = $b2b_item0['lab_result_link']; // JSON or PDF
			$lab_link1 = $b2b_item0['lab_result_data']['lab_result_detail']; // JSON
			$lab_link2 = $b2b_item0['lab_result_data']['coa']; // PDF
			if ( ! empty($b2b_item1['lab_result_link'])) {
				// Try to Detect if JSON or PDF
			}
			if ( ! empty($b2b_item1['lab_result_data']['lab_result_list'])) {
				foreach ($b2b_item1['lab_result_data']['lab_result_list'] as $lr1) {
					// $lr1['coa']; // PDF
					// $lr1['lab_result_link']; // JSON
				}
			}

			$ret['item_list'][] = $b2b_item1;

		}

		return $ret;

	}

	/**
	 * Get the Lab Data
	 */
	static function lab_get($url)
	{
		$ret = [];
		$res = self::url_get($url);
		switch ($res['code']) {
			case 200:
				return [
					'data' => json_decode($res['body'], true),
					'meta' => [],
				];
				break;
			default:
		}

		return $ret;

	}

	/**
	 * Convert the WCIA Data Model to OpenTHC Model
	 */
	static function lab_to_openthc($doc) : array
	{

	}

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

	/**
	 * GET the URL and Give Back Meaningful Data
	 */
	static function url_get($url) : array
	{
		$ret = [
			'body' => '',
			'code' => 0,
			'head' => [],
			'meta' => [],
			'name' => ''
		];

		$req = __curl_init($url);
		// $req = _curl_init($url);

		// Naive Header Function
		$res_head = [];
		curl_setopt($req, CURLOPT_HEADERFUNCTION, function($req0, $head_line) use (&$res_head) {
				$key = trim(strtolower(strtok($head_line, ':')));
				$val = trim(strtok(''));
				$res_head[$key] = $val;
				return strlen($head_line);
		});
		$res = curl_exec($req);
		$inf = curl_getinfo($req);

		if ( ! empty($res_head['content-disposition'])) {
			if (preg_match('/filename=(.+);?/', $res_head['content-disposition'], $m)) {
				$n = trim($m[1]);
				$n = trim($n, "'");
				$n = trim($n, '"');
				$f['name'] = $n;
			}
		}

		return [
			'body' => $res,
			'code' => $inf['http_code'],
			'head' => $res_head,
			'meta' => $inf,
		];

	}

}
