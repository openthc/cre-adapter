<?php
/**
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\E_Metrc\B_Base;

class B_Section_Test extends \OpenTHC\CRE\Test\Metrc_Case
{
	function test_section_type_list()
	{
		$res = $this->cre->section()->getTypeList();
		$this->assertNotEmpty($res);

	}

	function test_section_create()
	{
		$section_list = [];

		// Update Test
		$section = [];
		$section['Name'] = sprintf('TEST %s', $this->create_test_id());
		$section['LocationTypeName'] = 'Default Location Type'; // Oklahoma
		$res = $this->cre->section()->create($section);
		$this->assertValidResponse($res);
		$section_list[] = $section;

		// Delete Test
		$section = [];
		$section['Name'] = sprintf('TEST %s DELETE', $this->create_test_id());
		$section['LocationTypeName'] = 'Default Location Type'; // Oklahoma
		$res = $this->cre->section()->create($section);
		$this->assertValidResponse($res);
		$section_list[] = $section;

		return $section_list;

	}

	/**
	 * @depends test_section_create
	 */
	function test_section_search(array $section_list) : array
	{
		$this->assertCount(2, $section_list);

		$res = $this->cre->section()->search();
		$this->assertValidResponse($res);
		foreach ($res['data'] as $rec) {
			foreach ($section_list as $idx => $section) {
				if ($rec['Name'] == $section['Name']) {
					$section_list[$idx] = $rec;
				}
			}
		}

		foreach ($section_list as $idx => $section) {
			$this->assertIsArray($section);
			$this->assertNotEmpty($section['Id']);
		}

		return $section_list;

	}

	/**
	 * @depends test_section_search
	 */
	function test_section_update(array $section_list) : array
	{
		$section = $section_list[0];

		$this->assertNotEmpty($section['Id']);

		$section['Name'] = sprintf('%s-UPDATE', $section['Name']);

		$res = $this->cre->section()->update($section);
		$this->assertValidResponse($res);

		// Find The Updated
		$section_update = null;
		$res = $this->cre->section()->search();
		$this->assertValidResponse($res);
		foreach ($res['data'] as $rec) {
			// var_dump($rec);
			if ($rec['Id'] == $section['Id']) {
				$section_update = $rec;
				break;
			}
		}

		$this->assertNotEmpty($section_update);
		$this->assertEquals($section['Id'], $section_update['Id']);
		$this->assertEquals($section['Name'], $section_update['Name']);

		return $section_list;
	}

	/**
	 * @depends test_section_update
	 */
	function test_section_delete(array $section_list)
	{
		$section = $section_list[1];
		$res = $this->cre->section()->delete($section['Id']);
		$this->assertValidResponse($res);
	}

}
