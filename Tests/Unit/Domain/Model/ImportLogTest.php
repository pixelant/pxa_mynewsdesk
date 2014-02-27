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
 * Test case for class \TYPO3\PxaMynewsdesk\Domain\Model\ImportLog.
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
class ImportLogTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \TYPO3\PxaMynewsdesk\Domain\Model\ImportLog
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \TYPO3\PxaMynewsdesk\Domain\Model\ImportLog();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getHashReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setHashForStringSetsHash() { 
		$this->fixture->setHash('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getHash()
		);
	}
	
	/**
	 * @test
	 */
	public function getNewstableReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setNewstableForStringSetsNewstable() { 
		$this->fixture->setNewstable('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getNewstable()
		);
	}
	
	/**
	 * @test
	 */
	public function getNewsidReturnsInitialValueForInteger() { 
		$this->assertSame(
			0,
			$this->fixture->getNewsid()
		);
	}

	/**
	 * @test
	 */
	public function setNewsidForIntegerSetsNewsid() { 
		$this->fixture->setNewsid(12);

		$this->assertSame(
			12,
			$this->fixture->getNewsid()
		);
	}
	
}
?>