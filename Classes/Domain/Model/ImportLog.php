<?php
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
 *  the Free Software Foundation; either version 3 of the License, or
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
 *
 *
 * @package pxa_mynewsdesk
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_PxaMynewsdesk_Domain_Model_ImportLog extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * News hash
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $hash;

	/**
	 * News table
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $newstable;

	/**
	 * News uid
	 *
	 * @var integer
	 * @validate NotEmpty
	 */
	protected $newsid;

	/**
	 * News pid
	 *
	 * @var integer
	 * @validate NotEmpty
	 */
	protected $newspid;

	/**
	 * Returns the hash
	 *
	 * @return string $hash
	 */
	public function getHash() {
		return $this->hash;
	}

	/**
	 * Sets the hash
	 *
	 * @param string $hash
	 * @return void
	 */
	public function setHash($hash) {
		$this->hash = $hash;
	}

	/**
	 * Returns the newstable
	 *
	 * @return string $newstable
	 */
	public function getNewstable() {
		return $this->newstable;
	}

	/**
	 * Sets the newstable
	 *
	 * @param string $newstable
	 * @return void
	 */
	public function setNewstable($newstable) {
		$this->newstable = $newstable;
	}

	/**
	 * Returns the newsid
	 *
	 * @return integer $newsid
	 */
	public function getNewsid() {
		return $this->newsid;
	}

	/**
	 * Sets the newsid
	 *
	 * @param integer $newsid
	 * @return void
	 */
	public function setNewsid($newsid) {
		$this->newsid = $newsid;
	}

	/**
	 * Returns the newspid
	 *
	 * @return integer $newsid
	 */
	public function getNewspid() {
		return $this->newspid;
	}

	/**
	 * Sets the newsid
	 *
	 * @param integer $newspid
	 * @return void
	 */
	public function setNewspid($newspid) {
		$this->newspid = $newspid;
	}
}
?>