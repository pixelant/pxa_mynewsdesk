<?php
defined('TYPO3_MODE') || die('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Pixelant\PxaMynewsdesk\Scheduler\ImportTask::class] = [
    'extension' => 'pxa_mynewsdesk',
    'title' => 'MyNewsDesk import',
    'description' => 'Import of MyNewsDesk news items into TYPO3 news extension',
    'additionalFields' => \Pixelant\PxaMynewsdesk\Scheduler\ImportAdditionalFieldsProvider::class
];
