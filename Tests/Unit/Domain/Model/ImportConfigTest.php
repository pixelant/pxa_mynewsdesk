<?php

namespace TYPO3\PxaMynewsdesk\Tests;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Maksym Leskiv <maksym@pixelant.se>, Pixelant AB
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class \TYPO3\PxaMynewsdesk\Domain\Model\ImportConfig.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Mynewsdesk import
 *
 * @author Maksym Leskiv <maksym@pixelant.se>
 */
class ImportConfigTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \TYPO3\PxaMynewsdesk\Domain\Model\ImportConfig
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \TYPO3\PxaMynewsdesk\Domain\Model\ImportConfig();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getTitleReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setTitleForStringSetsTitle() { 
		$this->fixture->setTitle('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getTitle()
		);
	}
	
	/**
	 * @test
	 */
	public function getDescriptionReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setDescriptionForStringSetsDescription() { 
		$this->fixture->setDescription('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getDescription()
		);
	}
	
	/**
	 * @test
	 */
	public function getNewpidReturnsInitialValueForInteger() { 
		$this->assertSame(
			0,
			$this->fixture->getNewpid()
		);
	}

	/**
	 * @test
	 */
	public function setNewpidForIntegerSetsNewpid() { 
		$this->fixture->setNewpid(12);

		$this->assertSame(
			12,
			$this->fixture->getNewpid()
		);
	}
	
	/**
	 * @test
	 */
	public function getNewstableReturnsInitialValueForInteger() { 
		$this->assertSame(
			0,
			$this->fixture->getNewstable()
		);
	}

	/**
	 * @test
	 */
	public function setNewstableForIntegerSetsNewstable() { 
		$this->fixture->setNewstable(12);

		$this->assertSame(
			12,
			$this->fixture->getNewstable()
		);
	}
	
	/**
	 * @test
	 */
	public function getMappingReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setMappingForStringSetsMapping() { 
		$this->fixture->setMapping('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getMapping()
		);
	}
	
	/**
	 * @test
	 */
	public function getNewsurlReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setNewsurlForStringSetsNewsurl() { 
		$this->fixture->setNewsurl('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getNewsurl()
		);
	}
	
}
?>