<?php

namespace HeimrichHannot\Blocks\DataContainer;

use Contao\Controller;
use Contao\Database;
use Contao\DataContainer;
use Contao\Image;
use Contao\StringUtil;
use Contao\System;

class ModuleContainer
{
    public function getBlocks(DataContainer $dc): array
    {
        $blocks = [];

        $objBlocks = Database::getInstance()
            ->prepare('SELECT id, title FROM tl_block WHERE pid = ?')
            ->execute($dc->activeRecord->pid);

        while ($objBlocks->next()) {
            $blocks[$objBlocks->id] = $objBlocks->title;
        }

        return $blocks;
    }

    public function disableBlockModule($varValue, DataContainer $dc)
    {
        if (/* prevent changing block module */($dc->activeRecord->type === 'block' && !is_null($dc->activeRecord->block)) ||
            /* prevent block module creation */($dc->activeRecord->type !== 'block' && $varValue === 'block'))
        {
            return $dc->activeRecord->type;
        }

        return $varValue;
    }

    public function editBlockButtons($row, $href, $label, $title, $icon, $attributes): string
    {
        if ($row['type'] == 'block') {
            $html = '';

            System::loadLanguageFile('tl_block');

            if ($href == 'act=edit') {
                // edit button
                $html .= '<a href="'
                    . Controller::addToUrl('&table=tl_block_module&id=' . $row['block'])
                    . '" title="'
                    . StringUtil::specialchars(sprintf($GLOBALS['TL_LANG']['tl_block']['edit'][1], $row['block']))
                    . '"' . $attributes
                    . '>' . Image::getHtml($icon, $label) . '</a> ';

                // edit header button
                $icon = 'header.gif';
                $html .= '<a href="'
                    . Controller::addToUrl('&table=tl_block&act=edit&id=' . $row['block'])
                    . '" title="'
                    . StringUtil::specialchars(sprintf(($GLOBALS['TL_LANG']['tl_block']['editHeader'][1] ?? 'Edit block ID %s'), $row['block']))
                    . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ';
            }

            return $html;
        }

        return '<a href="'
            . Controller::addToUrl($href . '&amp;id=' . $row['id'])
            . '" title="' . StringUtil::specialchars($title)
            . '"' . $attributes
            . '>' . Image::getHtml($icon, $label) . '</a> ';
    }

    /**
     * List a front end module
     */
    public function listModule(array $row): string
    {
        if ($row['type'] == 'block') {
            Controller::loadLanguageFile('tl_block');

            $icon = '<a href="'
                . Controller::addToUrl('&table=tl_block_module&id=' . $row['block'])
                . '" title="'
                . StringUtil::specialchars($GLOBALS['TL_LANG']['tl_block']['show'][0])
                . '">'
                . Image::getHtml(
                    '/bundles/heimrichhannotblocks/assets/icon.png',
                    $GLOBALS['TL_LANG']['MOD']['blocks'],
                    'style="vertical-align: -4px;"'
                ) . '</a> ';

            return '<div style="float:left">'
                . $icon . $row['name']
                . ' <span style="color:#b3b3b3;padding-left:3px">['
                . ($GLOBALS['TL_LANG']['FMD'][$row['type']][0] ?? $row['type'])
                . "]</span></div>\n";
        }

        $intMarginLeft = 20;

        return "<div style=\"margin-left: {$intMarginLeft}px; float:left\">" . $row['name']
            . ' <span style="color:#b3b3b3;padding-left:3px">['
            . ($GLOBALS['TL_LANG']['FMD'][$row['type']][0] ?? $row['type'])
            . "]</span></div>\n";
    }
}