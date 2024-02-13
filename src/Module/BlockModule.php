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

namespace HeimrichHannot\Blocks\Module;

use Contao\BackendTemplate;
use Contao\Module;
use Contao\StringUtil;
use HeimrichHannot\Blocks\Exception\NoBlockChildrenException;
use HeimrichHannot\Blocks\Model\BlockModel;
use HeimrichHannot\Blocks\Model\BlockModuleModel;

class BlockModule extends Module
{
    const TYPE = 'block';

    protected $strTemplate = 'mod_block';

    protected $strBuffer = '';

    protected $objBlock;

    public function __construct($objModule, $strColumn = 'main')
    {
        parent::__construct($objModule, $strColumn);

        $this->objBlock = BlockModel::findPublishedByPk($this->block);

        if ($this->objBlock !== null) {
            foreach ($this->objBlock->row() as $key => $value) {
                // overwrite module parameter with block parameter, except the following
                if (in_array($key, ['id', 'pid', 'tstamp', 'module', 'title'])) {
                    continue;
                }

                $this->{$key} = version_compare(VERSION, '4.0', '<') ? deserialize($value) : StringUtil::deserialize($value);
            }
        }
    }

    public function generate()
    {
        if (TL_MODE == 'BE') {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### BLOCK ###';
            $objTemplate->title    = $this->headline;
            $objTemplate->id       = $this->id;
            $objTemplate->link     = $this->name;
            $objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        if ($this->objBlock == null) {
            return '';
        }

        try {
            return parent::generate();
        } catch (NoBlockChildrenException $e) {
            return '';
        }
    }

    protected function compile()
    {
        $this->Template->block = null; // reset block attribute, otherwise block id will be printed

        $objChilds = BlockModuleModel::findPublishedByPid($this->block, ['order' => 'sorting']);

        if ($objChilds === null) {
            throw new NoBlockChildrenException("No block children found.");
        }

        $strBuffer = '';

        $childrenCount = 0;
        while ($objChilds->next()) {
            if (strlen($objChilds->hide) == 0 || $objChilds->hide == 1 || ($objChilds->hide == 2 && !FE_USER_LOGGED_IN) || ($objChilds->hide == 3 && FE_USER_LOGGED_IN)) {
                $child = $this->renderChild($objChilds->current());
                if (empty($child)) {
                    continue;
                }
                $childrenCount++;

                $strBuffer .= $child . "\n";
            }
        }
        if ($childrenCount < 1) {
            throw new NoBlockChildrenException("No visible block child!");
        }

        if ($this->objBlock->addWrapper) {
            $this->cssID = version_compare(VERSION, '4.0', '<') ? deserialize($this->objBlock->cssID) : \StringUtil::deserialize($this->objBlock->cssID);
        }

        if (strlen(preg_replace('/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', '', $strBuffer)) == 0) {
            $this->Template->addWrapper = false;
        }

        $this->Template->block = $strBuffer;
    }

    protected function renderChild($objChild)
    {
        if ($objChild->uncached) {
            return sprintf('{{insert_block_child::%s|uncached}}', $objChild->id);
        }

        $blockChild = new BlockChild($objChild);

        return $blockChild->generate();
    }
}

class_alias(BlockModule::class, 'HeimrichHannot\Blocks\ModuleBlock');
