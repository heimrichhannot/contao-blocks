<?php

namespace HeimrichHannot\Blocks\DataContainer;

use Contao\Backend;
use Contao\BackendUser;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\CoreBundle\Exception\RedirectResponseException;
use Contao\Database;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use Contao\ModuleModel;
use Contao\StringUtil;
use Contao\System;
use Contao\Versions;
use HeimrichHannot\Blocks\Model\BlockModel;

/**
 * Class BlockContainer
 *
 * Ported from Backend tl_block
 *
 * @package HeimrichHannot\Blocks\DataContainer
 */
class BlockContainer
{
    public function __construct() {}

    public function updateFEModule(DataContainer $dc): void
    {
        $objBlock = new BlockModel();
        $objBlock->setRow((array)$dc->activeRecord);
        $this->createBlockModule($objBlock);
    }

    public function copyBlock($insertID, DataContainer $dc): void
    {
        $block = BlockModel::findByPk($insertID);
        $block->module = 0;

        if ($block === null) {
            return;
        }

        $this->createBlockModule($block);
    }

    public function deleteFEModule(DataContainer $dc): void
    {
        $module = ModuleModel::findByPk($dc->activeRecord->module);
        $module?->delete();
    }
    
    public function createBlockModule(BlockModel $block): void
    {
        $title = $block->title;

        // create new module, if not yet existent
        $module = ModuleModel::findByPk($block->module);
        if ($module === null) {
            $module = new ModuleModel();
            $module->pid = $block->pid;
            $module->type = 'block';
            $module->block = $block->id;
        }

        // always update title and tstamp
        $module->name   = $title;
        $module->tstamp = $block->tstamp;
        $module->save();

        // set frontend module id for current block
        $block = BlockModel::findByPk($block->id);
        $block->module = $module->id;
        $block->save();
    }
}