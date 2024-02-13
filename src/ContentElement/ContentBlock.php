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

    public function generate()
    {
        $this->headline = ''; // unset headlines

        return parent::generate();
    }

    /**
     * Generate the content element
     */
    protected function compile()
    {
        $this->Template->content = BlockModuleModel::generateContent($this->block);
    }
}

class_alias(ContentBlock::class, 'HeimrichHannot\Blocks\ContentBlock');
