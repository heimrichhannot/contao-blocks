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

use HeimrichHannot\Blocks\ContentElement\ContentBlock;
use HeimrichHannot\Blocks\Controller\Hooks;
use HeimrichHannot\Blocks\EventListener\InsertTagsListener;
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
array_splice($GLOBALS['FE_MOD']['miscellaneous'], 0, 0, [BlockModule::TYPE => BlockModule::class]);

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

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['generateBreadcrumb']['huh.blocks'] = [Hooks::class, 'generateBreadcrumbHook'];
$GLOBALS['TL_HOOKS']['replaceInsertTags']['huh.blocks'] = [InsertTagsListener::class, 'onReplaceInsertTags'];
