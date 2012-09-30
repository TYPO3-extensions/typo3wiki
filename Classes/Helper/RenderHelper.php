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
	// @todo  check if __DIR__ has ending slash
	require(__DIR__ . '/markdown.php');
	class Tx_Typo3wiki_Helper_RenderHelper extends MarkdownExtra_Parser {
		/**
		 * relatedPageSearch
		 *
		 * @var string
		 */
		private $relatedPageSearch = NULL;

		/**
		 * $relatedPageReplace
		 *
		 * @var string
		 */
		private $relatedPageReplace = NULL;

		/**
		 * pageRepository
		 *
		 * @var Tx_Typo3wiki_Domain_Repository_PageRepository
		 */
		protected $pageRepository;

		/**
		 * Uri Builder
		 *
		 * @var Tx_Extbase_MVC_Web_Routing_UriBuilder
		 */
		protected $uriBuilder;

		/**
		 * maxStage
		 *
		 * @var int
		 */
		protected $maxStage = 6;

		/**
		 * The Controllers Settings
		 *
		 * @var array
		 */
		protected $settings;

		/**
		 * The ObjectManager
		 *
		 * @var Tx_Extbase_Object_ObjectManagerInterface
		 */
		protected $objectManager;

		/**
		 * A Helper Variable should be NULL after each method.
		 *
		 * @var null
		 */
		protected $helper = NULL;

		/**
		 * Rendering Method to render TextRevision
		 *
		 * @param string $text
		 * @return string
		 */
		public function renderText($text) {
			$text = $this->transform($text);
			$text = $this->_renderExternalLinks($text);
			$text = $this->_renderInternalLinks($text);
			$text = $this->_renderHeadlineLinks($text);
			$text = $this->_renderContentList($text);
			return $text;
		}

		/**
		 * Render Related Pages, if current pages is new created
		 *
		 * @param Tx_Typo3wiki_Domain_Model_Page $page
		 * @return void
		 */
		public function renderRelatedPages(Tx_Typo3wiki_Domain_Model_Page $page) {
			foreach ($page->getRelatedPages() as $singlePage) {
				$text = $singlePage->getMainRevision()->getRenderedText();
				$text = $this->renderRelatedPagesHelper($text, $page);
				$singlePage->getMainRevision()->setRenderedText($text);
				// @todo check is is saved
			}
		}

		/**
		 * Removes unused class out of anchors with current page as target
		 * @todo write RenderRelatedPagesHelper Method
		 *
		 * @param string $text
		 * @param Tx_Typo3wiki_Domain_Model_Page $page
		 * @return void
		 */
		private function renderRelatedPagesHelper($text, Tx_Typo3wiki_Domain_Model_Page $page) {
			if ($this->relatedPageSearch === NULL || $this->relatedPageReplace === NULL) {
				$this->relatedPageSearch = '';
				$this->relatedPageReplace = '';
			}
			return str_replace($this->relatedPageSearch, $this->relatedPageReplace, $text);
		}

		/**
		 * @todo Adds the rendering of internalLinks to MarkUp
		 * @todo tx_typo3wiki_typo3wiki automatically?
		 *
		 * @param string $text
		 * @return string
		 */
		private function _renderInternalLinks($text) {
			$tmp = array();
			preg_match_all('#\[\[([^\]]*)\]\]#u', $text, $tmp);
			foreach($tmp[1] as $linkTitle){
				$linkTitle = explode('|', $linkTitle);
				if(count($linkTitle)==2){
					$link = $this->uriBuilder->setArguments(array('tx_typo3wiki_typo3wiki[action]' => 'show', 'tx_typo3wiki_typo3wiki[page]' => $linkTitle[1]));
					$link = $link->build();
					$cssClass = 'internal exists';
					$target = $this->pageRepository->findOneByPageTitle($linkTitle[1]);
					if($target === NULL) $cssClass = 'internal nonexists';
					$link = '<a href="'.$link.'" class="'.$cssClass.'">'.$linkTitle[0].'</a>';
					$text = str_replace('[['.$linkTitle[0].'|'.$linkTitle[1].']]', $link, $text);
				}else{
					$link = $this->uriBuilder->setArguments(array('tx_typo3wiki_typo3wiki[action]' => 'show', 'tx_typo3wiki_typo3wiki[page]' => $linkTitle[0]));
					$link = $link->build();
					$cssClass = 'internal exists';
					$target = $this->pageRepository->findOneByPageTitle($linkTitle[0]);
					if($target === NULL) $cssClass = 'internal nonexists';
					$link = '<a href="'.$link.'" class="'.$cssClass.'">'.$linkTitle[0].'</a>';
					$text = str_replace('[['.$linkTitle[0].']]', $link, $text);
				}

			}

			return $text;
		}

		/**
		 * Adds the rendering of externalLinks to MarkUp
		 *
		 * @param string $text
		 * @return string
		 */
		private function _renderExternalLinks($text) {
			$text = preg_replace('/<a href="(.*)"/', '$0 class="external" ', $text);
			return $text;
		}

		/**
		 * Adds the rendering of ContentList based on HeadLines to MarkUp
		 * @todo Make TOC Template Movable
		 *
		 * @param string $text
		 * @return string
		 */
		private function _renderContentList($text) {
			if(strpos($text, '{TOC}') === FALSE) return $text;
			$stageList = $this->_getContentListStage($text, 1);
			$tocView = $this->objectManager->create('Tx_Fluid_View_StandaloneView');
			$tocView->setFormat('html');
			$tocView->setLayoutRootPath('typo3conf/ext/typo3wiki/Resources/Private/Layouts');
			$tocView->setPartialRootPath('typo3conf/ext/typo3wiki/Resources/Private/Partials');
			$templateRootPath = t3lib_div::getFileAbsFileName( 'typo3conf/ext/typo3wiki/Resources/Private/Templates/Rendering/' );
			$templatePathAndFilename = $templateRootPath .'TableOfContents.html';
			$tocView->setTemplatePathAndFilename($templatePathAndFilename);
			$tocView->assign('stageList', $stageList);
			return str_replace('{TOC}', $tocView->render(), $text);
		}

		/**
		 * HelperMethod for Rendering ContentList
		 *
		 * @param string $text
		 * @param int $stage
		 * @return array
		 */
		private function _getContentListStage($text, $stage){
			$returnArray = array();
			if($stage == $this->maxStage) return $returnArray;
			preg_match_all('/<h'.$stage.'>(.*?)<\/h'.$stage.'>.*?((?:(?!<h'.$stage.'>).)*)/s', $text, $tmp);
			for($i = 0; $i < count($tmp[1]); $i++){
				$returnArray[$tmp[1][$i]] = $this->_getContentListStage($tmp[2][$i], $stage+1);
			}
			return $returnArray;
		}

		private function _renderHeadlineLinks($text){
			$this->helper = array();
			$text = preg_replace_callback('/<h.>(.*)<\/h.>/', array( $this, '_renderHeadlineLinksCall'), $text);
			$this->helper = NULL;
			return $text;
		}

		private function _renderHeadlineLinksCall($header){
			preg_match('/<h(.)>/', $header[0], $match);
			$level = $match[1];
			$header = $header[1];
			$header = trim($header);
			$shortTag = str_replace(' ', '_', $header);
			$shortTag = str_replace('#', '_', $shortTag);
			if(isset($this->helper[$header])){
				$this->helper[$header]++;
				$shortTag.= $this->helper[$header];
			}else{
				$this->helper[$header] = 0;
			}
			$header = '<a name="'.$shortTag.'"></a><h'.$level.'><a href="#'.$shortTag.'">'.$header.'</a></h'.$level.'>';
			return $header;
		}

		/**
		 *  Set Page Repository
		 *
		 * @param \Tx_Typo3wiki_Domain_Repository_PageRepository $pageRepository
		 */
		public function setPageRepository($pageRepository) {
			$this->pageRepository = $pageRepository;
		}

		/**
		 * Get Page Repository
		 *
		 * @return \Tx_Typo3wiki_Domain_Repository_PageRepository
		 */
		public function getPageRepository() {
			return $this->pageRepository;
		}

		/**
		 * Set Uri Builder
		 *
		 * @param Tx_Extbase_MVC_Web_Routing_UriBuilder $uriBuilder
		 */
		public function setUriBuilder($uriBuilder) {
			$this->uriBuilder = $uriBuilder;
		}

		/**
		 * Get Uri Builder
		 *
		 * @return Tx_Extbase_MVC_Web_Routing_UriBuilder
		 */
		public function getUriBuilder() {
			return $this->uriBuilder;
		}

		/**
		 * Set Settings
		 *
		 * @param array $settings
		 */
		public function setSettings($settings) {
			$this->settings = $settings;
		}

		/**
		 * Get Settings
		 *
		 * @return array
		 */
		public function getSettings() {
			return $this->settings;
		}

		/**
		 * Set ObjectManager
		 *
		 * @param \Tx_Extbase_Object_ObjectManagerInterface $objectManager
		 */
		public function setObjectManager($objectManager) {
			$this->objectManager = $objectManager;
		}

		/**
		 * Get Object Manager
		 *
		 * @return \Tx_Extbase_Object_ObjectManagerInterface
		 */
		public function getObjectManager() {
			return $this->objectManager;
		}

	}
