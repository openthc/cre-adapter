<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\E_Metrc\B_Base;

class A_Type_List_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_get_uom()
	{
		$res = $this->cre->uomList();
		$this->assertValidResponse($res);
		$this->assertNotEmpty($res);
	}

	function test_get_product_type()
	{
		$res = $this->cre->product()->getTypeList();
		$this->assertValidResponse($res);
	}

	function test_get_crop_waste_type()
	{
		$res = $this->cre->get('/harvests/v1/waste/types');
		$this->assertNotEmpty($res);
	}

}

// https://api-or.metrc.com/Documentation#Packages.get_packages_v1_types

// https://api-or.metrc.com/Documentation#PlantBatches.get_plantbatches_v1_types


// https://api-or.metrc.com/Documentation#Transfers.get_transfers_v1_types

// https://api-or.metrc.com/Documentation#Sales.get_sales_v1_customertypes

// https://api-or.metrc.com/Documentation#Plants.get_plants_v1_waste_reasons

// https://api-or.metrc.com/Documentation#Plants.get_plants_v1_additives_types
