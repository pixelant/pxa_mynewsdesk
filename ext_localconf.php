<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_pxamynewsdesk_importtask'] = array (
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:scheduler.tx_pxamynewsdesk_title',
	'description'      => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:scheduler.tx_pxamynewsdesk_description',
	'additionalFields' => 'tx_pxamynewsdesk_importadditionalfieldsprovider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_pxamynewsdesk_cleanertask'] = array (
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:scheduler.tx_pxamynewsdesk_cleanertitle',
	'description'      => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:scheduler.tx_pxamynewsdesk_cleanerdescription'
);
?>