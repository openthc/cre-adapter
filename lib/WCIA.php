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
				'id' => self::map_product_type_ct2id($b2b_item0['inventory_category'], $b2b_item0['inventory_type'])
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
	 * Maps OpenTHC Product ID to WCIA Category & Type
	 *
	 * @return Array
	 */
	static function map_product_type_id2ct(string $x)
	{
		if (empty($x)) {
			return null;
		}

		switch ($x) {
			// case '018NY6XC00PR0DUCTTYPE00000': // -system-
			// case '018NY6XC00PR0DUCTTYPE00001': return 'HarvestedMaterial'; // -orphan-
			case '018NY6XC00PT0WQP2XV5KNP395': return [ 'EndProduct', 'Topical Ointment' ];
			case '018NY6XC00PT25F95HPG583AJB': return [ 'EndProduct', 'Capsule' ];
			case '018NY6XC00PT2BKFPCEFB9G1Z2': return [ 'PropagationMaterial', 'Plant' ];
			case '018NY6XC00PT3EZZ4GN6105M64': return [ 'PropagationMaterial', 'Plant' ];
			case '018NY6XC00PT63ECNBAZH32YC3': return [ 'IntermediateProduct', 'Marijuana Mix' ];
			case '018NY6XC00PT684JJSXN8RAWBM': return [ 'IntermediateProduct', 'Ethanol Concentrate' ];
			case '018NY6XC00PT7N83PFNCX8ZFEF': return [ 'EndProduct', 'Liquid Edible' ];
			case '018NY6XC00PT8ZPGMPR8H2TAXH': return [ 'HarvestedMaterial', 'Other Material Lot' ];
			case '018NY6XC00PTAF3TFBB51C8HX6': return [ 'HarvestedMaterial', 'Flower Lot' ];
			case '018NY6XC00PTBJ3G5FDAJN60EX': return [ 'EndProduct', 'Suppository' ];
			case '018NY6XC00PTBNDY5VJ8JQ6NKP': return [ 'EndProduct', 'Solid Edible' ];
			case '018NY6XC00PTCS5AZV189X1YRK': return [ 'IntermediateProduct', 'Hydrocarbon Concentrate' ];
			case '018NY6XC00PTD9Q4QPFBH0G9H2': return [ 'EndProduct', 'Tincture' ];
			case '018NY6XC00PTFY48D1136W0S0J': return [ 'PropagationMaterial', 'Plant' ];
			case '018NY6XC00PTGBW49J6YD3WM84': return [ 'HarvestedMaterial', 'Other Material Unlotted' ];
			case '018NY6XC00PTGMB39NHCZ8EDEZ': return [ 'EndProduct', 'Usable Marijuana' ];
			case '018NY6XC00PTGRX4Q9SZBHDA5Z': return [ 'EndProduct', 'Marijuana Mix Infused' ];
			case '018NY6XC00PTHE7GWB4QTG4JKZ': return [ 'EndProduct', 'Sample Jar' ];
			case '018NY6XC00PTHP9NMJ1RE6TA62': return [ 'IntermediateProduct', 'Food Grade Solvent Concentrate' ];
			case '018NY6XC00PTHPB8YG56S0MCAC': return [ 'EndProduct', 'Transdermal' ];
			case '018NY6XC00PTKYYGMRSKV4XNH7': return [ 'EndProduct', 'Marijuana Mix Packaged' ];
			case '018NY6XC00PTNPA4TPCYSKD5XN': return [ 'IntermediateProduct', 'Non-Solvent Based Concentrate' ];
			case '018NY6XC00PTR9M5Z9S4T31C4R': return [ 'IntermediateProduct', 'CO2 Concentrate' ];
			case '018NY6XC00PTRPPDT8NJY2MWQW': return [ 'PropagationMaterial', 'Plant' ];
			case '018NY6XC00PTSF5NTC899SR0JF': return [ 'EndProduct', 'Marijuana Mix Infused' ]; // Concentrate For Inhalation
			case '018NY6XC00PTY5XPA4KJT6W3K4': return [ 'IntermediateProduct', 'Infused Cooking Medium' ];
			case '018NY6XC00PTY9THKSEQ8NFS1J': return [ 'PropagationMaterial', 'Seed' ];
			case '018NY6XC00PTZZWCH7XVREHK6T': return [ 'HarvestedMaterial', 'Flower Unlotted' ];
			// case '018NY6XC00PT8AXVZGNZN3A0QT': return 'Waste';
			default:
				throw new \Exception("Type '$x' Not Handled [CLC-194]");
		}
	}

	/**
	 * Maps WCIA Product Type and Category to OpenTHC ULID
	 */
	static function map_product_type_ct2id($t0, $t1)
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
			case 'HARVEST_MATERIALS/FLOWER_LOTS':               return '018NY6XC00PTAF3TFBB51C8HX6'; // Grade A Bulk/Lot - Cultivera Typo
			case 'HARVESTEDMATERIAL/FLOWER UNLOTTED':           return '018NY6XC00PTZZWCH7XVREHK6T'; // Grade A Bulk/Net
			case 'HARVESTEDMATERIAL/MARIJUANA MIX':             return '018NY6XC00PT63ECNBAZH32YC3'; // Grade C Bulk
			case 'HARVESTEDMATERIAL/OTHER MATERIAL LOT':        return '018NY6XC00PT8ZPGMPR8H2TAXH'; // Grade B Bulk/Lot
			case 'HARVEST_MATERIALS/OTHER_MATERIAL_LOTS':       return '018NY6XC00PT8ZPGMPR8H2TAXH'; // Grade B Bulk/Lot - Cultivera Typo
			case 'HARVESTEDMATERIAL/OTHER MATERIAL UNLOTTED':   return '018NY6XC00PTGBW49J6YD3WM84'; // Grade B Bulk/Net
			case 'HARVESTEDMATERIAL/WASTE':                     return '018NY6XC00PT8AXVZGNZN3A0QT';
			case 'HARVESTEDMATERIAL/WET FLOWER':                return '018NY6XC00PTZZWCH7XVREHK6T'; // Grade A Bulk/Lot
			case 'HARVESTEDMATERIAL/WET OTHER MATERIAL':        return '018NY6XC00PTGBW49J6YD3WM84'; // Grade B Bulk/Net
			case 'INTERMEDIATEPRODUCT/CBD':                     return '';
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
				//var_dump($x);
				throw new \Exception(_(sprintf('Product Type "%s" Invalid in WCIA Specification [CLW-234]', $pt)));
		}

		return '018NY6XC00PR0DUCTTYPE00001'; // -orphan-
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

	/**
	 * B2B Data GET Helper
	 */
	function url_get_b2b($source_url)
	{
		$ret = [
			'@context' => [],
			'@origin' => '',
			'@source' => [],
			'@status' => [],
		];

		$ret['@origin'] = trim($source_url);

	}

	/**
	 * Lab Data GET Helper
	 */
	function url_get_lab($source_url) : array
	{
		$ret = [
			'@context' => [],
			'@origin' => '',
			'@source' => [],
			'@status' => [],
		];

		$ret['@origin'] = trim($source_url);

		if ( ! preg_match('/^https?:\/\//', $ret['@origin'])) {
			return [ '@status' => [
				'fail' => _('Invalid Link for Laboratory Data Transfer [CLW-296]')
			]];
		}

		$req = _curl_init($ret['@origin']);
		$res = curl_exec($req);
		$inf = curl_getinfo($req);
		curl_close($req);

		$mime_type = strtolower($inf['content_type']);
		if (preg_match('/json.+charset/', $mime_type)) {
			$ret['@status'] = [
				'warn' => _('The provided data-link is not compliant with well defined internet standards (RFC4627, RFC7159) [CLW-309]')
			];
		}

		$mime_type = strtok($mime_type, ';');
		// $mime_type = strtok($mime_type, ',');
		// $mime_type = strtok($mime_type, '+');
		switch ($mime_type) {
			case 'application/json';
				// OK
				break;
			default:
				$ret['@status'] = [
					'fail' => sprintf(_('Invalid Link for Lab Data Transfer; Unhandled Content Type <em>"%s"</em> [CLW-322]'), __h($inf['content_type']))
				];
				return $ret;
		}

		$doc = json_decode($res, true);
		// $doc['@origin'] = $source_url;

		if ( 'WCIA Lab Result Schema' != $doc['document_name']) {
			$ret['@status']['fail'] = _('Invalid Content; Not a valid WCIA document [CLW-331]');
			return $ret;
		}

		// Nobody fucking cares about this version, just ignore it and evaluate the data-model
		switch ($doc['document_schema_version']) {
			case '1.0.0.0':
			case '1.1.0.0':
			case '1.2.0.0':
			case '1.2.0':
			case '1.3.0':
				$ret['@status']['warn'] = _('Sender should update their document schema version [CLW-342]');
				break;
			case '2.0.0': // 2022-04-31?
			case '2.1.0': // 2022-10-01
				break;
			default:
				$ret['@status']['warn'] = _('Sender has a crazy document schema version [CLW-348]');
				break;
		}

		$ret['@context'] = 'https://cannabisintegratorsalliance.com/v2022.021/lab/metric';
		$ret['@source'] = $doc;

		return $ret;

	}

}
