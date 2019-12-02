<?php
defined('TYPO3_MODE') || die('Access denied.');

return (function () {
    $ll = 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xlf:tx_pxamynewsdesk_domain_model_importconfig';
    $llNews = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';

    return [
        'ctrl' => [
            'title' => $ll,
            'label' => 'title',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'cruser_id' => 'cruser_id',

            'origUid' => 't3_origuid',
            'delete' => 'deleted',
            'enablecolumns' => [
                'disabled' => 'hidden',
            ],
            'searchFields' => 'title,description,storage,source_url',
            'iconfile' => 'EXT:pxa_mynewsdesk/Resources/Public/Icons/config.svg'
        ],
        'interface' => [
            'showRecordFieldList' => 'hidden, title, storage, categories, source_url, type, import_tags',
        ],
        'types' => [
            '1' => ['showitem' => 'hidden;;1, title, storage, type, categories,  import_tags, source_url'],
        ],
        'palettes' => [
            '1' => ['showitem' => ''],
        ],
        'columns' => [
            'hidden' => [
                'exclude' => true,
                'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
                'config' => [
                    'type' => 'check',
                    'default' => 0
                ]
            ],
            'title' => [
                'exclude' => false,
                'label' => $ll . '.title',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'eval' => 'trim,required'
                ],
            ],
            'storage' => [
                'exclude' => false,
                'label' => $ll . '.storage',
                'config' => [
                    'type' => 'group',
                    'internal_type' => 'db',
                    'allowed' => 'pages',
                    'maxitems' => 1,
                    'size' => 1,
                    'fieldControl' => [
                        'editPopup' => [
                            'disabled' => false,
                        ],
                        'addRecord' => [
                            'disabled' => false,
                        ],
                        'listModule' => [
                            'disabled' => false,
                        ],
                    ],
                ],
            ],
            'type' => [
                'exclude' => false,
                'label' => $ll . '.type',
                'config' => [
                    'type' => 'select',
                    'items' => [
                        [$llNews . 'tx_news_domain_model_news.type.I.0', 0],
                        [$llNews . 'tx_news_domain_model_news.type.I.1', 1],
                        [$llNews . 'tx_news_domain_model_news.type.I.2', 2],
                    ],
                    'size' => 1,
                    'maxitems' => 1,
                ],
            ],
            'categories' => [
                'exclude' => false,
                'label' => $ll . '.categories',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectTree',
                    'foreign_table' => 'sys_category',
                    'foreign_table_where' => 'ORDER BY sys_category.title',
                    'size' => 20,
                    'treeConfig' => [
                        'parentField' => 'parent',
                        'appearance' => [
                            'expandAll' => true,
                            'showHeader' => true,
                        ],
                    ],
                ],
            ],
            'source_url' => [
                'exclude' => false,
                'label' => $ll . '.source_url',
                'config' => [
                    'type' => 'input',
                    'size' => 100,
                    'eval' => 'trim,required'
                ],
            ],
            'import_tags' => [
                'exclude' => 1,
                'label' => $ll . '.import_tags',
                'config' => [
                    'type' => 'check',
                    'renderType' => 'checkboxToggle',
                    'default' => 0,
                ]
            ],
        ],
    ];
})();
