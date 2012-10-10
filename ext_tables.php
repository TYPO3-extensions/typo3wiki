<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Typo3wiki',
	'TYPO3 Wiki'
);

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'TYPO3 Wiki');

t3lib_extMgm::addLLrefForTCAdescr('tx_typo3wiki_domain_model_page', 'EXT:typo3wiki/Resources/Private/Language/locallang_csh_tx_typo3wiki_domain_model_page.xlf');
t3lib_extMgm::allowTableOnStandardPages('tx_typo3wiki_domain_model_page');
$TCA['tx_typo3wiki_domain_model_page'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_domain_model_page',
		'label' => 'page_title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'page_title,subscriber,revisions,main_revision,related_pages,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Page.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_typo3wiki_domain_model_page.gif'
	),
);

t3lib_extMgm::addLLrefForTCAdescr('tx_typo3wiki_domain_model_textrevision', 'EXT:typo3wiki/Resources/Private/Language/locallang_csh_tx_typo3wiki_domain_model_textrevision.xlf');
t3lib_extMgm::allowTableOnStandardPages('tx_typo3wiki_domain_model_textrevision');
$TCA['tx_typo3wiki_domain_model_textrevision'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_domain_model_textrevision',
		'label' => 'write_date',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',

		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'write_date,unrendered_text,rendered_text,changes,owner,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/TextRevision.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_typo3wiki_domain_model_textrevision.gif'
	),
);

t3lib_div::loadTCA('fe_users');
if (!isset($TCA['fe_users']['ctrl']['type'])) {
	// no type field defined, so we define it here. This will only happen the first time the extension is installed!!
	$TCA['fe_users']['ctrl']['type'] = 'tx_extbase_type';
	$tempColumns = array();
	$tempColumns[$TCA['fe_users']['ctrl']['type']] = array(
		'exclude' => 1,
		'label'   => 'LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_domain_model_user.tx_extbase_type',
		'config' => array(
			'type' => 'select',
			'items' => array(
				array('LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_domain_model_user.tx_extbase_type.0','0'),
			),
			'size' => 1,
			'maxitems' => 1,
			'default' => 'Tx_Typo3wiki_User'
		)
	);
	t3lib_extMgm::addTCAcolumns('fe_users', $tempColumns, 1);
}

$TCA['fe_users']['types']['Tx_Typo3wiki_User']['showitem'] = $TCA['fe_users']['types']['Tx_Extbase_Domain_Model_FrontendUser']['showitem'];
$TCA['fe_users']['columns'][$TCA['fe_users']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_domain_model_user','Tx_Typo3wiki_User');
t3lib_extMgm::addToAllTCAtypes('fe_users', $TCA['fe_users']['ctrl']['type'],'','after:hidden');

$tmp_typo3wiki_columns = array(

);

t3lib_extMgm::addTCAcolumns('fe_users',$tmp_typo3wiki_columns);

$TCA['fe_users']['columns'][$TCA['fe_users']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:fe_users.tx_extbase_type.Tx_Typo3wiki_User','Tx_Typo3wiki_User');

$TCA['fe_users']['types']['Tx_Typo3wiki_User']['showitem'] = $TCA['fe_users']['types']['Tx_Extbase_Domain_Model_FrontendUser']['showitem'];
$TCA['fe_users']['types']['Tx_Typo3wiki_User']['showitem'] .= ',--div--;LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_domain_model_user,';
$TCA['fe_users']['types']['Tx_Typo3wiki_User']['showitem'] .= '';