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
				'pid' => 'index',],],],
    'list'        => [
        'sorting'           => [
            'mode'                  => 4,
            'fields'                => ['sorting'],
            'panelLayout'           => 'filter;search,limit',
            'headerFields'          => ['title', 'tstamp'],
            'child_record_callback' => ['tl_block_module', 'addModuleInfo'],],
        'global_operations' => [
            'all' => [
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"',],],
        'operations'        => [
            'edit'       => [
                'label'           => &$GLOBALS['TL_LANG']['tl_block_module']['edit'],
                'href'            => 'table=tl_content',
                'icon'            => 'edit.gif',
                'button_callback' => ['tl_block_module', 'editContent'],],
            'editheader' => [
				'label' => &$GLOBALS['TL_LANG']['tl_block_module']['editheader'],
				'href'  => 'act=edit',
				'icon'  => 'header.gif',],
            'copy'       => [
				'label' => &$GLOBALS['TL_LANG']['tl_block_module']['copy'],
				'href'  => 'act=copy',
				'icon'  => 'copy.gif',],
            'cut'        => [
				'label'      => &$GLOBALS['TL_LANG']['tl_block_module']['cut'],
				'href'       => 'act=paste&amp;mode=cut',
				'icon'       => 'cut.gif',
				'attributes' => 'onclick="Backend.getScrollOffset()"',],
            'delete'     => [
				'label'      => &$GLOBALS['TL_LANG']['tl_block_module']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm']
								. '\')) return false; Backend.getScrollOffset();"',],
            'show'       => [
				'label' => &$GLOBALS['TL_LANG']['tl_block_module']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif',],],],
    // Palettes
    'palettes'    => [
        '__selector__' => ['type', 'feature', 'addWrapper'],
        'default'      => '{type_legend},type;{module_legend},module;{page_legend},addVisibility,pages,addPageDepth,keywords;{feature_legend},feature;{hide_legend},hide;{expert_legend:hide},addWrapper',
        'article'      => '{type_legend},type;{article_legend},articleAlias,imgSRC;{page_legend},addVisibility,pages,addPageDepth,keywords;{feature_legend},feature;{hide_legend},hide;{expert_legend:hide},addWrapper',
        'content'      => '{type_legend},type;{title_legend},title;{page_legend},addVisibility,pages,addPageDepth,keywords;{feature_legend},feature;{hide_legend},hide;{expert_legend:hide},addWrapper',],
    'subpalettes' => [
		'addWrapper' => 'headline,customTpl,customBlockTpl,cssID,space',
		'feature'    => 'feature_start,feature_stop,feature_count,feature_cookie_name,feature_cookie_expire,feature_cssID',],
    'fields'      => [
        'id'                    => [
            'label'  => ['ID'],
            'search' => true,
            'sql'    => "int(10) unsigned NOT NULL auto_increment",],
        'pid'                   => [
            'foreignKey' => 'tl_block.title',
            'sql'        => "int(10) unsigned NOT NULL default '0'",
            'relation'   => ['type' => 'belongsTo', 'load' => 'lazy'],],
        'sorting'               => [
			'sorting' => true,
			'flag'    => 2,
			'sql'     => "int(10) unsigned NOT NULL default '0'",],
        'tstamp'                => [
			'sql' => "int(10) unsigned NOT NULL default '0'",],
        'type'                  => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['type'],
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => ['default', 'article', 'content'],
            'eval'      => ['mandatory' => true, 'chosen' => true, 'submitOnChange' => true],
            'sql'       => "varchar(32) NOT NULL default 'default'",
            'reference' => &$GLOBALS['TL_LANG']['tl_block_module']['type_reference'],],
        'title'                 => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['title'],
            'inputType' => 'text',
            'sorting'   => true,
            'flag'      => 1,
            'search'    => true,
            'eval'      => ['mandatory' => true, 'maxlength' => 128, 'tl_class' => 'long'],
            'sql'       => "varchar(255) NOT NULL default ''",],
        'module'                => [
            'label'            => &$GLOBALS['TL_LANG']['tl_block_module']['module'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => ['tl_block_module', 'getModules'],
            'eval'             => ['mandatory' => true, 'chosen' => true, 'submitOnChange' => true],
            'wizard'           => [
                ['tl_block_module', 'editModule'],],
            'sql'              => "int(10) unsigned NOT NULL default '0'",],
        'articleAlias'          => [
            'label'            => &$GLOBALS['TL_LANG']['tl_block_module']['articleAlias'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => ['tl_block_module', 'getArticleAlias'],
            'eval'             => ['mandatory' => true, 'chosen' => true, 'submitOnChange' => true],
            'wizard'           => [
                ['tl_content', 'editArticleAlias'],],
            'sql'              => "int(10) unsigned NOT NULL default '0'",],
        'imgSRC'                => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['imgSRC'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => ['fieldType' => 'radio', 'filesOnly' => true],
            'sql'       => "binary(16) NULL",],
        'addVisibility'         => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['addVisibility'],
            'exclude'   => true,
            'inputType' => 'radio',
            'options'   => ['exclude', 'include'],
            'default'   => 'exclude',
            'reference' => &$GLOBALS['TL_LANG']['tl_block_module'],
            'eval'      => ['submitOnChange' => true],
            'sql'       => "varchar(32) NOT NULL default ''",
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
            'eval'      => ['tl_class' => 'clr'],
            'sql'       => "varchar(255) NOT NULL default ''",
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
            'sql'       => "char(1) NOT NULL default ''",],
        'feature_start'         => [
            'exclude'   => true,
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['feature_start'],
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",],
        'feature_stop'          => [
            'exclude'   => true,
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['feature_stop'],
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",],
        'feature_count'         => [
            'exclude'   => true,
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['feature_count'],
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'digit', 'maxlength' => 5, 'tl_class' => 'w50 wizard'],
            'sql'       => "int(10) unsigned NOT NULL default '0'",],
        'feature_cookie_name'   => [
            'exclude'       => true,
            'label'         => &$GLOBALS['TL_LANG']['tl_block_module']['feature_cookie_name'],
            'inputType'     => 'text',
            'save_callback' => [
                ['tl_block_module', 'setFeatureCookieName'],
            ],
            'eval'          => ['tl_class' => 'w50', 'maxlenght' => 64, 'unique' => true],
            'sql'           => "varchar(64) NOT NULL default ''",],
        'feature_cookie_expire' => [
            'exclude'       => true,
            'label'         => &$GLOBALS['TL_LANG']['tl_block_module']['feature_cookie_expire'],
            'inputType'     => 'text',
            'save_callback' => [
                ['tl_block_module', 'setFeatureCookieExpire'],
            ],
            'eval'          => ['tl_class' => 'wizard w50'],
            'sql'           => "varchar(10) NOT NULL default ''",],
        'feature_cssID'         => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['feature_cssID'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['multiple' => true, 'size' => 2, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",],
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
            'sql'       => "char(1) NOT NULL default ''",],
        'headline'              => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['headline'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'inputUnit',
            'options'   => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            'eval'      => ['maxlength' => 200],
            'sql'       => "varchar(255) NOT NULL default ''",],
        'customTpl'             => [
            'label'            => &$GLOBALS['TL_LANG']['tl_block_module']['customTpl'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => ['tl_block_module', 'getWrapperTemplates'],
            'eval'             => ['includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'],
            'sql'              => "varchar(64) NOT NULL default ''",],
        'customBlockTpl'        => [
            'label'            => &$GLOBALS['TL_LANG']['tl_block_module']['customBlockTpl'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => ['tl_block_module', 'getBlockTemplates'],
            'eval'             => ['includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'],
            'sql'              => "varchar(64) NOT NULL default ''",],
        'cssID'                 => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['cssID'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['multiple' => true, 'size' => 2, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",],
        'space'                 => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block_module']['space'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['multiple' => true, 'size' => 2, 'rgxp' => 'digit', 'nospace' => true, 'tl_class' => 'w50'],
            'sql'       => "varchar(64) NOT NULL default ''",],],];

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
			: ' <a href="contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $dc->value . '" title="' . sprintf(
				specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]),
				$dc->value
			) . '" style="padding-left:3px">' . $this->generateImage(
				'alias.gif',
				$GLOBALS['TL_LANG']['tl_content']['editalias'][0],
				'style="vertical-align:top"'
			) . '</a>';
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
			$arrLanguages = deserialize($GLOBALS['TL_CONFIG']['i18nl10n_languages']);
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
		$objModules = $this->Database->execute(
			"SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE type != 'block' ORDER BY t.name, m.name"
		);

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

			$objAlias = $this->Database->prepare(
				"SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid WHERE a.pid IN("
				. implode(',', array_map('intval', array_unique($arrPids))) . ") ORDER BY parent, a.sorting"
			)
				->execute($dc->id);
		} else {
			$objAlias = $this->Database->prepare(
				"SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid ORDER BY parent, a.sorting"
			)
				->execute($dc->id);
		}

		if ($objAlias->numRows) {
			System::loadLanguageFile('tl_article');

			while ($objAlias->next()) {
				$key                           = $objAlias->parent . ' (ID ' . $objAlias->pid . ')';
				$arrAlias[$key][$objAlias->id] =
					$objAlias->title . ' (' . ($GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn] ?: $objAlias->inColumn) . ', ID '
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
		}


		$objModule = $this->Database->prepare('SELECT name,type FROM tl_module WHERE id = ?')->execute($arrRow['module']);

		if ($objModule->numRows) {
			$output = '<div style="float:left">';
			$output .= '<img alt="" src="system/themes/' . $this->getTheme()
					   . '/images/modules.gif" style="vertical-align:text-bottom; margin-right: 4px;"/>';
			$output .= $objModule->name . ' <span style="color:#b3b3b3;padding-left:3px">['
					   . (isset($GLOBALS['TL_LANG']['FMD'][$objModule->type][0]) ? $GLOBALS['TL_LANG']['FMD'][$objModule->type][0] : $objModule->type)
					   . '] - ID:' . $arrRow['module'] . '</span>' . "</div>\n";
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
		$objRow = $this->Database->prepare("SELECT * FROM tl_layout WHERE pid=?")
			->limit(1)
			->execute($dc->activeRecord->pid);

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
}