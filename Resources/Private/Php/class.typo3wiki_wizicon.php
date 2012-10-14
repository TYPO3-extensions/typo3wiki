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
 * Add news extension to the wizard in page module
 *
 * @package TYPO3
 * @subpackage tx_typo3wiki
 */
class typo3wiki_typo3wiki_wizicon {

		const KEY = 'typo3wiki';

		/**
		 * Processing the wizard items array
		 *
		 * @param array $wizardItems The wizard items
		 * @return Modified array with wizard items
		 */
		public function proc($wizardItems) {
			$wizardItems['plugins_tx_' . self::KEY] = array(
				'icon' => t3lib_extMgm::extRelPath(self::KEY) . 'Resources/Public/Icons/ce_wiz.gif',
				'title' => $GLOBALS['LANG']->sL('LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_plugin_title'),
				'description' => $GLOBALS['LANG']->sL('LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_plugin_desc'),
				'params' => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=' . self::KEY . '_typo3wiki'
			);

			return $wizardItems;
		}
	}

	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/typo3wiki/Resources/Private/Php/class.typo3wiki_wizicon.php']) {
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/typo3wiki/Resources/Private/Php/class.typo3wiki_wizicon.php']);
	}