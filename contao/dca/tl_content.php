<?php

use Contao\Input;
use HeimrichHannot\Blocks\Controller\Backend\Content;

$arrDca = &$GLOBALS['TL_DCA']['tl_content'];

/**
 * Config
 */

$arrDca['config']['onload_callback'][] = [Content::class, 'onLoadCallback'];

/**
 * Palettes
 */
$arrDca['palettes']['block'] = '{type_legend},type;{include_legend},block;{protected_legend:collapsed},protected;{expert_legend:collapsed},guests,cssID,space;{invisible_legend:collapsed},invisible,start,stop';

/**
 * Fields
 */
$arrFields = [
    'block' => [
        'label'            => &$GLOBALS['TL_LANG']['tl_content']['block'],
        'exclude'          => true,
        'inputType'        => 'select',
        'options_callback' => [Content::class, 'getBlocks'],
        'eval'             => ['mandatory' => true, 'chosen' => true, 'submitOnChange' => true],
        'wizard'           => [[Content::class, 'editBlock']],
        'sql'              => "int(10) unsigned NOT NULL default '0'",
    ],
];

$arrDca['fields'] = array_merge($arrDca['fields'], $arrFields);


/**
 * Dynamically add the permission check and parent table
 */
if (Input::get('do') == 'themes') {
    $arrDca['config']['ptable']            = 'tl_block_module';
    $arrDca['config']['onload_callback'][] = [Content::class, 'checkPermission'];
}