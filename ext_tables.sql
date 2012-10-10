#
# Table structure for table 'tx_typo3wiki_domain_model_page'
#
CREATE TABLE tx_typo3wiki_domain_model_page (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	page_title varchar(255) DEFAULT '' NOT NULL,
	is_category tinyint(4) unsigned DEFAULT '0' NOT NULL,
	subscriber int(11) unsigned DEFAULT '0' NOT NULL,
	revisions int(11) unsigned DEFAULT '0' NOT NULL,
	main_revision int(11) unsigned DEFAULT '0',
	related_pages int(11) unsigned DEFAULT '0' NOT NULL,
	category_pages int(11) unsigned DEFAULT '0' NOT NULL,
	redirection int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),

	KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_typo3wiki_domain_model_textrevision'
#
CREATE TABLE tx_typo3wiki_domain_model_textrevision (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	page int(11) unsigned DEFAULT '0' NOT NULL,

	write_date int(11) DEFAULT '0' NOT NULL,
	unrendered_text text NOT NULL,
	rendered_text text NOT NULL,
	changes varchar(255) DEFAULT '' NOT NULL,
	owner int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	sorting int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),

	KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'fe_users'
#
CREATE TABLE fe_users (

	tx_extbase_type varchar(255) DEFAULT '' NOT NULL,

);

#
# Table structure for table 'tx_typo3wiki_domain_model_textrevision'
#
CREATE TABLE tx_typo3wiki_domain_model_textrevision (

	page  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_typo3wiki_page_user_mm'
#
CREATE TABLE tx_typo3wiki_page_user_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_typo3wiki_page_page_mm'
#
CREATE TABLE tx_typo3wiki_page_page_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_typo3wiki_category_page_mm'
#
CREATE TABLE tx_typo3wiki_category_page_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);