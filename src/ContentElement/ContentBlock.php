<?php

namespace HeimrichHannot\Blocks\ContentElement;

use Contao\ContentElement;
use HeimrichHannot\Blocks\Model\BlockModuleModel;

class ContentBlock extends ContentElement
{
    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'ce_block_module';

    public function generate(): string
    {
        $this->headline = ''; // unset headlines

        return parent::generate();
    }

    /**
     * Generate the content element
     */
    protected function compile(): void
    {
        $this->Template->content = BlockModuleModel::generateContent($this->block);
    }
}