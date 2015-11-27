<?php

namespace HeimrichHannot\Blocks;


class ContentBlock extends \ContentElement
{

	/**
	 * Template
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
		$arrContent = array();

		$objBlock = BlockModuleModel::findByPk($this->block);

		if($objBlock === null) return;

		$objElement = \ContentModel::findPublishedByPidAndTable($this->block, 'tl_block_module');
		
		if ($objElement !== null)
		{
			while ($objElement->next())
			{
				$arrContent[] = \Controller::getContentElement($objElement->current());
			}
		}

		$strReturn = implode('', $arrContent);

		if($objBlock->addWrapper)
		{
			$strReturn = ModuleBlock::createBlockWrapper($objBlock, $strReturn);
		}

		$this->Template->content = $strReturn;
	}
}
