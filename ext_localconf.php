<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_crontab_pi1.php', '_pi1', '', 0);

/*$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_pxamynewsdesk_importtask'] = array(
    'extension'        => $_EXTKEY,
    'title'            => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:scheduler.tx_pxamynewsdesk_title',
    'description'      => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:scheduler.tx_pxamynewsdesk_description'
);*/

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_pxamynewsdesk_importtask'] = array (
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xlf:scheduler.tx_pxamynewsdesk_title',
	'description'      => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:scheduler.tx_pxamynewsdesk_description',
	'additionalFields' => 'tx_pxamynewsdesk_importadditionalfieldsprovider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_pxamynewsdesk_cleanertask'] = array (
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:scheduler.tx_pxamynewsdesk_cleanertitle',
	'description'      => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:scheduler.tx_pxamynewsdesk_cleanerdescription'
);
?>