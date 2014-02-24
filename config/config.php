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

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_block'] = 'BlockModel';
$GLOBALS['TL_MODELS']['tl_block_module'] = 'BlockModuleModel';

/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD']['miscellaneous'], 0, array
(
	'block' => 'ModuleBlock'
));