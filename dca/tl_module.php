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

$dc = &$GLOBALS['TL_DCA']['tl_module'];

$dc['palettes']['block'] = '{title_legend},headline,type;{block_legend},block';

$dc['fields']['block'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['block'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_block', 'getBlocks'),
	'eval'                    => array('tl_class'=>'w50', 'mandatory' => true, 'readonly' => true),
	'sql'											=> "int(10) unsigned NOT NULL default '0'"
);

$dc['fields']['type']['save_callback'] = array(array('tl_module_block', 'disableBlockModule'));

foreach($dc['list']['operations'] as $key => $button)
{
	if(in_array($key, array('edit', 'copy', 'cut', 'delete')))
	{
		$dc['list']['operations'][$key]['button_callback'] = array('tl_module_block', 'editBlockButtons');
	}
}


$dc['config']['onload_callback'][]= array('tl_module_block', 'checkBlockPermission');
$dc['config']['onload_callback'][]= array('tl_module_block', 'cleanup');

/**
 * Breadcrumb tweaks for auto_item
 */
$dc['palettes']['breadcrumb'] = str_replace('showHidden;', 'showHidden;{block_legend},hideAutoItem;', $dc['palettes']['breadcrumb']);

$dc['fields']['hideAutoItem'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hideAutoItem'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);

class tl_module_block extends \Backend
{
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * tl_module blocks can not exist without tl_block items
	 * @param DataContainer $dc
	 */
	public function cleanup(DataContainer $dc)
	{
		$objModules = \Database::getInstance()->prepare('SELECT m.id FROM tl_module m LEFT JOIN tl_block b ON b.module = m.id WHERE m.block > 0 AND m.type = ? and b.id IS NULL')->execute('block');

		if($objModules->numRows < 1)
		{
			return;
		}

		\Database::getInstance()->prepare('DELETE FROM tl_module WHERE id IN(' .implode(",", $objModules->fetchEach('id')) . ')')->execute();
	}

	public function checkBlockPermission()
	{
		// Check current action
		if($this->Input->get('act'))
		{
			// single actions
			if(in_array($this->Input->get('act'), array('edit', 'copy', 'cut', 'delete')))
			{
				$objModule = $this->Database->prepare("SELECT id FROM tl_module WHERE id = ? and type='block'")->execute($this->Input->get('id'));

				if($objModule->numRows)
				{
					$this->log('Action "'.$this->Input->get('act').'" not allowed for modules of type "block"'. $this->Input->get('pid') .' (root level)', 'tl_module_block checkBlockPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
			}

			// batch actions
			if(in_array($this->Input->get('act'), array('editAll', 'copyAll', 'deleteAll', 'cutAll', 'showAll')))
			{
				$session = $this->Session->getData();

				$ids = $session['CURRENT']['IDS'];

				if(is_array($ids) && count($ids) > 0)
				{
					$objModules = $this->Database->prepare("SELECT * FROM tl_module WHERE id IN (".implode(',', $ids).") and type='block'")->execute($this->Input->get('id'));

					while($objModules->next())
					{
						$index = array_search($objModules->id, $ids);
						unset($ids[$index]);
					}

					$session['CURRENT']['IDS'] = $ids;

					$this->Session->setData($session);
				}
			}
		}
	}

	public function getBlocks(DataContainer $dc)
	{
		$blocks = array();

		$objBlocks = $this->Database->prepare('SELECT id, title FROM tl_block WHERE pid = ?')->execute($dc->activeRecord->pid);

		while($objBlocks->next())
		{
			$blocks[$objBlocks->id] = $objBlocks->title;
		}

		return $blocks;
	}

	public function disableBlockModule($varValue, DataContainer $dc)
	{
		// prevent changing block module
		if($dc->activeRecord->type == 'block' && !is_null($dc->activeRecord->block))
		{
			return $dc->activeRecord->type;
		}
		// prevent block module creation
		else if($dc->activeRecord->type != 'block' && $varValue == 'block')
		{
			return $dc->activeRecord->type;
		}
		return $varValue;
	}

	public function editBlockButtons($row, $href, $label, $title, $icon, $attributes)
	{
		$this->loadLanguageFile('tl_block');
		if($row['type'] == 'block')
		{
			if($href == 'act=edit')
			{
				return '<a href="'.$this->addToUrl('&table=tl_block&act=edit&id='.$row['block']).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG']['tl_block']['edit'][1],$row['block'])).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
			}
			return '';
		}
		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}
}