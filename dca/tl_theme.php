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

array_insert($GLOBALS['TL_DCA']['tl_theme']['list']['operations'], 5 ,
	array('blocks' =>
		array
		(
			'label'               => &$GLOBALS['TL_LANG']['tl_theme']['blocks'],
			'href'                => 'table=tl_block',
			'icon'                => 'system/modules/blocks/html/block.png'
		)
	)
);


?>