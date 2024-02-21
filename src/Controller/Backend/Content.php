<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @author  Dennis Patzer
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\Blocks\Controller\Backend;

use Contao\Backend;
use Contao\BackendUser;
use Contao\Database;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use Contao\StringUtil;
use Contao\System;

class Content extends Backend
{
    public function onLoadCallback(?DataContainer $dc = null): void
    {
        if (null === $dc || !$dc->id || 'edit' !== Input::get('act')) {
            return;
        }

        $objModule = Database::getInstance()
            ->prepare("SELECT * FROM tl_module WHERE id = ? AND type = 'block'")
            ->execute($dc->value);

        if ($objModule->numRows) {
            $GLOBALS['TL_DCA']['tl_content']['fields']['module']['wizard'] = [
                [Content::class, 'editModule'],
            ];
        }
    }

    public function editBlock(DataContainer $dc): string
    {
        $requestToken = System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue();

        if ($dc->activeRecord->block < 1) {
            return '';
        }

        return sprintf('<a href="contao?do=themes&amp;table=tl_content&amp;id=%s&amp;popup=1&amp;nb=1&amp;rt=%s" title="%s" style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\'%s\',\'url\':this.href});return false">%s</a>',
            $dc->activeRecord->block,
            $requestToken,
            sprintf(StringUtil::specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $dc->activeRecord->block),
            StringUtil::specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_content']['editalias'][1], $dc->activeRecord->block))),
            Image::getHtml('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top"')
        );
    }

    public function getBlocks(): array
    {
        $arrBlocks = [];
        $objBlocks = Database::getInstance()
            ->prepare("SELECT b.title as block, bm.id, bm.title, t.name AS theme FROM tl_block_module bm LEFT JOIN tl_block b on b.id = bm.pid LEFT JOIN tl_theme t ON b.pid=t.id WHERE type=? ORDER BY b.title, bm.title")
            ->execute('content');

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
    public function checkPermission(): void
    {
        if (BackendUser::getInstance()->isAdmin) {
            return;
        }

        // TODO
    }

    public function editModule(DataContainer $objDc): string
    {
        if ($objDc->value < 1) {
            return '';
        }

        $objModule = Database::getInstance()
            ->prepare("SELECT * FROM tl_module WHERE id = ? AND type = 'block'")
            ->execute($objDc->value);

        $requestToken = System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue();

        if ($objModule->numRows) {
            System::loadLanguageFile('tl_block');

            return sprintf(' <a href="contao?do=themes&amp;table=tl_block_module&amp;act=edit&amp;id=%s&amp;popup=1&amp;nb=1&amp;rt=%s" title="%s" style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\'%s\',\'url\':this.href});return false">%s</a>',
                $objDc->block,
                $requestToken,
                sprintf(StringUtil::specialchars($GLOBALS['TL_LANG']['tl_block']['edit'][1]), $objDc->block),
                StringUtil::specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_block']['edit'][1], $objDc->block))),
                Image::getHtml('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top"')
            );
        }

        if ($objDc->value < 1) {
            return '';
        }

        return sprintf(' <a href="contao?do=themes&amp;table=tl_module&amp;act=edit&amp;id=%s&amp;popup=1&amp;nb=1&amp;rt=%s" title="%s" style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\'%s\',\'url\':this.href});return false">%s</a>',
            $objDc->value,
            $requestToken,
            sprintf(StringUtil::specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $objDc->value),
            StringUtil::specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_content']['editalias'][1], $objDc->value))),
            Image::getHtml('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top"')
        );
    }
}