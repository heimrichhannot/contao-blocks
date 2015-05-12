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

/**
 * Backend Modules
 */

$GLOBALS['BE_MOD']['design']['themes']['tables'][] = 'tl_block';
$GLOBALS['BE_MOD']['design']['themes']['tables'][] = 'tl_block_module';
$GLOBALS['BE_MOD']['design']['themes']['tables'][] = 'tl_content';

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_block'] = 'HeimrichHannot\Blocks\BlockModel';
$GLOBALS['TL_MODELS']['tl_block_module'] = 'HeimrichHannot\Blocks\BlockModuleModel';

/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD']['miscellaneous'], 0, array
(
	'block' => 'HeimrichHannot\Blocks\ModuleBlock'
));

$GLOBALS['BLOCKS']['CAROUSEL'] = array
(
	'default' => 'HeimrichHannot\Blocks\BlocksCarouselBootstrap'
);

/**
 * Content elements
 */
$GLOBALS['TL_CTE']['includes']['block'] = '\HeimrichHannot\Blocks\ContentBlock';

/**
 * Easy Themes Support
 */

$GLOBALS['TL_EASY_THEMES_MODULES'] = array_merge
(
	array
	(
		'blocks' => array
		(
			'href_fragment' => 'table=tl_block',
			'icon'          => 'system/modules/blocks/assets/icon.png',
		),
	),
	is_array($GLOBALS['TL_EASY_THEMES_MODULES']) ? $GLOBALS['TL_EASY_THEMES_MODULES'] : array()
);

/**
 * User JS
 */
if(TL_MODE == 'FE')
{
// 	$GLOBALS['TL_JAVASCRIPT']['jquery-mobile'] = 'system/modules/blocks/assets/js/jquery.mobile-1.4.2.js|static';
// 	$GLOBALS['TL_JAVASCRIPT']['blocks'] = '/system/modules/blocks/assets/js/jquery.blocks.js|static';
}
