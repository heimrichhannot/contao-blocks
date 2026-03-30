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
use Contao\DataContainer;
use Contao\DC_Table;
use HeimrichHannot\Blocks\DataContainer\BlockContainer;

$GLOBALS['TL_DCA']['tl_block'] = [
    // Config
    'config'      => [
        'dataContainer'     => DC_Table::class,
        'ptable'            => 'tl_theme',
        'ctable'            => ['tl_block_module'],
        'enableVersioning'  => true,
        'onsubmit_callback' => [[BlockContainer::class, 'updateFEModule']],
        'oncopy_callback'   => [[BlockContainer::class, 'copyBlock']],
        'ondelete_callback' => [[BlockContainer::class, 'deleteFEModule']],
        'sql'               => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    'list'        => [
        'sorting'           => [
            'mode'        => DataContainer::MODE_SORTABLE,
            'fields'      => ['title'],
            'flag'        => DataContainer::SORT_INITIAL_LETTER_ASC,
            'panelLayout' => 'sort,search,limit',
        ],
        'label'             => [
            'fields' => ['title'],
            'format' => '%s',
        ],
        'global_operations' => [
            'all' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"',
            ],
        ],
        'operations'        => [
            'edit',
            'children',
            'copy',
            'delete',
            'toggle',
            'show',
        ],
    ],
    // Palettes
    'palettes'    => [
        '__selector__' => ['addWrapper', 'published'],
        'default'      => '{title_legend},title;{expert_legend:collapsed},addWrapper,published',
    ],
    'subpalettes' => [
        'addWrapper' => 'cssID',
        'published'  => 'start,stop',
    ],
    'fields'      => [
        'id'         => [
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ],
        'pid'        => [
            'foreignKey' => 'tl_theme.name',
            'sql'        => "int(10) unsigned NOT NULL default '0'",
            'relation'   => ['type' => 'belongsTo', 'load' => 'eager'],
        ],
        'tstamp'     => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'module'     => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title'      => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block']['title'],
            'inputType' => 'text',
            'sorting'   => true,
            'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
            'search'    => true,
            'eval'      => ['mandatory' => true, 'maxlength' => 128, 'tl_class' => 'w50'],
            'sql'       => "varchar(128) NOT NULL default ''",
        ],
        'addWrapper' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block']['addWrapper'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['tl_class' => 'clr', 'submitOnChange' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'cssID'      => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block']['cssID'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['multiple' => true, 'maxlength' => 255, 'size' => 2, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'published'  => [
            'exclude'   => true,
            'filter'    => true,
            'toggle'    => true,
            'inputType' => 'checkbox',
            'eval'      => ['doNotCopy' => true, 'submitOnChange' => true],
            'sql'       => "char(1) NOT NULL default '1'",
        ],
        'start'      => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block']['start'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
        'stop'       => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block']['stop'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
    ],
];
