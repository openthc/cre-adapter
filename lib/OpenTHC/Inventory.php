<?php
/**
 * Inventory Adapter
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\OpenTHC;

class Inventory extends Base
{
	protected $_path = '/inventory';

	function search($filter=null)
	{
		$url = '/lot';

		if (!empty($filter)) {
			$url.= '?' . http_build_query($filter);
		}

		$res = $this->_cre->get($url);
		return $res;
	}

	/**
	 * @param $oid Inventory Lot to Adjust
	 */
	function adjust(string $oid, $arg)
	{
		$url = sprintf('/%s/%s/adjust', $this->_path, rawurlencode($oid));
		$res = $this->_cre->post($url, $arg);
		return $res;
	}

	/**
	 *
	 */
	function convert()
	{
		throw new \Exception('Not Implemented [ROL-0398]');
	}

	/**
	 * Convert stuff into this one?
	 *
	 * @param $source_spec
	 */
	function convertFrom()
	{

	}

	/**
	 * Convert this to something else?
	 *
	 * @param $output_spec
	 */
	function convertTo()
	{

	}

	/**
		@param $x Lot Data Array
	*/
	function create($obj)
	{
		$res = $this->_cre->post('/lot', $obj);
		return $res;
	}

	/**
	*/
	function update(string $oid, $obj)
	{
		$url = sprintf('/lot/%s', rawurlencode($oid));
		$res = $this->_cre->patch($url, $obj);
		return $res;
	}

	function delete(string $oid, $arg=null)
	{
		$res = $this->_cre->delete('/lot/' . $oid);
		return $res;
	}

	// Legacy Alias
	function destroy(string $oid, $arg)
	{
		return $this->delete($oid, $arg);
	}

	function sync($lid, $msg)
	{
		$res = $this->single($lid);

		if (200 === $res['code']) {
			$rec = $res['data'];
		} else {
			throw new \Exception(sprintf("Invalid code from OpenTHC %s [OSL-082]", $res['code']));
		}

		if (empty($rec['id'])) {
			syslog(LOG_ERR, "Lot object from OpenTHC missing ID [OSL-074]");
			return(null);
		}

		$L = $this->_cre->getLicense();

		$I = Inventory::findByGUID($rec['id']);
		if (empty($I)) {
			$I = new Inventory();
			$I['license_id'] = $L['id'];
			$I['guid'] = $rec['id'];
		}

		// @todo
		// // Skip if Known
		// $hashA = $I['hash'];
		// $hashB = $this->_sync->hashObject($rec);
		// if ($hashA == $hashB) {
		// 	return($I);
		// }
		// $I['hash'] = $hashB;
		if (empty($I['stat'])) {
			$I['stat'] = 200;
		}

		// Update prime CRE data first
		$I['meta'] = json_encode($rec);
		$meta = $I->getMeta();
		$cre_meta = json_decode($meta['meta'], true);

		// Only set the Section once
		if (empty($I['section_id']) && !empty($rec['section_id'])) {

			$R = Section::findByGUID($rec['section_id']);
			if (empty($R['id'])) {
				$chk = $this->_cre->section()->single($rec['section_id']);
				if (!empty($chk)) {
					$R = new Section();
					$R['license_id'] = $I['license_id'];
					$R['name'] = $chk['data']['name'];
					$R['guid'] = $chk['data']['id'];
					$R['type'] = '-';
					$R->save('Section/Create via Lot/Update via -system-sync-');
				}
			}

			$I['section_id'] = $R['id'];

		}
		if (empty($I['section_id'])) {
			$I['section_id'] = '018NY6XC00SECT10N000000000';
		}

		if (empty($I['variety_id'])) {
			$S = Variety::findByGUID($rec['variety_id']);
			if (empty($S['id'])) {
				$S = new Variety();
				$S['license_id'] = $L['id'];
				$S['guid'] = $rec['variety']['id'];
				$S['name'] = '-Lost Variety #SLD086-';
				$S->save();
			}
			$I['variety_id'] = $S['id'];
		}
		// if (!empty($rec['variety_id'])) {
		// 	$S = Variety::findByGUID($rec['variety_id']);
		// 	if (empty($S['id'])) {
		// 		$S = new Variety();
		// 		$S['license_id'] = $L['id'];
		// 		$S['guid'] = $rec['variety']['id'];
		// 		$S['name'] = '-Lost variety #SLD086-';
		// 		$S->save();
		// 	}
		// 	$I['variety_id'] = $S['id'];
		// } else {
		// 	$I['variety_id'] = '018NY6XC00VAR1ETY000000000';
		// }

		$I['created_at'] = $rec['created_at'];
		$I['updated_at'] = $rec['updated_at'];
		// Deleted at?

		// If the Lot was Modified, check for the modified product parmeter
		// @todo Migrate away from 'type' field, and toward 'product' /mbw
		// $product_guid = $cre_meta['product'] ?: $cre_meta['type'] ?: $rec['product_id'];
		// $P = Product::findByGUID($product_guid);
		// if (empty($P['id'])) {

			// @todo
			// syslog(LOG_ERR, "RLS#369: No Product / Inventory Type {$rec['product_id']} for Inventory {$rec['id']}");

			// $chk = $this->_cre->inventory_type()->single($rec['global_inventory_type_id']);
			// if (!empty($chk)) {
			// 	$PT = new Product_Type($P['product_type_id']);
			// } else {

			// 	$P = new Product();
			// 	$P['license_id'] = $this->_License['id'];
			// 	$P['guid'] = $rec['global_inventory_type_id'];
			// 	$P['product_type_id'] = '018NY6XC00PR0DUCTTYPE00000';
			// 	$P['name'] = '- LeafData Product Lost [LSL#131] -';
			// 	$P['stub'] = 'junk';
			// 	$P->save('Create missing from Inventory sync');

			// }

		// } else {

			// $PT = new Product_Type($P['product_type_id']);

		// }

		// $I['product_id'] = $P['id'];
		if (empty($I['product_id'])) {
			$P = Product::findByGUID($rec['product_id']);
			if (empty($P['id'])) {
				// @todo Discover Product Type!
				$P = new Product();
				$P['license_id'] = $L['id'];
				$P['product_type_id'] = '018NY6XC00PR0DUCTTYPE00001';
				$P['guid'] = $rec['product_id'];
				$P['name'] = '-Lost Product LSL#203-';
				$P['stub'] = '-';
				$P->save();
			}
			$I['product_id'] = $P['id'];
		}

		// v2
		// $qty = array_key_exists('qty', $cre_meta) ? $cre_meta['qty'] : $rec['qty'];
		// $I['qty'] = floatval($qty);

		if (empty($I['id'])) {
			$I['qty_initial'] = $rec['qty'];
			$I['qty'] = $rec['qty'];
		}
		// Is Medical?

		$I->save($msg);

		$meta = json_decode($rec['meta'], true);
		if (!empty($rec['convert']) && !empty($rec['convert']['parents'])) {

			foreach ($rec['convert']['parents'] as $parent_rec) {
				$PL = Inventory::findByGUID($parent_rec['id']);
				if (!empty($PL['id'])) {
					$I->bindParent($PL);
				} else {
					$msg = "OSL#188: Invalid Parent Lot '{$rec['id']} => '{$parent_rec['id']}'!";
					syslog(LOG_ERR, $msg);
					//throw new Exception($msg);
				}
			}

		}

		// Link Lab Result?

		return $I;
	}

}
