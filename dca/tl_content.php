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

$GLOBALS['TL_DCA']['tl_content']['fields']['module']['wizard'] = array(
	array('tl_content_block', 'editModule')
);

class tl_content_block extends Backend
{
	public function __construct()
	{
		parent::__construct();
	}

	public function editModule(DataContainer $dc)
	{
		if($dc->value < 1)
		{
			return '';
		}

		$objModule = $this->Database->prepare("SELECT * FROM tl_module WHERE id = ? AND type = 'block'")->execute($dc->value);

		if($objModule->numRows)
		{
			$this->loadLanguageFile('tl_block');

			return ' <a href="contao/main.php?do=themes&amp;table=tl_block_module&amp;id=' . $objModule->block . '" title="'.sprintf(specialchars($GLOBALS['TL_LANG']['tl_block']['edit'][1]), $objModule->block).'" style="padding-left:3px">' . $this->generateImage('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top"') . '</a>';
		}

		return ' <a href="contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $dc->value . '" title="'.sprintf(specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $dc->value).'" style="padding-left:3px">' . $this->generateImage('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top"') . '</a>';
	}
}