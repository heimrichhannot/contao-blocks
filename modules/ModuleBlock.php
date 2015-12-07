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

namespace HeimrichHannot\Blocks;

class ModuleBlock extends \Module
{
	protected $strTemplate = 'mod_block';

	protected $strBuffer = '';

	protected $objPage;

	private $arrPageCache;

	protected $objBlock;

	public function __construct($objModule, $strColumn='main')
	{
		parent::__construct($objModule, $strColumn);

		$this->objBlock = BlockModel::findByPk($this->block);

		if($this->objBlock !== null)
		{
			foreach($this->objBlock->row() as $key => $value)
			{
				// overwrite module parameter with block parameter, except the following
				if(in_array($key, array('id', 'pid', 'tstamp', 'module', 'title'))) continue;

				$this->{$key} = deserialize($value);
			}
		}
	}

	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### BLOCK ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		if($this->objBlock == null) return '';

		return parent::generate();
	}

	protected function compile()
	{
		$this->objPage = $this->determineCurrentPage();

		$objChilds = BlockModuleModel::findBy('pid', $this->block, array('order' => 'sorting'));

		if($objChilds === null) return '';

		$strBuffer = '';

		while($objChilds->next())
		{
			if (strlen($objChilds->hide) == 0 || $objChilds->hide == 1 || ($objChilds->hide == 2 && !FE_USER_LOGGED_IN) || ($objChilds->hide == 3 && FE_USER_LOGGED_IN))
			{
				$strBuffer .= $this->renderChild($objChilds);
			}
		}

		$this->Template->block = $strBuffer;
	}

	protected function renderChild($objChild)
	{
		$strReturn = '';

		if(!$this->isVisible($objChild)) return $strReturn;

		switch($objChild->type)
		{
			case 'article':
				$strReturn = $this->renderArticle($objChild);
			break;
			case 'content':
				$strReturn = $this->renderContent($objChild);
			break;
			case 'module':
			default:
				$strReturn = $this->renderModule($objChild);
			break;
		}

		if($objChild->addWrapper)
		{
			$strReturn = static::createBlockWrapper($objChild, $strReturn);
		}

		return $strReturn;
	}

	public static function createBlockWrapper($objBlock, $strContent)
	{
		$objT = new \FrontendTemplate('blocks_wrapper');
		$objT->block = $strContent;
		$arrCssID = deserialize($objBlock->cssID, true);
		$arrSpace = deserialize($objBlock->space);
		$arrStyle = array();

		if ($arrSpace[0] != '')
		{
			$arrStyle[] = 'margin-top:'.$arrSpace[0].'px;';
		}

		if ($arrSpace[1] != '')
		{
			$arrStyle[] = 'margin-bottom:'.$arrSpace[1].'px;';
		}

		$objT->style = !empty($arrStyle) ? implode(' ', $arrStyle) : '';
		$objT->class = trim($objT->getName() . ' ' . $arrCssID[1]);
		$objT->cssID = ($arrCssID[0] != '') ? ' id="' . $arrCssID[0] . '"' : '';

		return $objT->parse();
	}

	protected function isVisible($objChild)
	{
		$currentLang = array('', $GLOBALS['TL_LANGUAGE']);

		if(!in_array($objChild->language, $currentLang))
		{
			return false;
		}

		$arrPages = deserialize($objChild->pages);



		/**
		 * Filter out pages
		 * (exclude == display module not on this page)
		 * (include == display module only on this page)
		 */
		if(is_array($arrPages) && count($arrPages) > 0)
		{
			// add nested pages to the filter
			if($objChild->addPageDepth)
			{
				$arrPages = array_merge($arrPages, \Database::getInstance()->getChildRecords($arrPages, 'tl_page'));
			}


			$check = ($objChild->addVisibility == 'exclude') ? true : false;

			if(in_array($this->objPage->id, $arrPages) == $check)
			{
				return false;
			}
		}

		// filter out modules by keywords
		if(strlen($objChild->keywords) > 0)
		{
			$arrKeywords = preg_split('/\s*,\s*/', trim($objChild->keywords), -1, PREG_SPLIT_NO_EMPTY);

			if(is_array($arrKeywords) && !empty($arrKeywords))
			{
				foreach($arrKeywords as $keyword)
				{
					$negate = substr($keyword, 0, 1) == '!';
					$keyword = $negate ? substr($keyword, 1, strlen($keyword)) : $keyword;

					if($this->Input->get($keyword) != $negate)
					{
						return false;
					}
				}
			}
		}

		return true;
	}

	protected function renderContent($objChild)
	{
		$strContent = '';
		$objElement = \ContentModel::findPublishedByPidAndTable($objChild->id, 'tl_block_module');

		if ($objElement !== null)
		{
			while ($objElement->next())
			{
				if(!\Controller::isVisibleElement($objElement->current())) return '';

				$strContent .= \Controller::getContentElement($objElement->current());
			}
		}

		return $strContent;
	}

	protected function renderArticle($objChild)
	{
		$objArticles = \ArticleModel::findPublishedById($objChild->articleAlias);

		if ($objArticles === null)
		{
			return '';
		}

		if(!\Controller::isVisibleElement($objArticles)) return '';

		return \Controller::getArticle($objArticles);
	}

	protected function renderModule($objChild)
	{
		$objModule = \ModuleModel::findByPK($objChild->module);

		if($objModule === null) return '';

		if(!\Controller::isVisibleElement($objModule)) return '';
		
		$strClass = \Module::findClass($objModule->type);

		if (!class_exists($strClass))
		{
			$this->log('Module class "'.$GLOBALS['FE_MOD'][$objModule->type].'" (module "'.$objModule->type.'") does not exist', 'ModuleBlock renderModule()', TL_ERROR);
			return '';
		}

		$objModule->typePrefix = 'mod_';

		if(!$objChild->addWrapper)
		{
			$objModule = $this->overrideCommonProps($objModule, $objChild);
		}

		$objModule = new $strClass($objModule);

		return $objModule->generate();
	}

	protected function overrideCommonProps($objItem, $objChild)
	{
		$space = deserialize($objChild->space);
		$cssID = deserialize($objChild->cssID, true);


		// override original space settings with block module settings
		if ($space[0] != '' || $space[1] != '')
		{
			$objItem->space = $objChild->space;
		}

		// override original cssID with block module settings
		if($cssID[0] != '' || $cssID[1] != '')
		{
			$objItem->cssID = $objChild->cssID;
		}

		return $objItem;
	}

	/**
	 * Do not use global $objPage, as long as pagelink module is enabled
	 * because $objPage will hold the target page
	 */
	protected function determineCurrentPage()
	{
		if (!in_array('pagelink', $this->Config->getActiveModules()))
		{
			global $objPage;
			return $objPage;
		}

		$pageId = $this->getPageIdFromUrl();
		$objPage = \PageModel::findPublishedByIdOrAlias($pageId);

		// Check the URL and language of each page if there are multiple results
		if ($objPage !== null && $objPage->count() > 1)
		{
			$objNewPage = null;
			$arrPages = array();

			// Order by domain and language
			while ($objPage->next())
			{
				$objCurrentPage = $objPage->current()->loadDetails();

				$domain = $objCurrentPage->domain ?: '*';
				$arrPages[$domain][$objCurrentPage->rootLanguage] = $objCurrentPage;

				// Also store the fallback language
				if ($objCurrentPage->rootIsFallback)
				{
					$arrPages[$domain]['*'] = $objCurrentPage;
				}
			}

			$strHost = \Environment::get('host');

			// Look for a root page whose domain name matches the host name
			if (isset($arrPages[$strHost]))
			{
				$arrLangs = $arrPages[$strHost];
			}
			else
			{
				$arrLangs = $arrPages['*']; // Empty domain
			}

			// Use the first result (see #4872)
			if (!$GLOBALS['TL_CONFIG']['addLanguageToUrl'])
			{
				$objNewPage = current($arrLangs);
			}
			// Try to find a page matching the language parameter
			elseif (($lang = Input::get('language')) != '' && isset($arrLangs[$lang]))
			{
				$objNewPage = $arrLangs[$lang];
			}

			// Store the page object
			if (is_object($objNewPage))
			{
				$objPage = $objNewPage;
			}
		}

		return $objPage;
	}

}
