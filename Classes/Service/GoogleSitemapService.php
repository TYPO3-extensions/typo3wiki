<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Bastian Bringenberg <bastian.bringenberg@typo3.org>, BBNetz.eu
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
 * class Tx_Typo3wiki_Service_GoogleSitemapService
 *
 * @package typo3wiki
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_Typo3wiki_Service_GoogleSitemapService implements Tx_GoogleServices_Interface_SitemapProviderInterface {

	/**
	 * function getRecords
	 *
	 * @param int $startPage
	 * @param array $basePages
	 * @param Tx_GoogleServices_Controller_SitemapController $obj
	 * @return array
	 */
	public function getRecords($startPage, $basePages, Tx_GoogleServices_Controller_SitemapController $obj) {
		$nodes = array();

		if (!t3lib_extMgm::isLoaded('typo3wiki')) {
			return $nodes;
		}
		$pid = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_typo3wiki.']['view.']['defaultPid'];
		$objectManager = new Tx_Extbase_Object_ObjectManager();
		$repo = $objectManager->get('Tx_Typo3wiki_Domain_Repository_PageRepository');
		$settings = $objectManager->get('Tx_Extbase_Persistence_Typo3QuerySettings');
		$settings->setRespectStoragePage(FALSE);
		$query = $repo->createQuery();
		$query->setQuerySettings($settings);
		$founds = $query->execute();

		foreach($founds as $found) {
			$uriBuilder = $obj->getUriBuilder();
			$uriBuilder->setTargetPageUid($pid);
			$url = $uriBuilder->uriFor('show', array('page' => $found->getPageTitle()), 'Page', 'typo3wiki', 'Typo3wiki');
			if ($url === '') {
				continue;
			}
			$node = new Tx_GoogleServices_Domain_Model_Node();
			$node->setLoc($baseUri . $url);
			$node->setPriority(0.9);
			$node->setChangefreq('weekly');
				// LastMod will come later
				// $node->setLastmod($found->getTstamp()->getTimestamp($found));
			$nodes[] = $node;
		}
		return $nodes;
	}
}
