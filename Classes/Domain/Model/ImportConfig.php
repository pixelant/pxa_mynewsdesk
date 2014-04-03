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

namespace Pixelant\PxaMynewsdesk\Domain\Model ;

/**
 *
 *
 * @package pxa_mynewsdesk
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ImportConfig extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Title
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $title;

	/**
	 * Description
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * News storage pid
	 *
	 * @var integer
	 */
	protected $newpid;

	/**
	 * News table
	 *
	 * @var string
	 */
	protected $newstable;

	/**
	 * Mapping configuration
	 *
	 * @var string
	 */
	protected $mapping;

	/**
	 * News categories
	 *
	 * @var string
	 */
	protected $newscat;

	/**
	 * Url with news xml
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $newsurl;

	/**
	 * Returns the title
	 *
	 * @return string $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets the title
	 *
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Returns the description
	 *
	 * @return string $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Sets the description
	 *
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Returns the newpid
	 *
	 * @return integer $newpid
	 */
	public function getNewpid() {
		return $this->newpid;
	}

	/**
	 * Sets the newpid
	 *
	 * @param integer $newpid
	 * @return void
	 */
	public function setNewpid($newpid) {
		$this->newpid = $newpid;
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
	 * Returns the newscat
	 *
	 * @return string $newscat
	 */
	public function getNewscat() {
		return $this->newscat;
	}

	/**
	 * Sets the newscat
	 *
	 * @param string $newscat
	 * @return void
	 */
	public function setNewscat($newscat) {
		$this->newscat = $newscat;
	}

	/**
	 * Returns the mapping
	 *
	 * @return string $mapping
	 */
	public function getMapping() {
		return $this->mapping;
	}

	/**
	 * Sets the mapping
	 *
	 * @param string $mapping
	 * @return void
	 */
	public function setMapping($mapping) {
		$this->mapping = $mapping;
	}

	/**
	 * Returns the newsurl
	 *
	 * @return string $newsurl
	 */
	public function getNewsurl() {
		return $this->newsurl;
	}

	/**
	 * Sets the newsurl
	 *
	 * @param string $newsurl
	 * @return void
	 */
	public function setNewsurl($newsurl) {
		$this->newsurl = $newsurl;
	}

}
?>