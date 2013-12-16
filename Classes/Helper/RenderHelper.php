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
	require_once(__DIR__ . '/markdown.php');
	require_once(__DIR__ . '/geshi.php');

	/**
	 * class Tx_Typo3wiki_Helper_RenderHelper
	 *
	 * @package typo3wiki
	 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
	 *
	 */
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
		 * The Controllers Object Settings
		 *
		 * @var array
		 */
		protected $objectSettings;

		/**
		 * The ObjectManager
		 *
		 * @var Tx_Extbase_Object_ObjectManagerInterface
		 */
		protected $objectManager;

		/**
		 * A Helper Variable should be NULL after each method.
		 *
		 * @var $helper NULL
		 */
		protected $helper = NULL;

		/**
		 * The RelatedPage
		 *
		 * @var $relatedPage Tx_Typo3wiki_Domain_Model_Page
		 */
		protected $relatedPage;

		/**
		 * Rendering Method to render TextRevision
		 *
		 * @param string $text
		 * @return string
		 */
		public function renderText($text) {
			$text = $this->transform($text);
			$text = $this->_renderCategories($text);
			$text = $this->_renderCategoriesList($text);
			$text = $this->_renderExternalLinks($text);
			$text = $this->_renderInternalLinks($text);
			$text = $this->_renderHeadlineLinks($text);
			$text = $this->_renderContentList($text);
			$text = $this->_renderOwnTextBlocks($text);
			$text = $this->_renderCodeHighlighting($text);
			return $text;
		}

		/**
		 * Method for getting a PageObject by Title. If Object does not exists, it will be created.
		 *
		 * @param string $title
		 * @return Tx_Typo3wiki_Domain_Model_Page
		 */
		protected function createPageIfNotExists($title) {
		$returnPage = $this->pageRepository->findOneByPageTitle($title);
		if($returnPage === NULL){
			$returnPage = $this->objectManager->get('Tx_Typo3wiki_Domain_Model_Page');
			$returnPage->setPageTitle($title);
			$this->pageRepository->add($returnPage);
			$persistenceManager = t3lib_div::makeInstance('Tx_Extbase_Persistence_Manager');
			$persistenceManager->persistAll();
		}
		return $returnPage;

		}

		/**
		 * Removes Cache of Related Categories; Fired on update
		 *
		 * @param string $text
		 * @return void
		 */
		public function renderCategoryPages($text) {
			$text = str_replace('{TOC}', '', $text);
			$text = str_replace('{LOC}', '', $text);
			$this->helper = array();
			$text = preg_replace_callback('/{(.*?)}/', array( $this, '_renderCategoryPagesHelper'), $text);
			$this->helper = NULL;
		}

		/**
		 * Helper Method for: Removes Cache of Related Categories; Fired on update
		 * Adds this page to all relatedCategoryPages
		 *
		 * @param mixed $cat
		 * @return mixed
		 */
		protected function _renderCategoryPagesHelper($cat){
			$cat = $cat[1];
			if($cat === 'TOC') return '';
			if($cat === 'LOC') return '';
			if(!isset($this->helper[$cat])){
				$catPage = $this->createPageIfNotExists($cat);
				if(!$catPage->getCategoryPages()->contains($this->relatedPage)){
					$catPage->addCategoryPage($this->relatedPage);
					$catPage->setIsCategory(TRUE);
				}
				$this->helper[$cat] = TRUE;
			}
			return '';
		}

		/**
		 * Removes Cache of Related Pages, if current pages is new created
		 *
		 * @return void
		 */
		public function renderRelatedPages() {
			foreach($this->relatedPage->getRelatedPages() as $related){
				$related->getMainRevision()->setRenderedText('');
			}
		}


		/**
		 * Adds the rendering of internalLinks to MarkUp
		 *
		 * @param string $text
		 * @return string
		 */
		protected function _renderInternalLinks($text) {
			$this->helper = array();
			$tmp = array();
			preg_match_all('#\[\[([^\]]*)\]\]#u', $text, $tmp);
			foreach($tmp[1] as $linkTitle){
				$linkTitle = explode('|', $linkTitle);
				if(count($linkTitle) == 2){
					$link = $this->uriBuilder->setArguments(array('tx_typo3wiki_typo3wiki[action]' => 'show', 'tx_typo3wiki_typo3wiki[page]' => $linkTitle[1]));
					$link = $link->buildFrontendUri();
					$cssClass = 'internal exists';
					if(!isset($this->helper[$linkTitle[1]])){
						$target = $this->createPageIfNotExists($linkTitle[1]);
						$this->helper[$linkTitle[1]] = $target;
					}else{
						$target = $this->helper[$linkTitle[1]];
					}
					if($target->getMainRevision() === NULL ) $cssClass = 'internal nonexists';
					$link = '<a href="' . $link . '" class="' . $cssClass . '">' . $linkTitle[0] . '</a>';
					$text = str_replace('[[' . $linkTitle[0] . '|' . $linkTitle[1] . ']]', $link, $text);
				}else{
					$link = $this->uriBuilder->setArguments(array('tx_typo3wiki_typo3wiki[action]' => 'show', 'tx_typo3wiki_typo3wiki[page]' => $linkTitle[0]));
					$link = $link->buildFrontendUri();
					$cssClass = 'internal exists';
					if(!isset($this->helper[$linkTitle[0]])){
						$target = $this->createPageIfNotExists($linkTitle[0]);
						$this->helper[$linkTitle[0]] = $target;
					}else{
						$target = $this->helper[$linkTitle[0]];
					}
					if( $target->getMainRevision() === NULL ) $cssClass = 'internal nonexists';
					$link = '<a href="' . $link . '" class="' . $cssClass . '">' . $linkTitle[0] . '</a>';
					$text = str_replace('[[' . $linkTitle[0] . ']]', $link, $text);
				}

			}
			if($this->relatedPage !== NULL){
				foreach($this->helper as $relatedPage){
					if($relatedPage->getMainRevision() === NULL){
						if(!$relatedPage->getRelatedPages()->contains($this->relatedPage)){
							$relatedPage->addRelatedPage($this->relatedPage);
						}
					}
				}
			}
			$this->helper = NULL;
			return $text;
		}

		/**
		 * Adds the rendering of externalLinks to MarkUp
		 *
		 * @param string $text
		 * @return string
		 */
		protected function _renderExternalLinks($text) {
			$text = preg_replace('/<a href="(.*)"/', '$0 class="external" ', $text);
			return $text;
		}

		/**
		 * Adds the rendering of ContentList based on HeadLines to MarkUp
		 *
		 * @param string $text
		 * @return string
		 */
		protected function _renderContentList($text) {
			if(strpos($text, '{TOC}') === FALSE) return $text;
			$return = $this->_getCurrentLevel($text, 1);
			return str_replace('{TOC}', $return, $text);
		}

		/**
		 * Helper Method for _renderContentList doing the magic ( recursivly )
		 *
		 * @param string $string
		 * @param int $stage
		 * @return string
		 */
		protected function _getCurrentLevel($string, $stage){
			$array = explode('<h' . $stage . '>', $string);
			$return = '';
			foreach($array as $i => $arrayField) {
				if($i == 0) continue;
				$internal_array = explode('</h' . $stage . '>', $arrayField);
				$return .= '<li>' . $internal_array[0];
				if($stage != 6) $return .= $this->_getCurrentLevel($internal_array[1], $stage + 1);
				$return .= '</li>';
			}
			if($return != '') $return = '<ul>' . $return . '</ul>';
			return $return;
		}

		/**
		 * Renders HeadLines to Links
		 *
		 * @param string $text
		 * @return string
		 */
		protected function _renderHeadlineLinks($text){
			$this->helper = array();
			$text = preg_replace_callback('/<h.>(.*)<\/h.>/', array( $this, '_renderHeadlineLinksCall'), $text);
			$this->helper = NULL;
			return $text;
		}

		/**
		 * Renders HeadLines to Links Call
		 *
		 * @param string $header
		 * @return string
		 */
		protected function _renderHeadlineLinksCall($header){
			preg_match('/<h(.)>/', $header[0], $match);
			$level = $match[1];
			$header = $header[1];
			$header = trim($header);
			$shortTag = str_replace(' ', '_', $header);
			$shortTag = str_replace('#', '_', $shortTag);
			if(isset($this->helper[$header])){
				$this->helper[$header]++;
				$shortTag .= $this->helper[$header];
			}else{
				$this->helper[$header] = 0;
			}
			$header = '<a name="' . $shortTag . '"></a><h' . $level . '><a href="#' . $shortTag . '">' . $header . '</a></h' . $level . '>';
			return $header;
		}

		/**
		 * Renders the Categories View
		 *
		 * @param string $text
		 * @return string
		 */
		protected function _renderCategories($text){
			$this->helper = array();
			$text = preg_replace_callback('/{(.*?)}/', array( $this, '_renderCategoriesHelper'), $text);
			$tocView = $this->createViewHelper('PageCategories');
			$tocView->assign('categories', $this->helper[1]);

			$helper = $tocView->render();
			$helper = $this->_renderInternalLinks($helper);
			$this->helper = NULL;
			return $text . $helper;
		}

		/**
		 * Renders the Categories View Helper
		 *
		 * @param string $cat
		 * @return string $cat
		 */
		protected function _renderCategoriesHelper($cat) {
			if($cat[0] === '{TOC}') return $cat[0];
			if($cat[0] === '{LOC}') return $cat[0];
			$this->helper[1][] = $cat[1];
			if($this->relatedPage !== NULL){
				$tmpPage = $this->createPageIfNotExists($cat[1]);
				if(!$tmpPage->getCategoryPages()->contains($this->relatedPage)) $tmpPage->addCategoryPage($this->relatedPage);
				if($tmpPage->getIsCategory() === FALSE)     $tmpPage->setIsCategory(TRUE);
			}
			return '';
		}

		/**
		 * Renders the List of Pages related to this Category ( Page )
		 *
		 * @param string $text
		 * @return string $text
		 */
		protected function _renderCategoriesList($text) {
			if(strpos($text, '{LOC}') !== FALSE){
				if($this->relatedPage !== NULL){
					$tocView = $this->createViewHelper('CategoryList');
					$tocView->assign('pages', $this->relatedPage->getCategoryPages());
					$helper = $tocView->render();
					$text = str_replace('{LOC}', $helper, $text);
				}
			}
			return $text;
		}

		/**
		 * Method for SyntaxHighlighting
		 *
		 * @param string $text
		 * @return string
		 */
		protected function _renderCodeHighlighting($text) {
			$text = preg_replace_callback('/<p>LanG:\s*(.*?)<\/p>.*?<pre>.?<code>(.*?)<\/code>.?<\/pre>/ism', array( $this, '_renderCodeHighlighting_Helper'), $text);
			return $text;
		}

		/**
		 * Method for renderingOwnTextBlocks
		 *
		 * Usage:
		 * !!! myClass
		 * hey ho
		 * !!!
		 *
		 * Will be rendered as:
		 * <div class="myClass">
		 * hey ho
		 * </div>
		 *
		 * @param string $text
		 * @return string
		 */
		protected function _renderOwnTextBlocks($text) {
			return preg_replace('/!!!\s*?(\w*?)\\n(.*?)\\n!!!/m', '<div class="$1">$2</div>', $text);
		}

		/**
		 * Helper Method for Syntax Highlighting
		 *
		 * @param string $text
		 * @return string
		 */
		protected function _renderCodeHighlighting_Helper($text){
			$geshi = new GeSHi(htmlspecialchars_decode($text[2]), $text[1]);
			$geshi->enable_classes();
			$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
			return '<style type="text/css" scoped="scoped">' . $geshi->get_stylesheet() . '</style><pre>' . $geshi->parse_code() . '</pre>';
		}

		/**
		 * Create Stand Alone Viewhelper
		 *
		 * @param string $templateName
		 * @return Tx_Fluid_View_StandaloneView
		 */
		protected function createViewHelper($templateName){
			$tocView = $this->objectManager->create('Tx_Fluid_View_StandaloneView');
			$tocView->setFormat('html');
			$tocView->setLayoutRootPath(t3lib_div::getFileAbsFileName($this->objectSettings['view']['layoutRootPath']));
			$tocView->setPartialRootPath(t3lib_div::getFileAbsFileName($this->objectSettings['view']['partialRootPath']));
			$tocView->setTemplatePathAndFilename(t3lib_div::getFileAbsFileName($this->objectSettings['view']['templateRootPath']) . 'Rendering/' . $templateName . '.html');
			return $tocView;
		}

		/**
		 *  Set Page Repository
		 *
		 * @param \Tx_Typo3wiki_Domain_Repository_PageRepository $pageRepository
		 * @return void
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
		 * @return void
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
		 * @return void
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
		 * Set objectSettings
		 *
		 * @param array $objectSettings
		 * @return void
		 */
		public function setObjectSettings($objectSettings) {
			$this->objectSettings = $objectSettings;
		}

		/**
		 * Get objectSettings
		 *
		 * @return array
		 */
		public function getObjectSettings() {
			return $this->objectSettings;
		}


		/**
		 * Set ObjectManager
		 *
		 * @param \Tx_Extbase_Object_ObjectManagerInterface $objectManager
		 * @return void
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

		/**
		 * function setRelatedPage
		 *
		 * @param Tx_Typo3wiki_Domain_Model_Page $relatedPage
		 * @return void
		 */
		public function setRelatedPage($relatedPage) {
			$this->relatedPage = $relatedPage;
		}

		/**
		 * function getRelatedPage
		 *
		 * @return Tx_Typo3wiki_Domain_Model_Page
		 */
		public function getRelatedPage() {
			return $this->relatedPage;
		}


	}
