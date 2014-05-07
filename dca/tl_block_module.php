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

$GLOBALS['TL_DCA']['tl_block_module'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'					  					=> 'tl_block',
		'enableVersioning'            => true,
		'onload_callback'							=> array(
				array('tl_block_module', 'invokeI18nl10n')
		),
		'sql' => array
		(
				'keys' => array
				(
						'id' => 'primary',
						'pid' => 'index'
				)
		)
	),
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('sorting'),
			'panelLayout'             => 'filter;search,limit',
			'headerFields'            => array('title', 'tstamp'),
			'child_record_callback'   => array('tl_block_module', 'addModuleInfo'),
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_block_module']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'				  => &$GLOBALS['TL_LANG']['tl_block_module']['copy'],
				'href'				  => 'act=copy',
				'icon'				  => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_block_module']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_block_module']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_block_module']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),
	// Palettes
	'palettes' => array
	(
		'__selector__'								=> array('type'),
		'default'                     => '{type_legend},type;{module_legend},module;{page_legend},addVisibility,pages,addPageDepth,keywords;{hide_legend},hide;{expert_legend:hide},cssID,space',
		'section'											=> '{type_legend},type;{section_legend},section,addSectionPageDepth;{page_legend},addVisibility,pages,addPageDepth,keywords;{hide_legend},hide;{expert_legend:hide},cssID,space',
		'article'											=> '{type_legend},type;{article_legend},articleAlias,imgSRC;{page_legend},addVisibility,pages,addPageDepth,keywords;{hide_legend},hide;{expert_legend:hide},cssID,space',
	),
	'fields' => array
	(
		'id' => array
		(
				'label'                   => array('ID'),
				'search'                  => true,
				'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
				'foreignKey'              => 'tl_block.title',
				'sql'                     => "int(10) unsigned NOT NULL default '0'",
				'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
		),
		'sorting' => array
		(
				'sorting'                 => true,
				'flag'                    => 2,
				'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
				'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_block_module']['type'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'									=> array('default', 'section', 'article'),
			'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true),
			'sql'											=> "varchar(32) NOT NULL default 'default'",
			'reference'								=> &$GLOBALS['TL_LANG']['tl_block_module']['type_reference'],
		),
		'addSectionPageDepth' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_block_module']['addSectionPageDepth'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'default'									=> true,
			'eval'                    => array('tl_class'=>'m12'),
			'sql'											=> "char(1) NOT NULL default ''",
		),
		'section' => array
		(
				'label'                   => &$GLOBALS['TL_LANG']['tl_block_module']['section'],
				'exclude'                 => true,
				'inputType'               => 'select',
				'options_callback'				=> array('tl_block_module', 'getCustomSections'),
				'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true),
				'sql'											=> "varchar(255) NOT NULL default ''",
		),
		'module' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_block_module']['module'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_block_module', 'getModules'),
			'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true),
			'wizard' => array
			(
				array('tl_block_module', 'editModule')
			),
			'sql'											=> "int(10) unsigned NOT NULL default '0'",
		),
		'articleAlias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_block_module']['articleAlias'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_block_module', 'getArticleAlias'),
			'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true),
			'wizard' => array
			(
					array('tl_content', 'editArticleAlias')
			),
			'sql'											=> "int(10) unsigned NOT NULL default '0'",
		),
		'imgSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_block_module']['imgSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'filesOnly'=>true, 'mandatory'=>true),
			'sql'                     => "binary(16) NULL"
		),
		'addVisibility' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_block_module']['addVisibility'],
			'exclude'                 => true,
			'inputType'               => 'radio',
			'options'                 => array('exclude', 'include'),
			'default'									=> 'exclude',
			'reference'               => &$GLOBALS['TL_LANG']['tl_block_module'],
			'eval'                    => array('submitOnChange'=>true),
			'sql'											=> "varchar(32) NOT NULL default ''",
		),
		'pages' 				=> array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_block_module']['pages'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array('fieldType'=>'checkbox', 'multiple'=>true),
			'sql'											=> "blob NULL",
		),
		'keywords'			=> array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_block_module']['keywords'],
			'exclude'                 => true,
			'inputType' 							=> 'text',
			'eval'										=> array('tl_class' => 'clr'),
			'sql'											=> "varchar(255) NOT NULL default ''",
		),
		'addPageDepth' 	=> array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_block_module']['addPageDepth'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'default'									=> true,
			'eval'                    => array('tl_class'=>'m12'),
			'sql'											=> "char(1) NOT NULL default ''",
		),
		'language'			=> array(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['i18nl10n_fields']['language']['label'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'default'									=> '',
			'reference'  							=> &$GLOBALS['TL_LANG']['LNG'],
			'options_callback'				=> array('tl_block_module', 'getI18nl10nLanguages'),
			'eval'      							=> array('mandatory'=>false, 'rgxp'=>'alpha', 'maxlength'=>2, 'nospace'=>true, 'tl_class'=>'w50 clr'),
			'sql'											=> "varchar(2) NOT NULL default ''"
		),
		'hide' 	=> array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_block_module']['hide'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options' 				  			=> array('1' => 'dont_hide', '2' => 'hide_logged_in', '3' => 'hide_not_logged_in'),
			'reference'				  			=> $GLOBALS['TL_LANG']['tl_block_module'],
			'eval'                    => array('tl_class'=>'m12'),
			'sql'											=> "char(1) NOT NULL default ''"
		),
		'cssID' => array
		(
				'label'                   => &$GLOBALS['TL_LANG']['tl_block_module']['cssID'],
				'exclude'                 => true,
				'inputType'               => 'text',
				'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50'),
				'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'space' => array
		(
				'label'                   => &$GLOBALS['TL_LANG']['tl_block_module']['space'],
				'exclude'                 => true,
				'inputType'               => 'text',
				'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50'),
				'sql'                     => "varchar(64) NOT NULL default ''"
		)
	)
);

class tl_block_module extends \Backend
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


	public function editModule(DataContainer $dc)
	{
		return ($dc->value < 1) ? '' : ' <a href="contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $dc->value . '" title="'.sprintf(specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $dc->value).'" style="padding-left:3px">' . $this->generateImage('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top"') . '</a>';
	}

	public function invokeI18nl10n(DataContainer $dc)
	{
		if(in_array('i18nl10n', $this->Config->getActiveModules()))
		{
			$this->loadLanguageFile('languages');
			$GLOBALS['TL_DCA']['tl_block_module']['palettes']['default'] = str_replace('keywords', 'keywords, language', $GLOBALS['TL_DCA']['tl_block_module']['palettes']['default']);
		}
	}

	public function getI18nl10nLanguages()
	{
		$arrLanguages = array();
		if(in_array('i18nl10n', $this->Config->getActiveModules()))
		{
			$arrLanguages = deserialize($GLOBALS['TL_CONFIG']['i18nl10n_languages']);
			array_unshift($arrLanguages,'');
		}
		return $arrLanguages;
	}

	/**
	 * Get all modules and return them as array
	 * @return array
	 */
	public function getModules()
	{
		$arrModules = array();
		$objModules = $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE type != 'block' ORDER BY t.name, m.name");

		while ($objModules->next())
		{
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
		}

		return $arrModules;
	}

	/**
	 * Get all articles and return them as array (article alias)
	 * @param \DataContainer
	 * @return array
	 */
	public function getArticleAlias(DataContainer $dc)
	{
		$arrPids = array();
		$arrAlias = array();

		if (!$this->User->isAdmin)
		{
			foreach ($this->User->pagemounts as $id)
			{
				$arrPids[] = $id;
				$arrPids = array_merge($arrPids, $this->Database->getChildRecords($id, 'tl_page'));
			}

			if (empty($arrPids))
			{
				return $arrAlias;
			}

			$objAlias = $this->Database->prepare("SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid WHERE a.pid IN(". implode(',', array_map('intval', array_unique($arrPids))) .") ORDER BY parent, a.sorting")
										->execute($dc->id);
		}
		else
		{
			$objAlias = $this->Database->prepare("SELECT a.id, a.pid, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid ORDER BY parent, a.sorting")
										->execute($dc->id);
		}

		if ($objAlias->numRows)
		{
			System::loadLanguageFile('tl_article');

			while ($objAlias->next())
			{
				$key = $objAlias->parent . ' (ID ' . $objAlias->pid . ')';
				$arrAlias[$key][$objAlias->id] = $objAlias->title . ' (' . ($GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn] ?: $objAlias->inColumn) . ', ID ' . $objAlias->id . ')';
			}
		}

		return $arrAlias;
	}

	/**
	 * Add the type and name of module element
	 * @param array
	 * @return string
	 */
	public function addModuleInfo($arrRow)
	{
		$output = $arrRow['id'];

		if($arrRow['type'] == 'section')
		{
			$output  = '<div style="float:left">';
			$output .= '<img alt="" src="system/themes/' . $this->getTheme() . '/images/layout.gif" style="vertical-align:text-bottom; margin-right: 4px;"/>';
			$output .= $arrRow['section'] .' <span style="color:#b3b3b3;padding-left:3px">['. $GLOBALS['TL_LANG']['tl_block_module']['section'][0] .']</span>' . "</div>\n";
			return $output;
		}
		elseif ($arrRow['type'] == 'article')
		{
			$objArticle = \ArticleModel::findByPk($arrRow['articleAlias']);

			$output  = '<div style="float:left">';
			$output .= '<img alt="" src="system/themes/' . $this->getTheme() . '/images/article.gif" style="vertical-align:text-bottom; margin-right: 4px;"/>';
			$output .= $objArticle->title .' <span style="color:#b3b3b3;padding-left:3px">['. $GLOBALS['TL_LANG']['tl_block_module']['articleAlias'][0] .']</span>' . "</div>\n";
			return $output;
		}

		$objModule = $this->Database->prepare('SELECT name,type FROM tl_module WHERE id = ?')->execute($arrRow['module']);

		if($objModule->numRows)
		{
			$output  = '<div style="float:left">';
			$output .= '<img alt="" src="system/themes/' . $this->getTheme() . '/images/modules.gif" style="vertical-align:text-bottom; margin-right: 4px;"/>';
			$output .= $objModule->name .' <span style="color:#b3b3b3;padding-left:3px">['. (isset($GLOBALS['TL_LANG']['FMD'][$objModule->type][0]) ? $GLOBALS['TL_LANG']['FMD'][$objModule->type][0] : $objModule->type) .'] - ID:'.$arrRow['module'].'</span>' . "</div>\n";
		}

		return $output;
	}

	public function getTypes(DataContainer $dc)
	{
		$options = array('default', 'section');

		$objBlock = HeimrichHannot\Blocks\BlockModel::findByPk($dc->activeRecord->pid);

		if($objBlock->carousel)
		{
			return array('article');
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

}