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
// @todo Make preview Possible ( AJAX? )
// @todo implement User to System
class Tx_Typo3wiki_Controller_PageController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * pageRepository
	 *
	 * @var Tx_Typo3wiki_Domain_Repository_PageRepository
	 */
	protected $pageRepository;

	/**
	 * injectPageRepository
	 *
	 * @param Tx_Typo3wiki_Domain_Repository_PageRepository $pageRepository
	 * @return void
	 */
	public function injectPageRepository(Tx_Typo3wiki_Domain_Repository_PageRepository $pageRepository) {
		$this->pageRepository = $pageRepository;
	}

	/**
	 * action list
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->forward('show', NULL, NULL, array('page' => $this->settings['indexPageTitle']));
	}

	/**
	 * action show
	 *
	 * @param Tx_Typo3wiki_Domain_Model_Page $page
	 * @dontvalidate $page
	 * @return void
	 */
	public function showAction(Tx_Typo3wiki_Domain_Model_Page $page = NULL) {
		if($page === NULL ) $page = $this->pageRepository->findOneByPageTitle($this->request->getArgument('page'));
		if($page === NULL || $page->getMainRevision() === NULL){
			$this->redirect('unknownPage', NULL, NULL, array('page' => $this->request->getArgument('page')));
		}
		$this->view->assign('page', $page);
	}

	/**
	 * action unknownPage
	 *
	 * @return vpid
	 */
	public function unknownPageAction(){
		$page = $this->request->getArgument('page');
		$this->view->assign('page', $page);
	}

	/**
	 * action edit
	 *
	 * @param Tx_Typo3wiki_Domain_Model_Page $page
	 * @dontvalidate $page
	 * @return void
	 */
	public function editAction(Tx_Typo3wiki_Domain_Model_Page $page = NULL) {
		if($page === NULL) $page = $this->pageRepository->findOneByPageTitle($this->request->getArgument('page'));
		if($page === NULL){
			$page = $this->objectManager->get('Tx_Typo3wiki_Domain_Model_Page');
			$page->setPageTitle($this->request->getArgument('page'));
			$this->pageRepository->add($page);
			$persistenceManager = t3lib_div::makeInstance('Tx_Extbase_Persistence_Manager');
			$persistenceManager->persistAll();
		}
		$this->view->assign('page', $page);
	}

	/**
	 * action update
	 *
	 * @param Tx_Typo3wiki_Domain_Model_Page $page
	 * @return void
	 */
	public function updateAction(Tx_Typo3wiki_Domain_Model_Page $page) {
		if($page === NULL) $page = $this->pageRepository->findOneByPageTitle($this->request->getArgument('page'));
		$text = $this->request->getArgument('text') ;

		$renderHelper = $this->objectManager->get('Tx_Typo3wiki_Helper_RenderHelper');
		$renderHelper->setPageRepository($this->pageRepository);
		$renderHelper->setUriBuilder($this->uriBuilder);
		$renderHelper->setSettings($this->settings);
		$renderHelper->setObjectManager($this->objectManager);

		$revision = $this->objectManager->get('Tx_Typo3wiki_Domain_Model_TextRevision');
		$revision->setUnrenderedText($text);
		$revision->setRenderedText($renderHelper->renderText($revision->getUnrenderedText()));
		//echo $revision->getRenderedText(); die();

		if($page->getMainRevision() === NULL) $renderHelper->renderRelatedPages($page);
		$page->addRevision($revision);
		$page->setMainRevision($revision);
		$this->pageRepository->update($page);
		$this->redirect('show', NULL, NULL, array('page' => $page));
	}

}