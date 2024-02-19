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

use HeimrichHannot\Blocks\Backend\ModuleBackend;
use HeimrichHannot\Blocks\DataContainer\ModuleContainer;
use HeimrichHannot\Blocks\Module\BlockModule;

$dc = &$GLOBALS['TL_DCA']['tl_module'];

$dc['config']['onload_callback'][] = [ModuleBackend::class, 'checkBlockPermission'];
$dc['config']['onload_callback'][] = [ModuleBackend::class, 'cleanup'];
$dc['config']['onload_callback'][] = [ModuleContainer::class, 'onLoadCallback'];

$dc['list']['sorting']['child_record_callback'] = [ModuleBackend::class, 'listModule'];

foreach ($dc['list']['operations'] ?? [] as $key => $button) {
    if (in_array($key, ['edit', 'copy', 'cut', 'delete'])) {
        $dc['list']['operations'][$key]['button_callback'] = [ModuleBackend::class, 'editBlockButtons'];
    }
}

$dc['palettes'][BlockModule::TYPE] = '{title_legend},headline,type;{block_legend},block';

$dc['fields']['block'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_module']['block'],
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => [ModuleBackend::class, 'getBlocks'],
    'eval'             => ['tl_class' => 'w50', 'mandatory' => true, 'readonly' => true],
    'sql'              => "int(10) unsigned NOT NULL default '0'",
];

$dc['fields']['type']['save_callback'] = [[ModuleBackend::class, 'disableBlockModule']];

/**
 * Breadcrumb tweaks for auto_item
 */
$dc['palettes']['breadcrumb'] = str_replace('showHidden;', 'showHidden;{block_legend},hideAutoItem;', $dc['palettes']['breadcrumb']);

$dc['fields']['hideAutoItem'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_module']['hideAutoItem'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['tl_class' => 'w50'],
    'sql'       => "char(1) NOT NULL default ''",
];
