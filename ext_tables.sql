#
# Table structure for table 'tx_pxamynewsdesk_domain_model_importconfig'
#
CREATE TABLE tx_pxamynewsdesk_domain_model_importconfig (
	title varchar(255) DEFAULT '' NOT NULL,
	storage int(11) DEFAULT '0' NOT NULL,
	categories varchar(255) DEFAULT '' NOT NULL,
	source_url varchar(255) DEFAULT '' NOT NULL,
	type int(11) unsigned DEFAULT '0' NOT NULL,
    import_tags tinyint(4) DEFAULT '0' NOT NULL,
);