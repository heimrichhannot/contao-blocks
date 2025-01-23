<?php

/**
 * @copyright Heimrich & Hannot GmbH, 2025
 * @license LGPL-3.0-or-later
 */

use HeimrichHannot\Blocks\ContentElement\ContentBlock;
use HeimrichHannot\Blocks\Model\BlockModel;
use HeimrichHannot\Blocks\Model\BlockModuleModel;
use HeimrichHannot\Blocks\Module\BlockModule;

/**
 * Backend Modules
 */
$GLOBALS['BE_MOD']['design']['themes']['tables'][] = 'tl_block';
$GLOBALS['BE_MOD']['design']['themes']['tables'][] = 'tl_block_module';
$GLOBALS['BE_MOD']['design']['themes']['tables'][] = 'tl_content';

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_block']        = BlockModel::class;
$GLOBALS['TL_MODELS']['tl_block_module'] = BlockModuleModel::class;

/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['miscellaneous'][BlockModule::TYPE] = BlockModule::class;

/**
 * Content elements
 */
$GLOBALS['TL_CTE']['includes']['block'] = ContentBlock::class;

/**
 * Easy Themes Support
 */
$GLOBALS['TL_EASY_THEMES_MODULES'] = array_merge(
    [
        'blocks' => [
            'href_fragment' => 'table=tl_block',
            'icon'          => 'bundles/heimrichhannotblocks/assets/icon.png',
        ],
    ],
    is_array($GLOBALS['TL_EASY_THEMES_MODULES'] ?? null) ? $GLOBALS['TL_EASY_THEMES_MODULES'] : []
);
