<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @author  Dennis Patzer
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\Blocks\Backend;


use Contao\ContentModel;
use Contao\DataContainer;
use Contao\Input;

class Content extends \Backend
{
    /**
     * @param DataContainer|null $dc
     * @return void
     */
    public function onLoadCallback($dc = null)
    {
        if (null === $dc || !$dc->id || 'edit' !== Input::get('act')) {
            return;
        }

        $objModule = \Database::getInstance()->prepare("SELECT * FROM tl_module WHERE id = ? AND type = 'block'")->execute($dc->value);
        if ($objModule->numRows) {
            $GLOBALS['TL_DCA']['tl_content']['fields']['module']['wizard'] = [
                ['HeimrichHannot\Blocks\Backend\Content', 'editModule'],
            ];
        }
    }

    public function editBlock(\DataContainer $dc)
    {
        return ($dc->activeRecord->block < 1) ? '' : ' <a href="contao/main.php?do=themes&amp;table=tl_content&amp;id=' . $dc->activeRecord->block . '&amp;popup=1&amp;nb=1&amp;rt=' . REQUEST_TOKEN . '" title="' . sprintf(specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]),
                $dc->activeRecord->block) . '" style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\'' . specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_content']['editalias'][1], $dc->activeRecord->block))) . '\',\'url\':this.href});return false">' . \Image::getHtml('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0],
                'style="vertical-align:top"') . '</a>';
    }

    public function getBlocks()
    {
        $arrBlocks = [];
        $objBlocks = \Database::getInstance()->prepare("SELECT b.title as block, bm.id, bm.title, t.name AS theme FROM tl_block_module bm LEFT JOIN tl_block b on b.id = bm.pid LEFT JOIN tl_theme t ON b.pid=t.id WHERE type=? ORDER BY b.title, bm.title")->execute('content');

        if ($objBlocks->numRows < 1) {
            return $arrBlocks;
        }

        while ($objBlocks->next()) {
            $arrBlocks[$objBlocks->theme . ' &raquo; ' . $objBlocks->block][$objBlocks->id] = $objBlocks->title . ' (ID ' . $objBlocks->id . ')';
        }

        return $arrBlocks;
    }

    /**
     * Check permissions to edit table tl_content
     */
    public function checkPermission()
    {
        if (\BackendUser::getInstance()->isAdmin) {
            return;
        }

        // TODO
    }

    public function editModule(\DataContainer $objDc)
    {
        if ($objDc->value < 1) {
            return '';
        }

        $objModule = \Database::getInstance()->prepare("SELECT * FROM tl_module WHERE id = ? AND type = 'block'")->execute($objDc->value);

        if ($objModule->numRows) {
            \System::loadLanguageFile('tl_block');

            return ' <a href="contao/main.php?do=themes&amp;table=tl_block_module&amp;id=' . $objModule->block . '&amp;popup=1&amp;nb=1&amp;rt=' . REQUEST_TOKEN . '" title="' . sprintf(specialchars($GLOBALS['TL_LANG']['tl_block']['edit'][1]), $objModule->block) . '" style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\'' . specialchars(str_replace("'", "\\'",
                    sprintf($GLOBALS['TL_LANG']['tl_block']['edit'][1], $objModule->block))) . '\',\'url\':this.href});return false">' . \Image::getHtml('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top"') . '</a>';
        }

        return ($objDc->value < 1) ? '' : ' <a href="contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $objDc->value . '&amp;popup=1&amp;nb=1&amp;rt=' . REQUEST_TOKEN . '" title="' . sprintf(specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]),
                $objDc->value) . '" style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\'' . specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_content']['editalias'][1], $objDc->value))) . '\',\'url\':this.href});return false">' . \Image::getHtml('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0],
                'style="vertical-align:top"') . '</a>';
    }
}