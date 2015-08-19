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

$GLOBALS['TL_DCA']['tl_block'] = array
(

	// Config
	'config'      => array
	(
		'dataContainer'     => 'Table',
		'ptable'            => 'tl_theme',
		'ctable'            => array('tl_block_module'),
		'enableVersioning'  => true,
		'onsubmit_callback' => array(
			array('tl_block', 'updateFEModule')
		),
		'oncopy_callback'   => array(
			array('tl_block', 'copyBlock')
		),
		'ondelete_callback' => array(
			array('tl_block', 'deleteFEModule')
		),
		'sql'               => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),
	'list'        => array
	(
		'sorting'           => array
		(
			'mode'        => 2,
			'fields'      => array('title'),
			'flag'        => 1,
			'panelLayout' => 'sort,search,limit'
		),
		'label'             => array
		(
			'fields' => array('title'),
			'format' => '%s',
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations'        => array
		(
			'edit'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_block']['edit'],
				'href'  => 'table=tl_block_module',
				'icon'  => 'edit.gif',
			),
			'editHeader' => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_block']['editHeader'],
				'href'  => 'act=edit',
				'icon'  => 'header.gif'
			),
			'copy'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_block']['copy'],
				'href'  => 'act=copy',
				'icon'  => 'copy.gif'
			),
			'delete'     => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_block']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm']
								. '\')) return false; Backend.getScrollOffset();"'
			),
			'show'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_block']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			)
		)
	),
	// Palettes
	'palettes'    => array
	(
		'__selector__' => array('carousel'),
		'default'      => '{title_legend},title,carousel;{expert_legend:hide},cssClass'
	),
	'subpalettes' => array
	(
		'carousel' => 'carouselType',
	),
	'fields'      => array
	(
		'id'           => array
		(
			'sql' => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid'          => array
		(
			'foreignKey' => 'tl_theme.name',
			'sql'        => "int(10) unsigned NOT NULL default '0'",
			'relation'   => array('type' => 'belongsTo', 'load' => 'eager')
		),
		'tstamp'       => array
		(
			'sql' => "int(10) unsigned NOT NULL default '0'"
		),
		'module'       => array
		(
			'sql' => "int(10) unsigned NOT NULL default '0'"
		),
		'title'        => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_block']['title'],
			'inputType' => 'text',
			'sorting'   => true,
			'flag'      => 1,
			'search'    => true,
			'eval'      => array('mandatory' => true, 'maxlength' => 128, 'tl_class' => 'w50'),
			'sql'       => "varchar(255) NOT NULL default ''"
		),
		'carousel'     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_block']['carousel'],
			'exclude'   => true,
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'clr', 'submitOnChange' => true),
			'sql'       => "char(1) NOT NULL default ''",
		),
		'carouselType' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_block']['carouselType'],
			'exclude'   => true,
			'inputType' => 'select',
			'options'   => array_keys($GLOBALS['BLOCKS']['CAROUSEL']),
			'reference' => &$GLOBALS['TL_LANG']['tl_block']['carouselTypes'],
			'eval'      => array('tl_class' => 'clr', 'chosen' => true),
			'sql'       => "varchar(128) NOT NULL default ''",
		),
		'cssClass'     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_block']['cssClass'],
			'exclude'   => true,
			'inputType' => 'text',
			'eval'      => array('tl_class' => 'w50'),
			'sql'       => "varchar(255) NOT NULL default ''"
		),
	)
);

class tl_block extends \Backend
{
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	public function createBlockModule($objBlock)
	{
		$strTitle = $objBlock->title;

		// create new module, if non existing yet
		if(($objModule = \ModuleModel::findByPk($objBlock->module)) === null)
		{
			$objModule = new \ModuleModel();
			$objModule->pid = $objBlock->pid;
			$objModule->type = 'block';
			$objModule->block = $objBlock->id;
		}

		// always update title and tstamp
		$objModule->name = $strTitle;
		$objModule->tstamp = $objBlock->tstamp;
		$objModule->save();

		// set frontend module id for current block
		$objBlock = \HeimrichHannot\Blocks\BlockModel::findByPk($objBlock->id);
		$objBlock->module = $objModule->id;
		$objBlock->save();
	}

	public function copyBlock($insertID, DataContainer $dc)
	{
		$objBlock = \HeimrichHannot\Blocks\BlockModel::findByPk($insertID);
		$objBlock->module = 0;

		if($objBlock === null) return;

		$this->createBlockModule($objBlock);

	}

	public function updateFEModule(DataContainer $dc)
	{
		$objBlock = new \HeimrichHannot\Blocks\BlockModel();
		$objBlock->setRow($dc->activeRecord->row());
		$this->createBlockModule($objBlock);
	}

	public function deleteFEModule(DataContainer $dc)
	{
		if(($objModule = \ModuleModel::findByPk($dc->activeRecord->module)) !== null)
		{
			$objModule->delete();
		}
	}
}