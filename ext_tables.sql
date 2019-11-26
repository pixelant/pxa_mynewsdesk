#
# Table structure for table 'tx_pxamynewsdesk_domain_model_importconfig'
#
CREATE TABLE tx_pxamynewsdesk_domain_model_importconfig (
	title varchar(255) DEFAULT '' NOT NULL,
	description varchar(255) DEFAULT '' NOT NULL,
	newpid int(11) DEFAULT '0' NOT NULL,
	newstable varchar(100) DEFAULT '' NOT NULL,
	newscat text,
	mapping text,
	newsurl varchar(255) DEFAULT '' NOT NULL,
	newstype int(11) unsigned DEFAULT '0' NOT NULL,
    import_tags tinyint(4) DEFAULT '0' NOT NULL,
);