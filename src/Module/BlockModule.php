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

use AllowDynamicProperties;
use Contao\BackendTemplate;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\Model;
use Contao\Model\Collection;
use Contao\Module;
use Contao\StringUtil;
use Contao\System;
use HeimrichHannot\Blocks\BlockChild;
use HeimrichHannot\Blocks\Exception\NoBlockChildrenException;
use HeimrichHannot\Blocks\Model\BlockModel;
use HeimrichHannot\Blocks\Model\BlockModuleModel;

#[AllowDynamicProperties]
class BlockModule extends Module
{
    const TYPE = 'block';

    protected $strTemplate = 'mod_block';

    protected string $buffer = '';

    protected array|Model|Collection|null $objBlock;

    public function __construct($objModule, $strColumn = 'main')
    {
        parent::__construct($objModule, $strColumn);

        $this->objChildren = BlockModel::findPublishedByPk($this->block);

        if ($this->objBlock !== null) {
            foreach ($this->objBlock->row() as $key => $value) {
                // overwrite module parameter with block parameter, except the following
                if (in_array($key, ['id', 'pid', 'tstamp', 'module', 'title'])) {
                    continue;
                }

                $this->{$key} = StringUtil::deserialize($value);
            }
        }
    }

    public function generate(): string
    {
        $scopeMatcher = System::getContainer()->get(ScopeMatcher::class);
        $requestStack = System::getContainer()->get('request_stack');

        if ($scopeMatcher->isBackendRequest($requestStack->getCurrentRequest()))
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### BLOCK ###';
            $objTemplate->title    = $this->headline;
            $objTemplate->id       = $this->id;
            $objTemplate->link     = $this->name;
            $objTemplate->href     = 'contao?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

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

    protected function compile(): void
    {
        $this->Template->block = null; // reset block attribute, otherwise block id will be printed

        $objChildren = BlockModuleModel::findPublishedByPid($this->block, ['order' => 'sorting']);

        if ($objChildren === null) {
            throw new NoBlockChildrenException("No block children found.");
        }

        $hasFrontendUser = System::getContainer()->get('contao.security.token_checker')->hasFrontendUser();
        $strBuffer = '';

        $childrenCount = 0;
        while ($objChildren->next()) {
            if (strlen($objChildren->hide) == 0
                || $objChildren->hide == 1
                || ($objChildren->hide == 2 && !$hasFrontendUser)
                || ($objChildren->hide == 3 && $hasFrontendUser)
            ) {
                $child = $this->renderChild($objChildren->current());
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
            $this->cssID = StringUtil::deserialize($this->objBlock->cssID);
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