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

namespace HeimrichHannot;

class ModuleBlock extends \Module
{
	protected $strTemplate = 'mod_block';

	protected $strBuffer = '';

	protected $objPage;

	private $arrPageCache;

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

		if(!$this->block) return '';

		return parent::generate();
	}

	protected function compile()
	{
		$this->objPage = $this->determineCurrentPage();

		$objBlock = BlockModel::findByPk($this->block);

		$objChilds = $this->Database->prepare('SELECT * FROM tl_block_module WHERE pid = ? ORDER BY sorting')->execute($this->block);

		$arrChilds = array();

		while($objChilds->next())
		{
			if (strlen($objChilds->hide) == 0 || $objChilds->hide == 1 || ($objChilds->hide == 2 && !FE_USER_LOGGED_IN) || ($objChilds->hide == 3 && FE_USER_LOGGED_IN))
			{
				$value = $this->renderChild($objChilds);

				$blnMultiMode = is_array($value);
				
				if(($blnMultiMode && empty($value)) || (!$blnMultiMode && strlen($value) == 0)) continue;

				if($blnMultiMode)
				{
					foreach($value as $item)
					{
						$arrChilds[] = array
						(
							'output'		=> $item,
						);
					}

					$strBuffer = implode('', $value);
				}
				else
				{
					$objFile = \FilesModel::findByPk($objChilds->imgSRC);
	
					$arrChilds[$objChilds->id] = array
					(
						'output'		=> $value,
						'arrData'		=> $objChilds->row(),
						'image'			=> $objFile->path ? $this->generateImage($objFile->path) : ''
					);
					$strBuffer .= $value;
				}
			}
		}

		$this->Template->modules = $strBuffer;

		if($objBlock->carousel && strlen($strBuffer) > 0)
		{
			$this->Template->modules = $this->renderCarousel($arrChilds, $objBlock, $blnMultiMode ? $objChilds->first() : null);
		}
	}

	protected function renderChild($objChild)
	{
		if(!$this->isVisible($objChild)) return '';

		switch($objChild->type)
		{
			case 'section':
				return $this->renderSection($objChild);
			case 'article':
				return $this->renderArticle($objChild);
			case 'module':
			default:
				return $this->renderModule($objChild);
		}
	}

	protected function renderCarousel($arrItems, $objBlock, $objChild)
	{
		$strTemplate = 'mod_block_carousel';

		$objT = new \FrontendTemplate($strTemplate);
		
		$objT->class = $strTemplate;
		$objT->id = 'blockCarousel' . $objBlock->id;

		if($objChild !== null)
		{
			$arrCssID = deserialize($objChild->cssID, true);
			$objT->class .= strlen($arrCssID[1]) > 0 ? ' ' . $arrCssID[1] : '';
			$objT->id = strlen($arrCssID[0]) > 0 ? ' ' . $arrCssID[0] : $objT->id;
		}
		
		$objT->href = '#' . $objT->id;
		$objT->items = $arrItems;
		$objT->active = key($arrItems);

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
				$arrPages = array_merge($arrPages, $this->getChildRecords($arrPages, 'tl_page'));
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

	protected function findParentSection($pid, $objChild)
	{
		$objPages = \PageModel::findParentsById($pid);

		// no parent pages available --> return false
		if($objPages === null)
		{
			return null;
		}

		$objArticles = \ArticleModel::findPublishedByPidAndColumn($objPages->pid, $objChild->section);

		if($objArticles === null)
		{
			return $this->findParentSection($objPages->pid, $objChild);
		}

		return $objArticles;
	}

	protected function renderArticle($objChild)
	{
		$objArticles = \ArticleModel::findPublishedById($objChild->articleAlias);

		if ($objArticles === null)
		{
			return '';
		}

		$return = $this->getArticle($objArticles, false, false, $strColumn);

		return $this->getArticle($objArticles);
	}

	protected function renderSection($objChild)
	{
		$pid = $this->objPage->id;

		$objArticles = \ArticleModel::findPublishedByPidAndColumn($pid, $objChild->section);

		// add parent article, if there no article on current page
		if($objChild->addSectionPageDepth && $objArticles === null)
		{
			$objArticles = $this->findParentSection($pid, $objChild);
		}

		if ($objArticles === null)
		{
			return '';
		}

		$blnMultiMode = ($objArticles->count() > 1);
		
		$arrItems = array();
		
		while ($objArticles->next())
		{
			$objArticles = $this->overrideCommonProps($objArticles, $objChild);
			$arrItems[] = $this->getArticle($objArticles->current(), $blnMultiMode, false, $strColumn);
		}

		return $blnMultiMode ? $arrItems : implode('', $arrItems);
	}

	protected function renderModule($objChild)
	{
		$objModule = \ModuleModel::findByPK($objChild->module);

		if($objModule === null) return '';

		$strClass = $this->findFrontendModule($objModule->type);

		if (!$this->classFileExists($strClass))
		{
			$this->log('Module class "'.$GLOBALS['FE_MOD'][$objModule->type].'" (module "'.$objModule->type.'") does not exist', 'ModuleBlock renderModule()', TL_ERROR);
			return '';
		}

		$objModule->typePrefix = 'mod_';

		$objModule = $this->overrideCommonProps($objModule, $objChild);

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