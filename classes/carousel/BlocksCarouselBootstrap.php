<?php 

namespace HeimrichHannot\Blocks;

class BlocksCarouselBootstrap extends BlocksCarousel
{
	protected $strTemplate = 'blocks_carousel_bootstrap';
	
	protected function compile()
	{
		$this->Template->href = '#' . $this->Template->cssID;
		$this->Template->active = key($this->arrItems);
	}
}
