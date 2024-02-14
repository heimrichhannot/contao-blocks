<?php

namespace HeimrichHannot\Blocks\Backend;

use Contao\Controller;
use Contao\Database;
use Contao\DataContainer;
use Contao\Image;
use Contao\StringUtil;
use tl_module;

class ModuleBackend extends tl_module
{
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * tl_module blocks can not exist without tl_block items
     *
     * @param DataContainer $dc
     */
    public function cleanup(DataContainer $dc)
    {
        $objModules = \Database::getInstance()->prepare(
            'SELECT m.id FROM tl_module m LEFT JOIN tl_block b ON b.module = m.id WHERE m.block > 0 AND m.type = ? and b.id IS NULL'
        )->execute('block');

        if ($objModules->numRows < 1) {
            return;
        }

        Database::getInstance()->prepare('DELETE FROM tl_module WHERE id IN(' . implode(",", $objModules->fetchEach('id')) . ')')->execute();
    }

    public function checkBlockPermission()
    {
        // Check current action
        $act = $this->Input->get('act');
        if (!$act) {
            return;
        }

        // single actions
        if (in_array($act, ['edit', 'copy', 'cut', 'delete'])) {
            $objModule = $this->Database->prepare("SELECT block FROM tl_module WHERE id = ? and type='block'")->execute($this->Input->get('id'));

            if ($objModule->numRows) {
                $this->redirect(
                    'contao/main.php?do=themes&amp;table=tl_block_module&amp;id=' . $objModule->block . '&amp;popup=1&amp;nb=1&amp;rt=' . REQUEST_TOKEN
                );
            }

            return;
        }

        // batch actions
        if (in_array($act, ['editAll', 'copyAll', 'deleteAll', 'cutAll', 'showAll']))
        {
            $session = $this->Session->getData();

            $ids = $session['CURRENT']['IDS'];

            if (is_array($ids) && count($ids) > 0) {
                $objModules = $this->Database->prepare("SELECT * FROM tl_module WHERE id IN (" . implode(',', $ids) . ") and type='block'")->execute(
                    $this->Input->get('id')
                );

                while ($objModules->next()) {
                    $index = array_search($objModules->id, $ids);
                    unset($ids[$index]);
                }

                $session['CURRENT']['IDS'] = $ids;

                $this->Session->setData($session);
            }
        }
    }

    public function getBlocks(DataContainer $dc)
    {
        $blocks = [];

        $objBlocks = $this->Database->prepare('SELECT id, title FROM tl_block WHERE pid = ?')->execute($dc->activeRecord->pid);

        while ($objBlocks->next()) {
            $blocks[$objBlocks->id] = $objBlocks->title;
        }

        return $blocks;
    }

    public function disableBlockModule($varValue, DataContainer $dc)
    {
        // prevent changing block module
        if ($dc->activeRecord->type == 'block' && !is_null($dc->activeRecord->block)) {
            return $dc->activeRecord->type;
        } // prevent block module creation
        else {
            if ($dc->activeRecord->type != 'block' && $varValue == 'block') {
                return $dc->activeRecord->type;
            }
        }

        return $varValue;
    }

    public function editBlockButtons($row, $href, $label, $title, $icon, $attributes)
    {
        if ($row['type'] == 'block') {
            $html = '';

            Controller::loadLanguageFile('tl_block');

            if ($href == 'act=edit') {
                // edit button
                $html .= '<a href="' . $this->addToUrl('&table=tl_block_module&id=' . $row['block']) . '" title="' . StringUtil::specialchars(
                        sprintf($GLOBALS['TL_LANG']['tl_block']['edit'][1], $row['block'])
                    ) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ';

                // edit header button
                $icon = 'header.gif';
                $html .= '<a href="' . $this->addToUrl('&table=tl_block&act=edit&id=' . $row['block']) . '" title="' . StringUtil::specialchars(
                        sprintf(($GLOBALS['TL_LANG']['tl_block']['editHeader'][1] ?? 'Edit block ID %s'), $row['block'])
                    ) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ';
            }

            return $html;
        }

        return '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml(
                $icon,
                $label
            ) . '</a> ';
    }

    /**
     * List a front end module
     *
     * @param array $row
     *
     * @return string
     */
    public function listModule($row)
    {
        if ($row['type'] == 'block') {
            Controller::loadLanguageFile('tl_block');

            $icon = '<a href="' . $this->addToUrl('&table=tl_block_module&id=' . $row['block']) . '" title="' . specialchars(
                    $GLOBALS['TL_LANG']['tl_block']['show'][0]
                ) . '">' . Image::getHtml('/system/modules/blocks/assets/icon.png', $GLOBALS['TL_LANG']['MOD']['blocks'], 'style="vertical-align: -4px;"')
                . '</a> ';

            return '<div style="float:left">' . $icon . $row['name'] . ' <span style="color:#b3b3b3;padding-left:3px">['
                . (isset($GLOBALS['TL_LANG']['FMD'][$row['type']][0]) ? $GLOBALS['TL_LANG']['FMD'][$row['type']][0] : $row['type']) . ']</span>'
                . "</div>\n";
        }

        $intMarginLeft = version_compare(VERSION, '4.0', '<') ? 19 : 20;

        return '<div style="margin-left: ' . $intMarginLeft . 'px; float:left">' . $row['name'] . ' <span style="color:#b3b3b3;padding-left:3px">[' . (isset($GLOBALS['TL_LANG']['FMD'][$row['type']][0]) ? $GLOBALS['TL_LANG']['FMD'][$row['type']][0] : $row['type']) . ']</span>' . "</div>\n";
    }
}

class_alias(ModuleBackend::class, 'tl_module_block');
