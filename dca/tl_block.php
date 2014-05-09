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
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'					  					=> 'tl_theme',
		'ctable'                      => array('tl_block_module'),
		'enableVersioning'            => true,
		'onsubmit_callback'						=> array(
			array('tl_block', 'updateFEModule')
		),
		'ondelete_callback'						=> array(
			array('tl_block', 'deleteFEModule')
		),
		'sql' => array
		(
				'keys' => array
				(
						'id' 	=> 'primary'
				)
		)
	),
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('title'),
			'flag'                    => 1,
			'panelLayout'             => 'sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s',
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
				'label'               => &$GLOBALS['TL_LANG']['tl_block']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'				  => &$GLOBALS['TL_LANG']['tl_block']['copy'],
				'href'				  => 'act=copy',
				'icon'				  => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_block']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'compose' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_block']['compose'],
				'href'                => 'table=tl_block_module',
				'icon'                => 'modules.gif',
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_block']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),
	// Palettes
	'palettes' => array
	(
		'__selector__'								=> array('carousel'),
		'default'                     => '{title_legend},title,carousel;{expert_legend:hide},cssClass'
	),
	'subpalettes' => array
	(
		'carousel'									=> 'carouselType',
	),
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_theme.name',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager')
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'module' => array
		(
				'sql'                   => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_block']['title'],
			'inputType'               => 'text',
			'sorting'                 => true,
			'flag'                    => 1,
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'carousel' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_block']['carousel'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'clr', 'submitOnChange' => true),
			'sql'											=> "char(1) NOT NULL default ''",
		),
		'carouselType' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_block']['carouselType'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'									=> array_keys($GLOBALS['BLOCKS']['CAROUSEL']),
			'reference'								=> &$GLOBALS['TL_LANG']['tl_block']['carouselTypes'],
			'eval'                    => array('tl_class'=>'clr', 'chosen' => true),
			'sql'											=> "varchar(128) NOT NULL default ''",
		),
		'cssClass' => array
		(
				'label'                   => &$GLOBALS['TL_LANG']['tl_block']['cssClass'],
				'exclude'                 => true,
				'inputType'               => 'text',
				'eval'                    => array('tl_class'=>'w50'),
				'sql'                     => "varchar(255) NOT NULL default ''"
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

	public function updateFEModule(DataContainer $dc)
	{
		$title = sprintf($GLOBALS['TL_LANG']['tl_block']['module_name'], $dc->activeRecord->title);

		// frontend module doesn't exist, INSERT
		if($dc->activeRecord->module == 0)
		{
			$objModule = $this->Database->prepare('INSERT INTO tl_module (pid, tstamp, name, type, block) VALUES(?, ?, ?, ?, ?)')
			->execute($dc->activeRecord->pid, $dc->activeRecord->tstamp, $title, 'block', $dc->activeRecord->id);

			// set frontend module id for current block
			$this->Database->prepare('UPDATE tl_block SET module = ? WHERE id = ?')->execute($objModule->insertId, $dc->activeRecord->id);

			if($objModule->affectedRows <= 0)
			{
				$this->log('Block '.$title.' ID['.$this->activeRecord->id.'] \'s Frontend Module ID['.$dc->activeRecord->module.'] not created successfully.', 'tl_block::updateFEModule()', TL_ERROR);
			}

			return;
		}

		// frontend module exists, UPDATE
		$objModule = $this->Database->prepare('UPDATE tl_module SET tstamp = ?, name = ? WHERE id = ?')
								->execute($dc->activeRecord->tstamp, $title, $dc->activeRecord->module);

		if(!$objModule->isModified)
		{
			$this->log('Block '.$title.' ID['.$this->activeRecord->id.'] \'s Frontend Module ID['.$dc->activeRecord->module.'] not updated successfully.', 'tl_block::updateFEModule()', TL_ERROR);
		}

		return;
	}

	public function deleteFEModule(DataContainer $dc)
	{
		$title = sprintf($GLOBALS['TL_LANG']['tl_block']['module_name'], $dc->activeRecord->title);

		$objModule = $this->Database->prepare('DELETE FROM tl_module WHERE id = ?')->execute($dc->activeRecord->module);

		if($objModule->affectedRows > 0)
		{
			$this->log('Block '.$title.' ID['.$this->activeRecord->id.'] \'s Frontend Module ID['.$dc->activeRecord->module.'] not deleted successfully.', 'tl_block::deleteFEModule()', TL_ERROR);
		}
	}
}