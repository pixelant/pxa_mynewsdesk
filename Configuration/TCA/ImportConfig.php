<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_pxamynewsdesk_domain_model_importconfig'] = array(
	'ctrl' => $TCA['tx_pxamynewsdesk_domain_model_importconfig']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, description, newpid, newstable, newscat, mapping, newsurl,newstype',
	),
	'types' => array(
		'1' => array('showitem' => 'hidden;;1, title, description, newpid, newstable, newscat, mapping, newsurl,newstype,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_pxamynewsdesk_domain_model_importconfig',
				'foreign_table_where' => 'AND tx_pxamynewsdesk_domain_model_importconfig.pid=###CURRENT_PID### AND tx_pxamynewsdesk_domain_model_importconfig.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xlf:tx_pxamynewsdesk_domain_model_importconfig.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'description' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xlf:tx_pxamynewsdesk_domain_model_importconfig.description',
			'config' => array(
				'type' => 'input',
				'size' => 150,
				'eval' => 'trim'
			),
		),
		'newpid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xlf:tx_pxamynewsdesk_domain_model_importconfig.newpid',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'pages',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
				'eval' => 'required',
				'wizards' => array(
					'suggest' => array(
						'type' => 'suggest'
					)
				)
			),
		),
		'newstable' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xlf:tx_pxamynewsdesk_domain_model_importconfig.newstable',
			'config' => array(
				'type' => 'select',
				'items' => array(
					//array('tt_news', 'tt_news'),
					array('news', 'tx_news_domain_model_news'),
				),
				'size' => 1,
				'maxitems' => 1,
				'eval' => ''
			),
		),
		'newscat' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xlf:tx_pxamynewsdesk_domain_model_importconfig.newscat',
			'config' => array(
				'type' => 'input',
				'size' => 25,
				'eval' => 'trim'
			),
		),
		'mapping' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xlf:tx_pxamynewsdesk_domain_model_importconfig.mapping',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
        'newstype' => array(
            'exclude' => 0,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xlf:tx_pxamynewsdesk_domain_model_importconfig.newstype',
            'config' => array(
                'type' => 'select',
                'items' => array(
                    array($ll . 'tx_news_domain_model_news.type.I.0', 0),
                    array($ll . 'tx_news_domain_model_news.type.I.1', 1),
                    array($ll . 'tx_news_domain_model_news.type.I.2', 2),
                ),
                'size' => 1,
                'maxitems' => 1,
            )
        ),
		'newsurl' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xlf:tx_pxamynewsdesk_domain_model_importconfig.newsurl',
			'config' => array(
				'type' => 'input',
				'size' => 100,
				'eval' => 'trim,required'
			),
		),
	),
);


?>