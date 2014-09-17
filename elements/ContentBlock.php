<?php

namespace HeimrichHannot\Blocks;


class ContentBlock extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_block_module';


	/**
	 * Return the content elements, do not consider block configuration,
	 * otherwise visibility restrictions by page may take action
	 * @return string
	 */
	public function generate()
	{
		$strContent = '';
		$objElement = \ContentModel::findPublishedByPidAndTable($this->block, 'tl_block_module');

		if ($objElement !== null)
		{
			while ($objElement->next())
			{
				$strContent .= $this->getContentElement($objElement->current());
			}
		}

		return $strContent;
	}


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		return;
	}
}
