<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

	if(t3lib_extMgm::isLoaded('realurl')) require_once(t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/Realurl/realurl_conf.php');

	Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Typo3wiki',
	array(
		'Page' => 'index, show, unknownPage, edit, update',
		
	),
	// non-cacheable actions
	array(
		'Page' => 'create, update',
		
	)
);

/**
 * Enabling Caching Button in Backend
 *
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions'][] = 'EXT:'.$_EXTKEY.'/class.tx_typo3wiki_cachemenu.php:&tx_typo3wiki_cachemenu';
$TYPO3_CONF_VARS['BE']['AJAX']['tx_typo3wiki::clear'] = 'EXT:'.$_EXTKEY.'/class.tx_typo3wiki_cache.php:tx_typo3wiki_cache->clear';