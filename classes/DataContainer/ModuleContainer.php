<?php

namespace HeimrichHannot\Blocks\DataContainer;

use Contao\DataContainer;
use Contao\Input;
use Contao\ModuleModel;
use HeimrichHannot\Blocks\ModuleBlock;

class ModuleContainer
{
    public function onLoadCallback(DataContainer $dc = null): void
    {
        if (null === $dc || !$dc->id || 'edit' !== Input::get('act') || 'themes' !== Input::get('do')) {
            return;
        }

        $module = ModuleModel::findByPk($dc->id);

        if (null === $module || ModuleBlock::TYPE === $module->type) {
            return;
        }

        unset($GLOBALS['FE_MOD']['miscellaneous'][ModuleBlock::TYPE]);
    }
}