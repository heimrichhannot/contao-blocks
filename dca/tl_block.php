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
            'toggle'     => [
                'label'           => &$GLOBALS['TL_LANG']['tl_block']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => ['tl_block', 'toggleIcon'],
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
        '__selector__' => ['addWrapper', 'published'],
        'default'      => '{title_legend},title;{expert_legend:hide},addWrapper,published',
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
        'published'  => [
            'label'     => &$GLOBALS['TL_LANG']['tl_block']['published'],
            'exclude'   => true,
            'filter'    => true,
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

    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        $user = \Contao\BackendUser::getInstance();

        if (strlen(\Contao\Input::get('tid'))) {
            $this->toggleVisibility(\Contao\Input::get('tid'), (\Contao\Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$user->hasAccess('tl_block::published', 'alexf')) {
            return '';
        }

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

        if (!$row['published']) {
            $icon = 'invisible.svg';
        }

        return '<a href="' . $this->addToUrl($href) . '" title="' . \StringUtil::specialchars($title) . '"' . $attributes . '>' . \Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
    }

    public function toggleVisibility($intId, $blnVisible, \DataContainer $dc = null)
    {
        $user     = \Contao\BackendUser::getInstance();
        $database = \Contao\Database::getInstance();

        // Set the ID and action
        \Contao\Input::setGet('id', $intId);
        \Contao\Input::setGet('act', 'toggle');

        if ($dc) {
            $dc->id = $intId; // see #8043
        }

        // Trigger the onload_callback
        if (is_array($GLOBALS['TL_DCA']['tl_block']['config']['onload_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_block']['config']['onload_callback'] as $callback) {
                if (is_array($callback)) {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                } elseif (is_callable($callback)) {
                    $callback($dc);
                }
            }
        }

        // Check the field access
        if (!$user->hasAccess('tl_block::published', 'alexf')) {
            throw new \Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to publish/unpublish quiz item ID ' . $intId . '.');
        }

        // Set the current record
        if ($dc) {
            $objRow = $database->prepare("SELECT * FROM tl_block WHERE id=?")->limit(1)->execute($intId);

            if ($objRow->numRows) {
                $dc->activeRecord = $objRow;
            }
        }

        $objVersions = new \Versions('tl_block', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_block']['fields']['published']['save_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_block']['fields']['published']['save_callback'] as $callback) {
                if (is_array($callback)) {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
                } elseif (is_callable($callback)) {
                    $blnVisible = $callback($blnVisible, $dc);
                }
            }
        }

        $time = time();

        // Update the database
        $database->prepare("UPDATE tl_block SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")->execute($intId);

        if ($dc) {
            $dc->activeRecord->tstamp    = $time;
            $dc->activeRecord->published = ($blnVisible ? '1' : '');
        }

        // Trigger the onsubmit_callback
        if (is_array($GLOBALS['TL_DCA']['tl_block']['config']['onsubmit_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_block']['config']['onsubmit_callback'] as $callback) {
                if (is_array($callback)) {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                } elseif (is_callable($callback)) {
                    $callback($dc);
                }
            }
        }

        $objVersions->create();
    }
}
