<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

// Cleanup task
//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Pixelant\\pxa_mynewsdesk\\Scheduler\\Task\\GarbageCollectionTask'] = array(
//    'extension'   => $_EXTKEY,
//    'title'       => 'Sitemap garbage collection',
//    'description' => 'Cleanup old sitemap entries'
//);

//$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['pxa_mynewsdesk']);
//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['cliKeys'][$_EXTKEY] = array(
//             'EXT:' . $_EXTKEY . '/cli/scheduler_cli_dispatch.php',
//             '_CLI_scheduler'
//    );



//$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['scheduler']);

/*$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_pxamynewsdesk_importtask'] = array(
    'extension'        => $_EXTKEY,
    'title'            => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:scheduler.tx_pxamynewsdesk_title',
    'description'      => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:scheduler.tx_pxamynewsdesk_description'
);*/





$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['TYPO3\\PxaMynewsdesk\\Task\\ImportTask'] = array (
	'extension'        => $_EXTKEY,
	'title'            => 'tx_pxamynewsdesk_title',
	'description'      => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:scheduler.tx_pxamynewsdesk_description',
	'additionalFields' => 'ImportAdditionalFieldsProvider'
);


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['TYPO3\\PxaMynewsdesk\\Task\\CleanerTask'] = array (
	'extension'        => $_EXTKEY,
	'title'            => 'tx_pxamynewsdesk_cleanertitle',
	'description'      => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:scheduler.tx_pxamynewsdesk_cleanerdescription'
);
?>