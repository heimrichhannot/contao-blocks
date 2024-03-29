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

use HeimrichHannot\Blocks\DataContainer\ModuleContainer;

$dc = &$GLOBALS['TL_DCA']['tl_module'];

$dc['config']['onload_callback'][] = ['tl_module_block', 'checkBlockPermission'];
$dc['config']['onload_callback'][] = ['tl_module_block', 'cleanup'];
$dc['config']['onload_callback'][] = [ModuleContainer::class, 'onLoadCallback'];

$dc['list']['sorting']['child_record_callback'] = ['tl_module_block', 'listModule'];

foreach ($dc['list']['operations'] as $key => $button) {
    if (in_array($key, ['edit', 'copy', 'cut', 'delete'])) {
        $dc['list']['operations'][$key]['button_callback'] = ['tl_module_block', 'editBlockButtons'];
    }
}

$dc['palettes'][\HeimrichHannot\Blocks\ModuleBlock::TYPE] = '{title_legend},headline,type;{block_legend},block';

$dc['fields']['block'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_module']['block'],
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => ['tl_module_block', 'getBlocks'],
    'eval'             => ['tl_class' => 'w50', 'mandatory' => true, 'readonly' => true],
    'sql'              => "int(10) unsigned NOT NULL default '0'",
];

$dc['fields']['type']['save_callback'] = [['tl_module_block', 'disableBlockModule']];

/**
 * Breadcrumb tweaks for auto_item
 */
$dc['palettes']['breadcrumb'] = str_replace('showHidden;', 'showHidden;{block_legend},hideAutoItem;', $dc['palettes']['breadcrumb']);

$dc['fields']['hideAutoItem'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_module']['hideAutoItem'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['tl_class' => 'w50'],
    'sql'       => "char(1) NOT NULL default ''",
];

class tl_module_block extends \tl_module
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

        \Database::getInstance()->prepare('DELETE FROM tl_module WHERE id IN(' . implode(",", $objModules->fetchEach('id')) . ')')->execute();
    }

    public function checkBlockPermission()
    {
        // Check current action
        if ($this->Input->get('act')) {
            // single actions
            if (in_array($this->Input->get('act'), ['edit', 'copy', 'cut', 'delete'])) {

                $objModule = $this->Database->prepare("SELECT block FROM tl_module WHERE id = ? and type='block'")->execute($this->Input->get('id'));

                if ($objModule->numRows) {
                    $this->redirect(
                        'contao/main.php?do=themes&amp;table=tl_block_module&amp;id=' . $objModule->block . '&amp;popup=1&amp;nb=1&amp;rt=' . REQUEST_TOKEN
                    );
                }
            }

            // batch actions
            if (in_array($this->Input->get('act'), ['editAll', 'copyAll', 'deleteAll', 'cutAll', 'showAll'])) {
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
            \Controller::loadLanguageFile('tl_block');

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