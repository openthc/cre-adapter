<?php
/**
 * Implementation for B2B Transactions
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\CCRS;

use DateTimezone;
use DateTime;

class B2B
{
	/**
	 * Create a B2B Outgoing Manifest CSV
	 *
	 * @return resource handle to the Stream
	 */
	function create_outgoing_csv(array $b2b)
	{
		$req_ulid = _ulid();

		$tz0 = new DateTimezone(\OpenTHC\Config::get('cre/usa/wa/ccrs/tz'));
		$dt0 = new DateTime();
		$dt0->setTimezone($tz0);

		$dtC = new DateTime($b2b['created_at']);
		$dtC->setTimezone($tz0);

		$dtU = new DateTime($b2b['updated_at']);
		$dtU->setTimezone($tz0);

		$dtD = new DateTime($b2b['shipping']['depart_at']);
		$dtD->setTimezone($tz0);

		$dtA = new DateTime($b2b['shipping']['arrive_at']);
		$dtA->setTimezone($tz0);

		// $csv_name = sprintf('Manifest_%s_%s.csv', $cre_service_key, $req_ulid);
		$csv_head = explode(',', 'InventoryExternalIdentifier,PlantExternalIdentifier,Quantity,UOM,WeightPerUnit,ServingsPerUnit,ExternalIdentifier,CreatedBy,CreatedDate,UpdatedBy,UpdatedDate,Operation');
		$this->col_size = count($csv_head);

		$csv_temp = fopen('php://temp', 'w');

		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'SubmittedBy', 'OpenTHC' ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'SubmittedDate', date('m/d/Y') ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'NumberRecords', count($b2b['item_list']) ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'ExternalManifestIdentifier', $b2b['id'] ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'Header Operation','INSERT' ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'TransportationType', 'REGULAR' ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'OriginLicenseNumber', $b2b['source_license']['code'] ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'OriginLicenseePhone', $b2b['source_license']['phone'] ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'OriginLicenseeEmailAddress', sprintf('code+%s@openthc.com', $req_ulid) ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'TransportationLicenseNumber', '' ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'DriverName', $b2b['shipping']['contact'][0]['name'] ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'DepartureDateTime', $dtD->format('m/d/Y h:i:s A') ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'ArrivalDateTime', $dtA->format('m/d/Y h:i:s A') ]));

		$v = $b2b['shipping']['vehicle'];
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'VIN #', $v['vin'] ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'VehiclePlateNumber', $v['plate'] ?: $v['tag'] ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'VehicleModel', $v['model'] ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'VehicleMake', $v['make'] ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'VehicleColor', $v['color'] ]));

		$tl = $b2b['target_license'];
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'DestinationLicenseNumber', $tl['code'] ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'DestinationLicenseePhone', $tl['phone'] ]));
		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $this->_pad_csv_row([ 'DestinationLicenseeEmailAddress', $tl['email'] ]));

		\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, array_values($csv_head));

		foreach ($b2b['item_list'] as $b2b_item) {
			$row =  [
				$b2b_item['lot']['id'] // InventoryExternalIdentifier
				, '' // PlantExternalIdentifier
				, $b2b_item['unit_count'] // Quantity
				, $b2b_item['product']['uom'] ?: 'GRAM' // UOM
				, '0' // sprintf('%0.2f', 1) // WeightPerUnit
				, '1' // ServingsPerUnit
				, $b2b_item['id'] // ExternalIdentifier
				, '-system-' // CreatedBy
				, $dtC->format('m/d/Y') // CreatedDate
				, '-system-' // UpdatedBy
				, $dtU->format('m/d/Y') // UpdatedDate
				, 'INSERT' // OPERATION
			];
			\OpenTHC\CRE\CCRS::fputcsv_stupidly($csv_temp, $row);
		}

		fseek($csv_temp, 0);

		return $csv_temp;

	}

	/**
	 * Pad the Array to the Correct Size
	 */
	function _pad_csv_row($a)
	{
		return array_values(array_pad($a, $this->col_size, ''));
	}
}
