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

$GLOBALS['TL_DCA']['tl_block'] = [

    // Config
    'config'      => [
        'dataContainer'     => 'Table',
        'ptable'            => 'tl_theme',
        'ctable'            => ['tl_block_module'],
        'enableVersioning'  => true,
        'onsubmit_callback' => [
            ['tl_block', 'updateFEModule'],
        ],
        'oncopy_callback'   => [
            ['tl_block', 'copyBlock'],
        ],
        'ondelete_callback' => [
            ['tl_block', 'deleteFEModule'],
        ],
        'sql'               => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    'list'        => [
        'sorting'           => [
            'mode'        => 2,
            'fields'      => ['title'],
            'flag'        => 1,
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
            'edit'       => [
                'label' => &$GLOBALS['TL_LANG']['tl_block']['edit'],
                'href'  => 'table=tl_block_module',
                'icon'  => 'edit.gif',
            ],
            'editHeader' => [
                'label' => &$GLOBALS['TL_LANG']['tl_block']['editHeader'],
                'href'  => 'act=edit',
                'icon'  => 'header.gif',
            ],
            'copy'       => [
                'label' => &$GLOBALS['TL_LANG']['tl_block']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif',
            ],
            'delete'     => [
                'label'      => &$GLOBALS['TL_LANG']['tl_block']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
            ],
            'show'       => [
                'label' => &$GLOBALS['TL_LANG']['tl_block']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif',
            ],
        ],
    ],
    // Palettes
    'palettes'    => [
        '__selector__' => ['addWrapper'],
        'default'      => '{title_legend},title;{expert_legend:hide},addWrapper',
    ],
    'subpalettes' => [
        'addWrapper' => 'cssID',
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
            'flag'      => 1,
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
    ],
];

class tl_block extends \Backend
{
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function copyBlock($insertID, DataContainer $dc)
    {
        $objBlock         = \HeimrichHannot\Blocks\BlockModel::findByPk($insertID);
        $objBlock->module = 0;

        if ($objBlock === null) {
            return;
        }

        $this->createBlockModule($objBlock);

    }

    public function createBlockModule($objBlock)
    {
        $strTitle = $objBlock->title;

        // create new module, if non existing yet
        if (($objModule = \ModuleModel::findByPk($objBlock->module)) === null) {
            $objModule        = new \ModuleModel();
            $objModule->pid   = $objBlock->pid;
            $objModule->type  = 'block';
            $objModule->block = $objBlock->id;
        }

        // always update title and tstamp
        $objModule->name   = $strTitle;
        $objModule->tstamp = $objBlock->tstamp;
        $objModule->save();

        // set frontend module id for current block
        $objBlock         = \HeimrichHannot\Blocks\BlockModel::findByPk($objBlock->id);
        $objBlock->module = $objModule->id;
        $objBlock->save();
    }

    public function updateFEModule(DataContainer $dc)
    {
        $objBlock = new \HeimrichHannot\Blocks\BlockModel();
        $objBlock->setRow($dc->activeRecord->row());
        $this->createBlockModule($objBlock);
    }

    public function deleteFEModule(DataContainer $dc)
    {
        if (($objModule = \ModuleModel::findByPk($dc->activeRecord->module)) !== null) {
            $objModule->delete();
        }
    }
}