<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Mynewsdesk import');

t3lib_extMgm::addLLrefForTCAdescr('tx_pxamynewsdesk_domain_model_importlog', 'EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_csh_tx_pxamynewsdesk_domain_model_importlog.xml');
#t3lib_extMgm::allowTableOnStandardPages('tx_pxamynewsdesk_domain_model_importlog');
$TCA['tx_pxamynewsdesk_domain_model_importlog'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:tx_pxamynewsdesk_domain_model_importlog',
		'label' => 'hash',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
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
		'readOnly' => true,
		'searchFields' => 'hash,newstable,newsid,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/ImportLog.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_pxamynewsdesk_domain_model_importlog.gif'
	),
);

t3lib_extMgm::addLLrefForTCAdescr('tx_pxamynewsdesk_domain_model_importconfig', 'EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_csh_tx_pxamynewsdesk_domain_model_importconfig.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_pxamynewsdesk_domain_model_importconfig');
$TCA['tx_pxamynewsdesk_domain_model_importconfig'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:tx_pxamynewsdesk_domain_model_importconfig',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
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
		'searchFields' => 'title,description,newpid,newstable,mapping,newsurl,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/ImportConfig.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_pxamynewsdesk_domain_model_importconfig.gif'
	),
);

?>