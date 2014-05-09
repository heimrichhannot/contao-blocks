<?php 

namespace HeimrichHannot\Blocks;

abstract class BlocksCarousel extends \Frontend
{
	protected $objModule;
	
	protected $objBlock;
	
	protected $arrItems;
	
	protected $strTemplate;
	
	public function __construct($arrItems, $objBlock, $objModule)
	{
		$this->arrItems = $arrItems;
		$this->objBlock = $objBlock;
		$this->objModule = $objModule;
	}
	
	public function generate()
	{
		if(!$this->strTemplate)
		{
			$objError = new \Exception('Blocks Carousel Template $strTemplate not declared for Class: ' . get_called_class() . '.');
			die($objError->getMessage());
		}
		
		$this->Template = new \FrontendTemplate($this->strTemplate);
		$this->Template->setData($this->arrData);
		
		$this->Template->class = $this->strTemplate;
		
		$this->Template->class .= $this->objBlock->cssClass != ' ' ? ' ' . $this->objBlock->cssClass : '';
		$this->Template->cssID = 'blocksCarousel' . $objBlock->id;
		
		$this->Template->items = $this->arrItems;
		
		$this->compile();
		
		
		return $this->Template->parse();
	}
	
	abstract protected function compile();
}
