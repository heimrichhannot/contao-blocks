<?php

$this->loadDataContainer('tl_content');

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * @package Blocks
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_DCA']['tl_block_module'] = [

    // Config
    'config'      => [
        'dataContainer'    => 'Table',
        'ptable'           => 'tl_block',
        'ctable'           => ['tl_content'],
        'enableVersioning' => true,
        'onload_callback'  => [
            ['tl_block_module', 'invokeI18nl10n'],
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
            'child_record_callback' => ['tl_block_module', 'addModuleInfo'],
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
                'button_callback' => ['tl_block_module', 'editContent'],
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
                'button_callback' => ['tl_block_module', 'toggleIcon'],
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
            'options_callback' => ['tl_block_module', 'getContentBlockModulesAsOptions'],
            'eval'             => ['tl_class' => 'w50', 'mandatory' => true, 'includeBlankOption' => true, 'chosen' => true],
            'sql'              => "int(10) unsigned NOT NULL default '0'"
        ],
        'module'                => [
            'label'            => &$GLOBALS['TL_LANG']['tl_block_module']['module'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => ['tl_block_module', 'getModules'],
            'eval'             => ['mandatory' => true, 'chosen' => true, 'submitOnChange' => true],
            'wizard'           => [
                ['tl_block_module', 'editModule'],
            ],
            'sql'              => "int(10) unsigned NOT NULL default '0'",
        ],
        'articleAlias'          => [
            'label'            => &$GLOBALS['TL_LANG']['tl_block_module']['articleAlias'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => ['tl_block_module', 'getArticleAlias'],
            'eval'             => ['mandatory' => true, 'chosen' => true, 'submitOnChange' => true],
            'wizard'           => [
                ['tl_content', 'editArticleAlias'],
            ],
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
            'options_callback' => ['tl_block_module', 'getI18nl10nLanguages'],
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
                ['tl_block_module', 'setFeatureCookieName'],
            ],
            'eval'          => ['tl_class' => 'w50', 'maxlenght' => 64, 'unique' => true],
            'sql'           => "varchar(64) NOT NULL default ''",
        ],
        'feature_cookie_expire' => [
            'exclude'       => true,
            'label'         => &$GLOBALS['TL_LANG']['tl_block_module']['feature_cookie_expire'],
            'inputType'     => 'text',
            'save_callback' => [
                ['tl_block_module', 'setFeatureCookieExpire'],
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
            'options_callback' => function () {
                return System::getImageSizes();
            },
            'reference'        => &$GLOBALS['TL_LANG']['MSC'],
            'eval'             => ['rgxp' => 'natural', 'includeBlankOption' => true, 'nospace' => true, 'helpwizard' => true, 'tl_class' => 'w50'],
            'sql'              => "varchar(64) NOT NULL default ''",
        ],
        'customTpl'             => [
            'label'            => &$GLOBALS['TL_LANG']['tl_block_module']['customTpl'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => ['tl_block_module', 'getWrapperTemplates'],
            'eval'             => ['includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'],
            'sql'              => "varchar(64) NOT NULL default ''",
        ],
        'customBlockTpl'        => [
            'label'            => &$GLOBALS['TL_LANG']['tl_block_module']['customBlockTpl'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => ['tl_block_module', 'getBlockTemplates'],
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

class tl_block_module extends Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
        $this->loadLanguageFile('tl_content');
    }

    public function

    getContentBlockModulesAsOptions(\Contao\DataContainer $dc)
    {
        $options = [];

        $blockModules = $this->Database->prepare(
            "SELECT m.id, m.title, t.name AS 'theme' FROM tl_block_module m INNER JOIN tl_block b ON m.pid = b.id INNER JOIN tl_theme t ON t.id = b.pid WHERE m.type=? ORDER BY t.name, m.title"
        )->execute(
            'content'
        );

        if ($blockModules->numRows > 0) {
            while ($blockModules->next()) {
                $options[$blockModules->theme][$blockModules->id] = $blockModules->title . ' (ID ' . $blockModules->id . ')';
            }
        }

        return $options;
    }

    public function setFeatureCookieName($varValue, DataContainer $dc)
    {
        if ($varValue == '') {
            $varValue = 'block_feature_' . $dc->id;
        }

        return $varValue;
    }

    public function setFeatureCookieExpire($varValue, DataContainer $dc)
    {
        if ($varValue == '') {
            $varValue = (43200 * 60); // 30 Tage
        }

        return $varValue;
    }

    public function editModule(DataContainer $dc)
    {
        return ($dc->value < 1)
            ? ''
            : ' <a href="contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $dc->value . '&amp;rt=' . REQUEST_TOKEN . '" title="'
            . sprintf(specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $dc->value) . '" style="padding-left:3px">'
            . $this->generateImage('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top"') . '</a>';
    }

    public function invokeI18nl10n(DataContainer $dc)
    {
        if (in_array('i18nl10n', $this->Config->getActiveModules())) {
            $this->loadLanguageFile('languages');
            $GLOBALS['TL_DCA']['tl_block_module']['palettes']['default'] =
                str_replace('keywords', 'keywords, language', $GLOBALS['TL_DCA']['tl_block_module']['palettes']['default']);
        }
    }

    public function getI18nl10nLanguages()
    {
        $arrLanguages = [];
        if (in_array('i18nl10n', $this->Config->getActiveModules())) {
            $arrLanguages = version_compare(VERSION, '4.0', '<') ? deserialize($GLOBALS['TL_CONFIG']['i18nl10n_languages'],
                true) : \StringUtil::deserialize($GLOBALS['TL_CONFIG']['i18nl10n_languages'], true);;
            array_unshift($arrLanguages, '');
        }

        return $arrLanguages;
    }

    /**
     * Get all modules and return them as array
     *
     * @return array
     */
    public function getModules()
    {
        $arrModules = [];
        $objModules =
            $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE type != 'block' ORDER BY t.name, m.name");

        while ($objModules->next()) {
            $arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
        }

        return $arrModules;
    }

    /**
     * Get all articles and return them as array (article alias)
     *
     * @param \DataContainer
     *
     * @return array
     */
    public function getArticleAlias(DataContainer $dc)
    {
        $arrPids  = [];
        $arrAlias = [];

        if (!$this->User->isAdmin) {
            foreach ($this->User->pagemounts as $id) {
                $arrPids[] = $id;
                $arrPids   = array_merge($arrPids, $this->Database->getChildRecords($id, 'tl_page'));
            }

            if (empty($arrPids)) {
                return $arrAlias;
            }

            $objAlias =
                $this->Database->prepare("SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid WHERE a.pid IN("
                    . implode(',', array_map('intval', array_unique($arrPids))) . ") ORDER BY parent, a.sorting")
                    ->execute($dc->id);
        } else {
            $objAlias =
                $this->Database->prepare("SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid ORDER BY parent, a.sorting")
                    ->execute($dc->id);
        }

        if ($objAlias->numRows) {
            System::loadLanguageFile('tl_article');

            while ($objAlias->next()) {
                $key                           = $objAlias->parent . ' (ID ' . $objAlias->pid . ')';
                $arrAlias[$key][$objAlias->id] =
                    $objAlias->title . ' (' . ($GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn] ?? $objAlias->inColumn) . ', ID '
                    . $objAlias->id . ')';
            }
        }

        return $arrAlias;
    }

    /**
     * Add the type and name of module element
     *
     * @param array
     *
     * @return string
     */
    public function addModuleInfo($arrRow)
    {
        $output = $arrRow['id'];

        if ($arrRow['type'] == 'section') {
            $output = '<div style="float:left">';
            $output .= '<img alt="" src="system/themes/' . $this->getTheme()
                . '/images/layout.gif" style="vertical-align:text-bottom; margin-right: 4px;"/>';
            $output .= $arrRow['section'] . ' <span style="color:#b3b3b3;padding-left:3px">[' . $GLOBALS['TL_LANG']['tl_block_module']['section'][0]
                . ']</span>' . "</div>\n";

            return $output;
        } elseif ($arrRow['type'] == 'article') {
            $objArticle = \ArticleModel::findByPk($arrRow['articleAlias']);

            $output = '<div style="float:left">';
            $output .= '<img alt="" src="system/themes/' . $this->getTheme()
                . '/images/article.gif" style="vertical-align:text-bottom; margin-right: 4px;"/>';
            $output .= $objArticle->title . ' <span style="color:#b3b3b3;padding-left:3px">['
                . $GLOBALS['TL_LANG']['tl_block_module']['articleAlias'][0] . ']</span>' . "</div>\n";

            return $output;
        } elseif ($arrRow['type'] == 'content') {
            $output = '<div style="float:left">';
            $output .= '<img alt="" src="system/themes/' . $this->getTheme()
                . '/images/published.gif" style="vertical-align:text-bottom; margin-right: 4px;"/>';
            $output .= $arrRow['title'] . ' <span style="color:#b3b3b3;padding-left:3px">['
                . $GLOBALS['TL_LANG']['tl_block_module']['contentElements'] . ']</span>' . "</div>\n";

            return $output;
        } elseif ($arrRow['type'] == 'included_content') {
            $module = \Contao\Database::getInstance()->prepare('SELECT * FROM tl_block_module WHERE tl_block_module.id=?')->limit(1)->execute(
                $arrRow['contentBlockModuleAlias']
            );

            if ($module->numRows > 0)
            {
                $arrRow = $module->row();

                $output = '<div style="float:left">';
                $output .= '<img alt="" src="system/themes/' . $this->getTheme()
                    . '/images/published.gif" style="vertical-align:text-bottom; margin-right: 4px;"/>';
                $output .= $arrRow['title'] . ' <span style="color:#b3b3b3;padding-left:3px">['
                    . $GLOBALS['TL_LANG']['tl_block_module']['includedContentElements'] . ']</span>' . "</div>\n";

                return $output;
            } else {
                $output = '<div style="float:left">';
                $output .= $GLOBALS['TL_LANG']['tl_block_module']['type_reference'][$arrRow['type']] ?: $arrRow['type'];
                $output .= '</div>';
            }
        } else {
            if ($arrRow['type'] == 'default') {
                $objModule = $this->Database->prepare('SELECT name,type FROM tl_module WHERE id = ?')->execute($arrRow['module']);

                if ($objModule->numRows) {
                    $output = '<div style="float:left">';
                    $output .= '<img alt="" src="system/themes/' . $this->getTheme()
                        . '/images/modules.gif" style="vertical-align:text-bottom; margin-right: 4px;"/>';
                    $output .= $objModule->name . ' <span style="color:#b3b3b3;padding-left:3px">['
                        . (isset($GLOBALS['TL_LANG']['FMD'][$objModule->type][0]) ? $GLOBALS['TL_LANG']['FMD'][$objModule->type][0] : $objModule->type)
                        . '] - ID:' . $arrRow['module'] . '</span>' . "</div>\n";
                }
            } else {
                $output = '<div style="float:left">';
                $output .= $GLOBALS['TL_LANG']['tl_block_module']['type_reference'][$arrRow['type']] ?: $arrRow['type'];
                $output .= '</div>';
            }
        }


        return $output;
    }

    public function getTypes(DataContainer $dc)
    {
        $options = ['default', 'section'];

        $objBlock = HeimrichHannot\Blocks\BlockModel::findByPk($dc->activeRecord->pid);

        if ($objBlock->carousel) {
            return ['article'];
        }

        return $options;
    }

    public function getCustomSections(DataContainer $dc)
    {
        $objRow = $this->Database->prepare("SELECT * FROM tl_layout WHERE pid=?")->limit(1)->execute($dc->activeRecord->pid);

        return trimsplit(',', $objRow->sections);
    }

    public function editContent($row, $href, $label, $title, $icon, $attributes)
    {
        if ($row['type'] != 'content') {
            return '';
        }

        return '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>'
            . Image::getHtml($icon, $label) . '</a> ';
    }

    /**
     * Return all block wrapper templates as array
     *
     * @return array
     */
    public function getWrapperTemplates()
    {
        return $this->getTemplateGroup('blocks_wrapper_');
    }

    /**
     * Return all block templates as array
     *
     * @return array
     */
    public function getBlockTemplates()
    {
        return $this->getTemplateGroup('block_');
    }

    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        $user = \Contao\BackendUser::getInstance();

        if (strlen(\Contao\Input::get('tid'))) {
            $this->toggleVisibility(\Contao\Input::get('tid'), (\Contao\Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$user->hasAccess('tl_block_module::published', 'alexf')) {
            return '';
        }

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

        if (!$row['published']) {
            $icon = 'invisible.svg';
        }

        return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . \Image::getHtml($icon, $label,
                'data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
    }

    public function toggleVisibility($intId, $blnVisible, \DataContainer $dc = null)
    {
        $user     = \Contao\BackendUser::getInstance();
        $database = \Contao\Database::getInstance();

        // Set the ID and action
        \Contao\Input::setGet('id', $intId);
        \Contao\Input::setGet('act', 'toggle');

        if ($dc) {
            $dc->id = $intId; // see #8043
        }

        // Trigger the onload_callback
        if (is_array($GLOBALS['TL_DCA']['tl_block_module']['config']['onload_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_block_module']['config']['onload_callback'] as $callback) {
                if (is_array($callback)) {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                } elseif (is_callable($callback)) {
                    $callback($dc);
                }
            }
        }

        // Check the field access
        if (!$user->hasAccess('tl_block_module::published', 'alexf')) {
            throw new \Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to publish/unpublish quiz item ID ' . $intId . '.');
        }

        // Set the current record
        if ($dc) {
            $objRow = $database->prepare("SELECT * FROM tl_block_module WHERE id=?")->limit(1)->execute($intId);

            if ($objRow->numRows) {
                $dc->activeRecord = $objRow;
            }
        }

        $objVersions = new \Versions('tl_block_module', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_block_module']['fields']['published']['save_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_block_module']['fields']['published']['save_callback'] as $callback) {
                if (is_array($callback)) {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
                } elseif (is_callable($callback)) {
                    $blnVisible = $callback($blnVisible, $dc);
                }
            }
        }

        $time = time();

        // Update the database
        $database->prepare("UPDATE tl_block_module SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")->execute($intId);

        if ($dc) {
            $dc->activeRecord->tstamp    = $time;
            $dc->activeRecord->published = ($blnVisible ? '1' : '');
        }

        // Trigger the onsubmit_callback
        if (is_array($GLOBALS['TL_DCA']['tl_block_module']['config']['onsubmit_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_block_module']['config']['onsubmit_callback'] as $callback) {
                if (is_array($callback)) {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                } elseif (is_callable($callback)) {
                    $callback($dc);
                }
            }
        }

        $objVersions->create();
    }
}
