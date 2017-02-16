<?php

namespace HeimrichHannot\Blocks;


class ContentBlock extends \ContentElement
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
