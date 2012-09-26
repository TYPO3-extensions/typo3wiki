<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Bastian Bringenberg <typo3@bastian-bringenberg.de>, Bastian Bringenberg
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
 * @package typo3wiki
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_Typo3wiki_Domain_Model_Category extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * title
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $title;

	/**
	 * pages
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_Typo3wiki_Domain_Model_Page>
	 * @lazy
	 */
	protected $pages;

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all Tx_Extbase_Persistence_ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 */
		$this->pages = new Tx_Extbase_Persistence_ObjectStorage();
	}

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
	 * Adds a Page
	 *
	 * @param Tx_Typo3wiki_Domain_Model_Page $page
	 * @return void
	 */
	public function addPage(Tx_Typo3wiki_Domain_Model_Page $page) {
		$this->pages->attach($page);
	}

	/**
	 * Removes a Page
	 *
	 * @param Tx_Typo3wiki_Domain_Model_Page $pageToRemove The Page to be removed
	 * @return void
	 */
	public function removePage(Tx_Typo3wiki_Domain_Model_Page $pageToRemove) {
		$this->pages->detach($pageToRemove);
	}

	/**
	 * Returns the pages
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_Typo3wiki_Domain_Model_Page> $pages
	 */
	public function getPages() {
		return $this->pages;
	}

	/**
	 * Sets the pages
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_Typo3wiki_Domain_Model_Page> $pages
	 * @return void
	 */
	public function setPages(Tx_Extbase_Persistence_ObjectStorage $pages) {
		$this->pages = $pages;
	}

}
?>