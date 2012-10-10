<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_typo3wiki_domain_model_page'] = array(
	'ctrl' => $TCA['tx_typo3wiki_domain_model_page']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, page_title, subscriber, redirection, is_category, category_pages, related_pages, main_revision, revisions',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, page_title, subscriber, redirection, is_category, category_pages, related_pages, --div--;LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_domain_model_page.revisions, main_revision, revisions, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_typo3wiki_domain_model_page',
				'foreign_table_where' => 'AND tx_typo3wiki_domain_model_page.pid=###CURRENT_PID### AND tx_typo3wiki_domain_model_page.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'page_title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_domain_model_page.page_title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'subscriber' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_domain_model_page.subscriber',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'MM' => 'tx_typo3wiki_page_user_mm',
				'size' => 10,
				'autoSizeMax' => 30,
				'maxitems' => 9999,
				'multiple' => 0,
				'wizards' => array(
					'_PADDING' => 1,
					'_VERTICAL' => 1,
					'edit' => array(
						'type' => 'popup',
						'title' => 'Edit',
						'script' => 'wizard_edit.php',
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
						),
					'add' => Array(
						'type' => 'script',
						'title' => 'Create new',
						'icon' => 'add.gif',
						'params' => array(
							'table' => 'fe_users',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'prepend'
							),
						'script' => 'wizard_add.php',
					),
				),
			),
		),
		'revisions' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_domain_model_page.revisions',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_typo3wiki_domain_model_textrevision',
				'foreign_field' => 'page',
				'maxitems'      => 9999,
				'appearance' => array(
					'collapseAll' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
			),
		),
		'main_revision' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_domain_model_page.main_revision',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_typo3wiki_domain_model_textrevision',
				'minitems' => 0,
				'maxitems' => 1,
				'appearance' => array(
					'collapseAll' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
			),
		),
		'redirection' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_domain_model_page.redirection',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_typo3wiki_domain_model_page',
				'minitems' => 0,
				'maxitems' => 1,
				'appearance' => array(
					'collapseAll' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
			),
		),
		'related_pages' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_domain_model_page.related_pages',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_typo3wiki_domain_model_page',
				'MM' => 'tx_typo3wiki_page_page_mm',
				'size' => 10,
				'autoSizeMax' => 30,
				'maxitems' => 9999,
				'multiple' => 0,
				'wizards' => array(
					'_PADDING' => 1,
					'_VERTICAL' => 1,
				),
			),
		),
		'is_category' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_domain_model_page.is_category',
			'config' => array(
				'type' => 'check',
			),
		),
		'category_pages' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:typo3wiki/Resources/Private/Language/locallang_db.xlf:tx_typo3wiki_domain_model_page.category_pages',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_typo3wiki_domain_model_page',
				'MM' => 'tx_typo3wiki_category_page_mm',
				'size' => 10,
				'autoSizeMax' => 30,
				'maxitems' => 9999,
				'multiple' => 0,
				'wizards' => array(
					'_PADDING' => 1,
					'_VERTICAL' => 1,
				),
			),
		),
	),
);

?>