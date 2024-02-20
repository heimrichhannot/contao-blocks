<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * @package Blocks
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

use HeimrichHannot\Blocks\DataContainer\BlockModuleContainer;

$this->loadDataContainer('tl_content');

$GLOBALS['TL_DCA']['tl_block_module'] = [

    // Config
    'config'      => [
        'dataContainer'    => 'Table',
        'ptable'           => 'tl_block',
        'ctable'           => ['tl_content'],
        'enableVersioning' => true,
        'onload_callback'  => [
            [BlockModuleContainer::class, 'invokeI18nl10n'],
        ],
        'sql'              => [
            'keys' => [
                'id'  => 'primary',
                'pid' => 'index',
            ],
        ],
    ],
    'list'        => [
        'sorting'           => [
            'mode'                  => 4,
            'fields'                => ['sorting'],
            'panelLayout'           => 'filter;search,limit',
            'headerFields'          => ['title', 'tstamp'],
            'child_record_callback' => [BlockModuleContainer::class, 'addModuleInfo'],
        ],
        'global_operations' => [
            'all' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"',
            ],
        ],
        'operations'        => [
            'edit'       => [
                'label'           => &$GLOBALS['TL_LANG']['tl_block_module']['edit'],
                'href'            => 'table=tl_content',
                'icon'            => 'edit.gif',
                'button_callback' => [BlockModuleContainer::class, 'editContent'],
            ],
            'editheader' => [
                'label' => &$GLOBALS['TL_LANG']['tl_block_module']['editheader'],
                'href'  => 'act=edit',
                'icon'  => 'header.gif',
            ],
            'copy'       => [
                'label' => &$GLOBALS['TL_LANG']['tl_block_module']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif',
            ],
            'cut'        => [
                'label'      => &$GLOBALS['TL_LANG']['tl_block_module']['cut'],
                'href'       => 'act=paste&amp;mode=cut',
                'icon'       => 'cut.gif',
                'attributes' => 'onclick="Backend.getScrollOffset()"',
            ],
            'delete'     => [
                'label'      => &$GLOBALS['TL_LANG']['tl_block_module']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if (!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null)
                    . '\')) return false; Backend.getScrollOffset();"',
            ],
            'toggle'     => [
                'label'           => &$GLOBALS['TL_LANG']['tl_block_module']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => [BlockModuleContainer::class, 'toggleIcon'],
            ],
            'show'       => [
                'label' => &$GLOBALS['TL_LANG']['tl_block_module']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif',
            ],
        ],
    ],
    // Palettes
    'palettes'    => [
        '__selector__'     => ['type', 'feature', 'addWrapper', 'published'],
        'default'          => '{type_legend},type;{module_legend},module;{page_legend},addVisibility,pages,addPageDepth,keywords,keywordPages;{feature_legend},feature;{hide_legend},hide;{expert_legend:hide},addWrapper,uncached,published',
        'article'          => '{type_legend},type;{article_legend},articleAlias,imgSRC;{page_legend},addVisibility,pages,addPageDepth,keywords,keywordPages;{feature_legend},feature;{hide_legend},hide;{expert_legend:hide},addWrapper,uncached,published',
        'content'          => '{type_legend},type;{title_legend},title;{page_legend},addVisibility,pages,addPageDepth,keywords,keywordPages;{feature_legend},feature;{hide_legend},hide;{expert_legend:hide},addWrapper,uncached,published',
        'included_content' => '{type_legend},type;{content_block_module_legend},contentBlockModuleAlias;{page_legend},addVisibility,pages,addPageDepth,keywords,keywordPages;{feature_legend},feature;{hide_legend},hide;{expert_legend:hide},addWrapper,uncached,published',
    ],
    'subpalettes' => [
        'addWrapper' => 'headline,backgroundSRC,backgroundSize,customTpl,customBlockTpl,cssID,space',
        'feature'    => 'feature_start,feature_stop,feature_count,feature_cookie_name,feature_cookie_expire,feature_cssID',
        'published'  => 'start,stop',
    ],
    'fields'      => [
        'id'                    => [
            'label'  => ['ID'],
            'search' => true,
            'sql'    => "int(10) unsigned NOT NULL auto_increment",
        ],
        'pid'                   => [
            'foreignKey' => 'tl_block.title',
            'sql'        => "int(10) unsigned NOT NULL default '0'",
            'relation'   => ['type' => 'belongsTo', 'load' => 'lazy'],
        ],
        'sorting'               => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'tstamp'                => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'type'                  => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['type'],
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => ['default', 'article', 'content', 'included_content'],
            'eval'      => ['mandatory' => true, 'chosen' => true, 'submitOnChange' => true],
            'sql'       => "varchar(32) NOT NULL default 'default'",
            'reference' => &$GLOBALS['TL_LANG']['tl_block_module']['type_reference'],
        ],
        'title'                 => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['title'],
            'inputType' => 'text',
            'sorting'   => true,
            'search'    => true,
            'eval'      => ['mandatory' => true, 'maxlength' => 128, 'tl_class' => 'long'],
            'sql'       => "varchar(128) NOT NULL default ''",
        ],
        'contentBlockModuleAlias'       => [
            'label'            => &$GLOBALS['TL_LANG']['tl_block_module']['contentBlockModuleAlias'],
            'exclude'          => true,
            'filter'           => true,
            'inputType'        => 'select',
            'options_callback' => [BlockModuleContainer::class, 'getContentBlockModulesAsOptions'],
            'eval'             => ['tl_class' => 'w50', 'mandatory' => true, 'includeBlankOption' => true, 'chosen' => true],
            'sql'              => "int(10) unsigned NOT NULL default '0'"
        ],
        'module'                => [
            'label'            => &$GLOBALS['TL_LANG']['tl_block_module']['module'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => [BlockModuleContainer::class, 'getModules'],
            'eval'             => ['mandatory' => true, 'chosen' => true, 'submitOnChange' => true],
            'wizard'           => [[BlockModuleContainer::class, 'editModule']],
            'sql'              => "int(10) unsigned NOT NULL default '0'",
        ],
        'articleAlias'          => [
            'label'            => &$GLOBALS['TL_LANG']['tl_block_module']['articleAlias'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => [BlockModuleContainer::class, 'getArticleAlias'],
            'eval'             => ['mandatory' => true, 'chosen' => true, 'submitOnChange' => true],
            'wizard'           => [['tl_content', 'editArticleAlias']],
            'sql'              => "int(10) unsigned NOT NULL default '0'",
        ],
        'imgSRC'                => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['imgSRC'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => ['fieldType' => 'radio', 'filesOnly' => true],
            'sql'       => "binary(16) NULL",
        ],
        'addVisibility'         => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['addVisibility'],
            'exclude'   => true,
            'inputType' => 'radio',
            'options'   => ['exclude', 'include'],
            'default'   => 'exclude',
            'reference' => &$GLOBALS['TL_LANG']['tl_block_module'],
            'eval'      => ['submitOnChange' => true],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
        'pages'                 => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['pages'],
            'exclude'   => true,
            'inputType' => 'pageTree',
            'eval'      => ['fieldType' => 'checkbox', 'multiple' => true],
            'sql'       => "blob NULL",
        ],
        'keywords'              => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['keywords'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['tl_class' => 'clr', 'maxlength' => 196],
            'sql'       => "varchar(196) NOT NULL default ''",
        ],
        'keywordPages'          => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['keywordPages'],
            'exclude'   => true,
            'inputType' => 'pageTree',
            'eval'      => ['fieldType' => 'checkbox', 'multiple' => true],
            'sql'       => "blob NULL",
        ],
        'addPageDepth'          => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['addPageDepth'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => true,
            'eval'      => ['tl_class' => 'm12'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'language'              => [
            'label'            => &$GLOBALS['TL_LANG']['MSC']['i18nl10n_fields']['language']['label'],
            'exclude'          => true,
            'inputType'        => 'select',
            'default'          => '',
            'reference'        => &$GLOBALS['TL_LANG']['LNG'],
            'options_callback' => [BlockModuleContainer::class, 'getI18nl10nLanguages'],
            'eval'             => ['mandatory' => false, 'rgxp' => 'alpha', 'maxlength' => 2, 'nospace' => true, 'tl_class' => 'w50 clr'],
            'sql'              => "varchar(2) NOT NULL default ''",
        ],
        'feature'               => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['feature'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['tl_class' => 'clr', 'submitOnChange' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'feature_start'         => [
            'exclude'   => true,
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['feature_start'],
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
        'feature_stop'          => [
            'exclude'   => true,
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['feature_stop'],
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
        'feature_count'         => [
            'exclude'   => true,
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['feature_count'],
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'digit', 'maxlength' => 5, 'tl_class' => 'w50 wizard'],
            'sql'       => "int(10) unsigned NOT NULL default '0'",
        ],
        'feature_cookie_name'   => [
            'exclude'       => true,
            'label'         => &$GLOBALS['TL_LANG']['tl_block_module']['feature_cookie_name'],
            'inputType'     => 'text',
            'save_callback' => [
                [BlockModuleContainer::class, 'setFeatureCookieName'],
            ],
            'eval'          => ['tl_class' => 'w50', 'maxlenght' => 64, 'unique' => true],
            'sql'           => "varchar(64) NOT NULL default ''",
        ],
        'feature_cookie_expire' => [
            'exclude'       => true,
            'label'         => &$GLOBALS['TL_LANG']['tl_block_module']['feature_cookie_expire'],
            'inputType'     => 'text',
            'save_callback' => [
                [BlockModuleContainer::class, 'setFeatureCookieExpire'],
            ],
            'eval'          => ['tl_class' => 'wizard w50'],
            'sql'           => "varchar(10) NOT NULL default ''",
        ],
        'feature_cssID'         => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['feature_cssID'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['multiple' => true, 'maxlength' => 255, 'size' => 2, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'hide'                  => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['hide'],
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => ['1' => 'dont_hide', '2' => 'hide_logged_in', '3' => 'hide_not_logged_in'],
            'reference' => $GLOBALS['TL_LANG']['tl_block_module'],
            'eval'      => ['tl_class' => 'm12'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'addWrapper'            => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['addWrapper'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['tl_class' => 'clr', 'submitOnChange' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'headline'              => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['headline'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'inputUnit',
            'options'   => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            'eval'      => ['maxlength' => 196],
            'sql'       => "varchar(196) NOT NULL default ''",
        ],
        'backgroundSRC'         => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['backgroundSRC'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => ['filesOnly' => true, 'fieldType' => 'radio', 'tl_class' => 'clr'],
            'sql'       => "binary(16) NULL",
        ],
        'backgroundSize'        => [
            'label'            => &$GLOBALS['TL_LANG']['tl_block_module']['backgroundSize'],
            'exclude'          => true,
            'inputType'        => 'imageSize',
            'options_callback' => [BlockModuleContainer::class, 'getImageSizeOptions'],
            'reference'        => &$GLOBALS['TL_LANG']['MSC'],
            'eval'             => ['rgxp' => 'natural', 'includeBlankOption' => true, 'nospace' => true, 'helpwizard' => true, 'tl_class' => 'w50'],
            'sql'              => "varchar(64) NOT NULL default ''",
        ],
        'customTpl'             => [
            'label'            => &$GLOBALS['TL_LANG']['tl_block_module']['customTpl'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => [BlockModuleContainer::class, 'getWrapperTemplates'],
            'eval'             => ['includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'],
            'sql'              => "varchar(64) NOT NULL default ''",
        ],
        'customBlockTpl'        => [
            'label'            => &$GLOBALS['TL_LANG']['tl_block_module']['customBlockTpl'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => [BlockModuleContainer::class, 'getBlockTemplates'],
            'eval'             => ['includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'],
            'sql'              => "varchar(64) NOT NULL default ''",
        ],
        'cssID'                 => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['cssID'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['multiple' => true, 'maxlength' => 255, 'size' => 2, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'space'                 => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['space'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['multiple' => true, 'size' => 2, 'rgxp' => 'digit', 'nospace' => true, 'tl_class' => 'w50'],
            'sql'       => "varchar(64) NOT NULL default ''",
        ],
        'published'             => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['published'],
            'exclude'   => true,
            'filter'    => true,
            'inputType' => 'checkbox',
            'eval'      => ['doNotCopy' => true, 'submitOnChange' => true],
            'sql'       => "char(1) NOT NULL default '1'",
        ],
        'start'                 => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['start'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
        'stop'                  => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['stop'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
        'uncached'              => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['uncached'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['tl_class' => 'clr'],
            'sql'       => "char(1) NOT NULL default ''",
        ]
    ],
];
