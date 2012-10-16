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
	if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'])) {
		$register = array(
			'wiki' => array (
				array(
					'GETvar' => 'tx_typo3wiki_typo3wiki[action]',
					//'value' => 'show'
				),
				array(
					//tx_typo3wiki_typo3wiki%5Bcontroller%5D=Page
					'GETvar' => 'tx_typo3wiki_typo3wiki[controller]',
					'value' => 'Page'
				),
				array(
					'GETvar' => 'tx_typo3wiki_typo3wiki[page]',
					'lookUpTable' => array (
						'table' => 'tx_typo3wiki_domain_model_page',
						'id_field' => 'uid',
						'alias_field' => 'page_title',
						'addWhereClause'=> 'AND NOT deleted',
						'useUniqueCache'=> 1,
						'useUniqueCache_conf' => array (
							'strtolower' => 1,
							'spaceCharacter' => '-',
						),
					),
				),
			),
		);

		foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'] as $domain => $config) {
			if (is_array($config)) {
				$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'][$domain]['postVarSets']['_DEFAULT'] = array_merge(
					$register, (array) $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'][$domain]['postVarSets']['_DEFAULT']
				);
			}

			unset($config);
		}

		unset($register);
		reset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']);
	}
