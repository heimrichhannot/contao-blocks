<?php

namespace HeimrichHannot\Blocks\Backend;

use Contao\Backend;
use Contao\BackendUser;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Contao\ModuleModel;
use Contao\Versions;
use HeimrichHannot\Blocks\Model\BlockModel;

class BlockBackend extends Backend
{
    public function __construct()
    {
        parent::__construct();
        $this->import(BackendUser::class, 'User');
    }

    public function copyBlock($insertID, DataContainer $dc)
    {
        $objBlock         = BlockModel::findByPk($insertID);
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
        if (($objModule = ModuleModel::findByPk($objBlock->module)) === null) {
            $objModule        = new ModuleModel();
            $objModule->pid   = $objBlock->pid;
            $objModule->type  = 'block';
            $objModule->block = $objBlock->id;
        }

        // always update title and tstamp
        $objModule->name   = $strTitle;
        $objModule->tstamp = $objBlock->tstamp;
        $objModule->save();

        // set frontend module id for current block
        $objBlock         = BlockModel::findByPk($objBlock->id);
        $objBlock->module = $objModule->id;
        $objBlock->save();
    }

    public function updateFEModule(DataContainer $dc)
    {
        $objBlock = new BlockModel();
        $objBlock->setRow($dc->activeRecord->row());
        $this->createBlockModule($objBlock);
    }

    public function deleteFEModule(DataContainer $dc)
    {
        if (($objModule = ModuleModel::findByPk($dc->activeRecord->module)) !== null) {
            $objModule->delete();
        }
    }

    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        $user = BackendUser::getInstance();

        if (strlen(Input::get('tid'))) {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
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

        return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . \Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
    }

    public function toggleVisibility($intId, $blnVisible, \DataContainer $dc = null)
    {
        $user     = BackendUser::getInstance();
        $database = Database::getInstance();

        // Set the ID and action
        Input::setGet('id', $intId);
        Input::setGet('act', 'toggle');

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
            throw new AccessDeniedException('Not enough permissions to publish/unpublish quiz item ID ' . $intId . '.');
        }

        // Set the current record
        if ($dc) {
            $objRow = $database->prepare("SELECT * FROM tl_block WHERE id=?")->limit(1)->execute($intId);

            if ($objRow->numRows) {
                $dc->activeRecord = $objRow;
            }
        }

        $objVersions = new Versions('tl_block', $intId);
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

class_alias(BlockBackend::class, 'tl_block');
