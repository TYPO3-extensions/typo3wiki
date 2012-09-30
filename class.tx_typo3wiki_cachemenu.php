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


	require_once(PATH_typo3 . 'interfaces/interface.backend_cacheActionsHook.php');
	//require_once(t3lib_extMgm::extPath('realurl_clearcache') . 'class.tx_realurlclearcache.php');

	class tx_typo3wiki_cachemenu implements backend_cacheActionsHook {
		/**
		 * Adds the option to clear the TYPO3Wiki cache in the back-end clear cache menu.
		 *
		 * @param array $a_cacheActions
		 * @param array $a_optionValues
		 * @return void
		 * @see typo3/interfaces/backend_cacheActionsHook#manipulateCacheActions($cacheActions, $optionValues)
		 */
		public function manipulateCacheActions(&$a_cacheActions, &$a_optionValues) {
			if (($GLOBALS['BE_USER']->isAdmin() || $GLOBALS['BE_USER']->getTSConfigVal('options.clearCache.typo3wiki')) && $GLOBALS['TYPO3_CONF_VARS']['EXT']['extCache']) {
				$s_title = $GLOBALS['LANG']->sL('LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_backend.cacheMenu', TRUE);
				$s_imagePath = t3lib_extMgm::extRelPath('typo3wiki') . 'Resources/Public/Icons/cache.gif';
				$a_cacheActions[] = array(
					'id' => 'typo3wiki',
					'title' => $s_title,
					'href' => 'ajax.php?ajaxID=tx_typo3wiki::clear',
					'icon' => '<img src="'.$s_imagePath.'" title="'.$s_title.'" alt="'.$s_title.'" />',
				);
				$a_optionValues[] = 'clearCacheTYPO3Wiki';
			}
		}
	}