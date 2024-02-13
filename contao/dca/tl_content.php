<?php

use Contao\Input;

$arrDca = &$GLOBALS['TL_DCA']['tl_content'];

/**
 * Config
 */

$arrDca['config']['onload_callback'][] = ['HeimrichHannot\Blocks\Backend\Content', 'onLoadCallback'];

/**
 * Palettes
 */

$arrDca['palettes']['block'] =
    '{type_legend},type;{include_legend},block;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';

/**
 * Fields
 */
$arrFields = [
    'block' => [
        'label'            => &$GLOBALS['TL_LANG']['tl_content']['block'],
        'exclude'          => true,
        'inputType'        => 'select',
        'options_callback' => ['HeimrichHannot\Blocks\Backend\Content', 'getBlocks'],
        'eval'             => ['mandatory' => true, 'chosen' => true, 'submitOnChange' => true],
        'wizard'           => [
            ['HeimrichHannot\Blocks\Backend\Content', 'editBlock'],
        ],
        'sql'              => "int(10) unsigned NOT NULL default '0'",
    ],
];

$arrDca['fields'] = array_merge($arrDca['fields'], $arrFields);


/**
 * Dynamically add the permission check and parent table
 */
if (Input::get('do') == 'themes') {
    $arrDca['config']['ptable']            = 'tl_block_module';
    $arrDca['config']['onload_callback'][] = ['HeimrichHannot\Blocks\Backend\Content', 'checkPermission'];
}