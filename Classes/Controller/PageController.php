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
		//var_dump($this->request->getArgument('page'), get_class($page));
		//var_dump($page->getUid());
		//die();
		$settings = $this->configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		if($page === NULL ) $page = $this->pageRepository->findOneByPageTitle($this->request->getArgument('page'));
		if($page === NULL || $page->getMainRevision() === NULL){
			if($page === NULL ){
				$this->request->getArgument('page');
			}else{
				$target = $page->getPageTitle();
			}
			$this->redirect('unknownPage', NULL, NULL, array('page' => $target));
		}
		$redirection = NULL;
		try{
			$redirection = $this->request->getArgument('redirection');
		}catch(Exception $e){}
		if($page->getRedirection() != NULL) $this->redirect('show', NULL, NULL, array('page' => $page->getRedirection(), 'redirection' => $page->getPageTitle()));
		if($page->getMainRevision()->getRenderedText() === ''){
			if($page->getIsCategory() === true){
				$page = $this->checkCategoryAssociation($page);
			}
			$renderHelper = $this->createRenderHelper();
			$renderHelper->setRelatedPage($page);
			$page->getMainRevision()->setRenderedText($renderHelper->renderText($page->getMainRevision()->getUnrenderedText()));
		}
		$this->view->assign('redirection', $redirection);
		$this->view->assign('page', $page);
	}

	/**
	 * action unknownPage
	 *
	 * @param Tx_Typo3wiki_Domain_Model_Page $page
	 * @dontvalidate $page
	 *
	 * @return vpid
	 */
	public function unknownPageAction(Tx_Typo3wiki_Domain_Model_Page $page = NULL){
		if($page === NULL){
			$page = $this->request->getArgument('page');
		}else{
			$page = $page->getPageTitle();
		}
		$this->view->assign('page', $page);
	}

	/**
	 * action edit
	 *
	 * @param Tx_Typo3wiki_Domain_Model_Page $page
	 * @dontvalidate $page
     *
     * @param string $unrenderedText
     * @dontvalidate $unrenderedText
     *
     * @param string $changes
     * @dontvalidate $changes
     *
	 * @return void
	 */
	public function editAction(Tx_Typo3wiki_Domain_Model_Page $page = NULL, $unrenderedText = NULL, $changes = NULL) {
		if($page === NULL) $page = $this->pageRepository->findOneByPageTitle($this->request->getArgument('page'));
		if($page === NULL){
			$page = $this->objectManager->get('Tx_Typo3wiki_Domain_Model_Page');
			$page->setPageTitle($this->request->getArgument('page'));
			$this->pageRepository->add($page);
			$persistenceManager = t3lib_div::makeInstance('Tx_Extbase_Persistence_Manager');
			$persistenceManager->persistAll();
		}
        $preview = NULL;
        $myUnrenderedText = '';
        if($page->getMainRevision() !== NULL) $myUnrenderedText = $page->getMainRevision()->getUnrenderedText();
        if($unrenderedText !== NULL){
            $renderHelper = $this->createRenderHelper();
            $preview = $renderHelper->renderText($unrenderedText);
            $myUnrenderedText = $unrenderedText;
        }
        $this->view->assign('preview', $preview);
        var_dump($changes, $_POST);
        $this->view->assign('changes', $changes);
        $this->view->assign('page', $page);
        $this->view->assign('unrenderedText', $myUnrenderedText);
	}

	/**
	 * action update
	 *
	 * @param Tx_Typo3wiki_Domain_Model_Page $page
	 * @return void
	 */
	public function updateAction(Tx_Typo3wiki_Domain_Model_Page $page) {
  		if($page === NULL) $page = $this->pageRepository->findOneByPageTitle($this->request->getArgument('page'));
		$text = $this->request->getArgument('text');
        $changes = $this->request->getArgument('changes');
        try{
            $this->request->getArgument('preview');
            $preview = TRUE;
        }catch(Exception $e){
            $preview = FALSE;
        }

        if(isset($page) && $preview){
            $this->forward('edit', NULL, NULL, array('page' => $page, 'unrenderedText' => $text, 'changes' => $changes));
        }


        $revision = $this->objectManager->get('Tx_Typo3wiki_Domain_Model_TextRevision');
		$revision->setUnrenderedText($text);
		$revision->setWriteDate(new DateTime('NOW'));
		$revision->setRenderedText('');
        $revision->setChanges($changes);

		$redirection = preg_match('/\[\[REDIRECT:(.*)\]\]/i', $text, $matches);
		if($redirection === 1){
			$redirectionPage = $this->pageRepository->findOneByPageTitle($matches[1]);
			if($redirectionPage === NULL){
				$redirectionPage = $this->objectManager->get('Tx_Typo3wiki_Domain_Model_Page');
				$redirectionPage->setPageTitle($matches[1]);
				$this->pageRepository->add($redirectionPage);
			}
			$page->setRedirection($redirectionPage);
		}else{
			$page->removeRedirection();
		}
		/**
		 * Clear Cache of related Pages
		 * eg Link Generation
		 * eg Category Generation
		 */
		$renderHelper = $this->createRenderHelper();
		$renderHelper->setRelatedPage($page);
		if($page->getMainRevision() === NULL) $renderHelper->renderRelatedPages($text);
		$tmp = str_replace('{TOC}', '', $text);
		if(preg_match('/{.*?}/', $tmp) === 1) $renderHelper->renderCategoryPages($text);
		$page->addRevision($revision);
		$page->setMainRevision($revision);
		$this->pageRepository->update($page);
		$this->redirect('show', NULL, NULL, array('page' => $page));
	}

	/**
	 * Creates a RenderHelper
	 *
	 * @return Tx_Typo3wiki_Helper_RenderHelper
	 */
	private function createRenderHelper(){
		$renderHelper = $this->objectManager->get('Tx_Typo3wiki_Helper_RenderHelper');
		$renderHelper->setPageRepository($this->pageRepository);
		$renderHelper->setUriBuilder($this->uriBuilder);
		$renderHelper->setSettings($this->settings);
		$renderHelper->setObjectSettings($this->configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK));
		$renderHelper->setObjectManager($this->objectManager);

		return $renderHelper;
	}

	/**
	 * Checks if the current Page is still a Category Page and removes unused Relations
	 *
	 * @param Tx_Typo3wiki_Domain_Model_Page $page The CategoryPage
	 * @return Tx_Typo3wiki_Domain_Model_Page
	 */
	private function checkCategoryAssociation(Tx_Typo3wiki_Domain_Model_Page $page){
		foreach($page->getCategoryPages() as $catPage){
			if($catPage->getMainRevision() !== NULL && FALSE === strpos($catPage->getMainRevision()->getUnrenderedText(),'{'.$page->getPageTitle().'}')){
				$page->removeCategoryPage($catPage);
			}
		}
		$page->setIsCategory(($page->getCategoryPages()->count() !== 0));
		return $page;
	}

}