<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Pixelant\\PxaMynewsdesk\\Scheduler\\ImportTask'] = array (
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xlf:tx_pxamynewsdesk_scheduler_importtask.title',
	'description'      => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xlf:tx_pxamynewsdesk_scheduler_importtask.description',
	'additionalFields' => 'Pixelant\\PxaMynewsdesk\\Scheduler\\ImportAdditionalFieldsProvider'
);


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Pixelant\\PxaMynewsdesk\\Scheduler\\CleanerTask'] = array (
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xlf:tx_pxamynewsdesk_scheduler_importtask_cleanertask.title',
	'description'      => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xlf:tx_pxamynewsdesk_scheduler_cleanertask.description'
);
?>