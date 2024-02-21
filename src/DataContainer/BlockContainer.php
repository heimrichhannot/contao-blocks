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

    public function toggleIcon($row, $href, $label, $title, $icon, $attributes): string
    {
        $user = BackendUser::getInstance();

        if (strlen(Input::get('tid'))) {
            $this->toggleVisibility(
                (int)Input::get('tid'),
                Input::get('state') == 1,
                @func_get_arg(12) ?: null
            );
            throw new RedirectResponseException(System::getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$user->hasAccess('tl_block::published', 'alexf')) {
            return '';
        }

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

        if (!$row['published']) {
            $icon = 'invisible.svg';
        }

        $dataState = $row['published'] ? 1 : 0;

        return sprintf(
            '<a href="%s" title="%s"%s>%s</a> ',
            Backend::addToUrl($href),
            StringUtil::specialchars($title),
            $attributes ?? '',
            Image::getHtml($icon, $label, "data-state=\"$dataState\"")
        );
    }

    public function toggleVisibility(int $intId, bool $blnVisible, DataContainer $dc = null): void
    {
        $user = BackendUser::getInstance();
        $database = Database::getInstance();

        // Set the ID and action
        Input::setGet('id', $intId);
        Input::setGet('act', 'toggle');

        if ($dc) {
            $dc->id = $intId; // see #8043
        }

        // Trigger the onload_callback
        $onloadCallbacks = $GLOBALS['TL_DCA']['tl_block']['config']['onload_callback'];
        if (is_array($onloadCallbacks)) {
            foreach ($onloadCallbacks as $callback) {
                if (is_array($callback)) {
                    System::importStatic($callback[0]);
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
            $objRow = $database
                ->prepare("SELECT * FROM tl_block WHERE id=?")
                ->limit(1)
                ->execute($intId);

            if ($objRow->numRows) {
                $dc->activeRecord = $objRow;
            }
        }

        $objVersions = new Versions('tl_block', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        $saveCallbacks = $GLOBALS['TL_DCA']['tl_block']['fields']['published']['save_callback'];
        if (is_array($saveCallbacks)) {
            foreach ($saveCallbacks as $callback) {
                if (is_array($callback)) {
                    System::importStatic($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
                } elseif (is_callable($callback)) {
                    $blnVisible = $callback($blnVisible, $dc);
                }
            }
        }

        $time = time();

        // Update the database
        $database
            ->prepare("UPDATE tl_block SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
            ->execute($intId);

        if ($dc) {
            $dc->activeRecord->tstamp    = $time;
            $dc->activeRecord->published = $blnVisible ? '1' : '';
        }

        // Trigger the onsubmit_callback
        $onsubmitCallbacks = $GLOBALS['TL_DCA']['tl_block']['config']['onsubmit_callback'];
        if (is_array($onsubmitCallbacks)) {
            foreach ($onsubmitCallbacks as $callback) {
                if (is_array($callback)) {
                    System::importStatic($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                } elseif (is_callable($callback)) {
                    $callback($dc);
                }
            }
        }

        $objVersions->create();
    }
}