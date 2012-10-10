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
	class Tx_Typo3wiki_Domain_Model_Page extends Tx_Extbase_DomainObject_AbstractEntity {

		/**
		 * pageTitle
		 *
		 * @var string
		 * @validate NotEmpty
		 */
		protected $pageTitle;

		/**
		 * subscriber
		 *
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_Typo3wiki_Domain_Model_User>
		 * @lazy
		 */
		protected $subscriber;

		/**
		 * revisions
		 *
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_Typo3wiki_Domain_Model_TextRevision>
		 * @lazy
		 */
		protected $revisions;

		/**
		 * mainRevision
		 *
		 * @var Tx_Typo3wiki_Domain_Model_TextRevision
		 * @lazy
		 */
		protected $mainRevision;

		/**
		 * relatedPages
		 *
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_Typo3wiki_Domain_Model_Page>
		 * @lazy
		 */
		protected $relatedPages;

		/**
		 * categoryPages
		 *
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_Typo3wiki_Domain_Model_Page>
		 * @lazy
		 */
		protected $categoryPages;

		/**
		 * isCategory
		 *
		 * @var boolean
		 */
		protected $isCategory;

		/**
		 * Redirections PageName
		 *
		 * @var Tx_Typo3wiki_Domain_Model_Page
		 */
		protected $redirection;

		/**
		 * __construct
		 *
		 * @return void
		 */
		public function __construct() {
			$this->initStorageObjects();
		}

		/**
		 * Initializes all Tx_Extbase_Persistence_ObjectStorage properties.
		 *
		 * @return void
		 */
		protected function initStorageObjects() {
			$this->subscriber = new Tx_Extbase_Persistence_ObjectStorage();
			$this->revisions = new Tx_Extbase_Persistence_ObjectStorage();
			$this->relatedPages = new Tx_Extbase_Persistence_ObjectStorage();
			$this->categoryPages = new Tx_Extbase_Persistence_ObjectStorage();
		}

		/**
		 * Returns the pageTitle
		 *
		 * @return string $pageTitle
		 */
		public function getPageTitle() {
			return $this->pageTitle;
		}

		/**
		 * Sets the pageTitle
		 *
		 * @param string $pageTitle
		 * @return void
		 */
		public function setPageTitle($pageTitle) {
			$this->pageTitle = $pageTitle;
		}

		/**
		 * Adds a User
		 *
		 * @param Tx_Typo3wiki_Domain_Model_User $subscriber
		 * @return void
		 */
		public function addSubscriber(Tx_Typo3wiki_Domain_Model_User $subscriber) {
			$this->subscriber->attach($subscriber);
		}

		/**
		 * Removes a User
		 *
		 * @param Tx_Typo3wiki_Domain_Model_User $subscriberToRemove The User to be removed
		 * @return void
		 */
		public function removeSubscriber(Tx_Typo3wiki_Domain_Model_User $subscriberToRemove) {
			$this->subscriber->detach($subscriberToRemove);
		}

		/**
		 * Returns the subscriber
		 *
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_Typo3wiki_Domain_Model_User> $subscriber
		 */
		public function getSubscriber() {
			return $this->subscriber;
		}

		/**
		 * Sets the subscriber
		 *
		 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_Typo3wiki_Domain_Model_User> $subscriber
		 * @return void
		 */
		public function setSubscriber(Tx_Extbase_Persistence_ObjectStorage $subscriber) {
			$this->subscriber = $subscriber;
		}

		/**
		 * Adds a TextRevision
		 *
		 * @param Tx_Typo3wiki_Domain_Model_TextRevision $revision
		 * @return void
		 */
		public function addRevision(Tx_Typo3wiki_Domain_Model_TextRevision $revision) {
			$this->revisions->attach($revision);
		}

		/**
		 * Removes a TextRevision
		 *
		 * @param Tx_Typo3wiki_Domain_Model_TextRevision $revisionToRemove The TextRevision to be removed
		 * @return void
		 */
		public function removeRevision(Tx_Typo3wiki_Domain_Model_TextRevision $revisionToRemove) {
			$this->revisions->detach($revisionToRemove);
		}

		/**
		 * Returns the revisions
		 *
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_Typo3wiki_Domain_Model_TextRevision> $revisions
		 */
		public function getRevisions() {
			return $this->revisions;
		}

		/**
		 * Sets the revisions
		 *
		 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_Typo3wiki_Domain_Model_TextRevision> $revisions
		 * @return void
		 */
		public function setRevisions(Tx_Extbase_Persistence_ObjectStorage $revisions) {
			$this->revisions = $revisions;
		}

		/**
		 * Returns the mainRevision
		 *
		 * @return Tx_Typo3wiki_Domain_Model_TextRevision $mainRevision
		 */
		public function getMainRevision() {
			return $this->mainRevision;
		}

		/**
		 * Sets the mainRevision
		 *
		 * @param Tx_Typo3wiki_Domain_Model_TextRevision $mainRevision
		 * @return void
		 */
		public function setMainRevision(Tx_Typo3wiki_Domain_Model_TextRevision $mainRevision) {
			$this->mainRevision = $mainRevision;
		}

		/**
		 * Adds a Page
		 *
		 * @param Tx_Typo3wiki_Domain_Model_Page $relatedPage
		 * @return void
		 */
		public function addRelatedPage(Tx_Typo3wiki_Domain_Model_Page $relatedPage) {
			$this->relatedPages->attach($relatedPage);
		}

		/**
		 * Removes a Page
		 *
		 * @param Tx_Typo3wiki_Domain_Model_Page $relatedPageToRemove The Page to be removed
		 * @return void
		 */
		public function removeRelatedPage(Tx_Typo3wiki_Domain_Model_Page $relatedPageToRemove) {
			$this->relatedPages->detach($relatedPageToRemove);
		}

		/**
		 * Returns the relatedPages
		 *
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_Typo3wiki_Domain_Model_Page> $relatedPages
		 */
		public function getRelatedPages() {
			return $this->relatedPages;
		}

		/**
		 * Sets the relatedPages
		 *
		 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_Typo3wiki_Domain_Model_Page> $relatedPages
		 * @return void
		 */
		public function setRelatedPages(Tx_Extbase_Persistence_ObjectStorage $relatedPages) {
			$this->relatedPages = $relatedPages;
		}

		/**
		 * Set Redirection
		 *
		 * @param Tx_Typo3wiki_Domain_Model_Page $redirection
		 */
		public function setRedirection(Tx_Typo3wiki_Domain_Model_Page $redirection) {
			$this->redirection = $redirection;
		}

		/**
		 * Get Redirection
		 *
		 * @return Tx_Typo3wiki_Domain_Model_Page
		 */
		public function getRedirection() {
			return $this->redirection;
		}

		/**
		 *  Remove the Redirection
		 */
		public function removeRedirection() {
			$this->redirection = NULL;
		}

		/**
		 * Adds a Page as Category
		 *
		 * @param Tx_Typo3wiki_Domain_Model_Page $relatedPage
		 * @return void
		 */
		public function addCategoryPage(Tx_Typo3wiki_Domain_Model_Page $relatedPage) {
			$this->categoryPages->attach($relatedPage);
		}

		/**
		 * Removes a Page as Category
		 *
		 * @param Tx_Typo3wiki_Domain_Model_Page $relatedPageToRemove The Page to be removed
		 * @return void
		 */
		public function removeCategoryPage(Tx_Typo3wiki_Domain_Model_Page $relatedPageToRemove) {
			$this->categoryPages->detach($relatedPageToRemove);
		}

		/**
		 * Set CategoryPages
		 *
		 * @param \Tx_Extbase_Persistence_ObjectStorage $categoryPages
		 */
		public function setCategoryPages($categoryPages) {
			$this->categoryPages = $categoryPages;
		}

		/**
		 * Get CategoryPages
		 *
		 * @return \Tx_Extbase_Persistence_ObjectStorage
		 */
		public function getCategoryPages() {
			return $this->categoryPages;
		}

		/**
		 * Set isCategory
		 *
		 * @param boolean $isCategory
		 */
		public function setIsCategory($isCategory) {
			$this->isCategory = $isCategory;
		}

		/**
		 * Get isCategory
		 *
		 * @return boolean
		 */
		public function getIsCategory() {
			return $this->isCategory;
		}

	}

?>