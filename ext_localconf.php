<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

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

?>