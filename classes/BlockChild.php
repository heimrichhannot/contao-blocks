<?php
/**
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Blocks;


use Contao\Controller;
use Contao\Database;
use Contao\Environment;
use Contao\Frontend;
use Contao\Image;
use Contao\Input;
use Contao\Module;
use Contao\ModuleLoader;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;

class BlockChild
{

    /**
     * Current block child
     *
     * @var BlockModuleModel
     */
    protected $objModel;

    /**
     * Current page
     *
     * @var PageModel
     */
    protected $objPage;

    /**
     * BlockChild constructor.
     *
     * @param BlockModuleModel $objModel
     */
    public function __construct(BlockModuleModel $objModel)
    {
        $this->objModel = $objModel;

        $this->objPage = $this->determineCurrentPage();
    }

    /**
     * Render the block child
     */
    public function generate()
    {
        $strReturn = '';

        if (!$this->isVisible()) {
            return $strReturn;
        }

        switch ($this->objModel->type) {
            case 'article':
                $strReturn = $this->renderArticle();
                break;
            case 'content':
                $strReturn = $this->renderContent();
                break;
            case 'module':
            case 'default':
                $strReturn = $this->renderModule();
                break;
            default:
                // HOOK: add custom logic
                if (isset($GLOBALS['TL_HOOKS']['renderCustomBlockModule']) && is_array($GLOBALS['TL_HOOKS']['renderCustomBlockModule'])) {
                    foreach ($GLOBALS['TL_HOOKS']['renderCustomBlockModule'] as $callback) {
                        $strReturn = Controller::importStatic($callback[0])->{$callback[1]}($this->objModel, $strReturn);
                    }
                }
                break;
        }

        if ($this->objModel->addWrapper && strlen($strReturn) > 0) {
            $strReturn = $this->addBlockWrapper($strReturn);
        }

        return $strReturn;
    }

    /**
     * Render current block child as article
     *
     * @return bool|string
     */
    protected function renderArticle()
    {
        $objArticles = \ArticleModel::findPublishedById($this->objModel->articleAlias);

        if ($objArticles === null) {
            return '';
        }

        if (!\Controller::isVisibleElement($objArticles)) {
            return '';
        }

        return \Controller::getArticle($objArticles) ?: '';
    }

    /**
     * Render current block child as content
     *
     * @return string
     */
    protected function renderContent()
    {
        $strContent = '';
        $objElement = \ContentModel::findPublishedByPidAndTable($this->objModel->id, 'tl_block_module');

        if ($objElement !== null) {
            while ($objElement->next()) {
                if (!\Controller::isVisibleElement($objElement->current())) {
                    continue;
                }

                $strContent .= \Controller::getContentElement($objElement->current());
            }
        }

        return $strContent;
    }

    /**
     * Render current child as module
     *
     * @return string
     */
    protected function renderModule()
    {
        $objModel = \ModuleModel::findByPK($this->objModel->module);

        if ($objModel === null) {
            return '';
        }

        if (!Controller::isVisibleElement($objModel)) {
            return '';
        }


        $strClass = Module::findClass($objModel->type);

        if (!class_exists($strClass)) {
            Controller::log('Module class "'.$GLOBALS['FE_MOD'][$objModel->type].'" (module "'.$objModel->type.'") does not exist', 'ModuleBlock renderModule()', TL_ERROR);

            return '';
        }

        $this->objModel->typePrefix = 'mod_';

        if (!$this->objModel->addWrapper) {
            $objModel = $this->overrideCommonProps($objModel);
        }

        /**
         * @var Module $objModule
         */
        $objModule = new $strClass($objModel);

        $strBuffer = $objModule->generate();

        // HOOK: add custom logic
        if (isset($GLOBALS['TL_HOOKS']['getFrontendModule']) && is_array($GLOBALS['TL_HOOKS']['getFrontendModule'])) {
            foreach ($GLOBALS['TL_HOOKS']['getFrontendModule'] as $callback) {
                $strBuffer = Controller::importStatic($callback[0])->{$callback[1]}($objModel, $strBuffer, $objModule);
            }
        }

        return $strBuffer;
    }

    /**
     * Overwrite module properties
     *
     * @param ModuleModel $objItem
     *
     * @return ModuleModel
     */
    protected function overrideCommonProps($objItem)
    {
        $space = version_compare(VERSION, '4.0', '<') ? deserialize($this->objModel->space) : StringUtil::deserialize($this->objModel->space);
        $cssID = version_compare(VERSION, '4.0', '<') ? deserialize($this->objModel->cssID, true) : StringUtil::deserialize($this->objModel->cssID, true);


        // override original space settings with block module settings
        if ($space[0] != '' || $space[1] != '') {
            $objItem->space = $this->objModel->space;
        }

        // override original cssID with block module settings
        if ($cssID[0] != '' || $cssID[1] != '') {
            $objItem->cssID = $this->objModel->cssID;
        }

        return $objItem;
    }

    /**
     * Add a block wrapper to current child
     *
     * @param $strContent
     *
     * @return string
     */
    protected function addBlockWrapper($strContent)
    {
        $objT        = new \FrontendTemplate($this->objModel->customTpl ? $this->objModel->customTpl : 'blocks_wrapper');
        $objT->block = $strContent;
        $cssID       = $this->objModel->featureActive ? $this->objModel->feature_cssID : $this->objModel->cssID;
        $arrCssID    = version_compare(VERSION, '4.0', '<') ? deserialize($cssID, true) : \StringUtil::deserialize($cssID, true);
        $arrSpace    = version_compare(VERSION, '4.0', '<') ? deserialize($this->objModel->space) : \StringUtil::deserialize($this->objModel->space);
        $arrStyle    = [];

        if ($arrSpace[0] != '') {
            $arrStyle[] = 'margin-top:'.$arrSpace[0].'px;';
        }

        if ($arrSpace[1] != '') {
            $arrStyle[] = 'margin-bottom:'.$arrSpace[1].'px;';
        }

        $objT->style    = !empty($arrStyle) ? implode(' ', $arrStyle) : '';
        $objT->class    = trim($objT->getName().' '.$arrCssID[1]);
        $objT->cssID    = ($arrCssID[0] != '') ? ' id="'.$arrCssID[0].'"' : '';
        $objT->blockTpl = $this->objModel->customBlockTpl ? $this->objModel->customBlockTpl : 'block_searchable';

        // Add an image
        if (!empty($this->objModel->backgroundSRC)) {
            if (null !== ($objModel = \FilesModel::findByUuid($this->objModel->backgroundSRC)) && is_file(TL_ROOT.'/'.$objModel->path)) {

                $size = deserialize($this->objModel->backgroundSize, true);
                $path = Image::get($objModel->path, $size[0] ?? null, $size[1] ?? null, $size[2] ?? '');

                $objT->background = $path;
                $objT->style      .= sprintf('background-image: url(%s); background-size:cover;', $path);
            }
        }

        $arrHeadline    = version_compare(VERSION, '4.0', '<') ? deserialize($this->objModel->headline, true) : \StringUtil::deserialize($this->objModel->headline, true);
        $objT->headline = is_array($arrHeadline) ? $arrHeadline['value'] : $arrHeadline;
        $objT->hl       = is_array($arrHeadline) ? $arrHeadline['unit'] : 'h1';

        return $objT->parse();
    }

    /**
     * Determine if child is visible or not
     *
     * @return bool
     */
    protected function isVisible()
    {
        $time        = \Date::floorToMinute();
        $currentLang = ['', $GLOBALS['TL_LANGUAGE']];

        if (!in_array($this->objModel->language, $currentLang)) {
            return false;
        }
        
        $arrPages        = version_compare(VERSION, '4.0', '<') ? deserialize($this->objModel->pages, true) : \StringUtil::deserialize($this->objModel->pages, true);
        $arrKeywordPages = version_compare(VERSION, '4.0', '<') ? deserialize($this->objModel->keywordPages, true) : \StringUtil::deserialize($this->objModel->keywordPages, true);
        
        /**
         * Filter out pages
         * (exclude == display module not on this page)
         * (include == display module only on this page)
         */
        if (is_array($arrPages) && count($arrPages) > 0) {
            // add nested pages to the filter
            if ($this->objModel->addPageDepth) {
                if (version_compare(VERSION, '4.0', '>=')) {
                    if (\Contao\System::getContainer()->has('huh.utils.cache.database_tree')) {
                        $arrPages = array_merge($arrPages, \Contao\System::getContainer()->get('huh.utils.cache.database_tree')->getChildRecords('tl_page', $arrPages));
                    } else {
                        $arrPages = array_merge($arrPages, Database::getInstance()->getChildRecords($arrPages, 'tl_page'));
                    }
                } else {
                    $arrPages = array_merge($arrPages, Database::getInstance()->getChildRecords($arrPages, 'tl_page'));
                }
            }

            $check = ($this->objModel->addVisibility == 'exclude') ? true : false;
    
            if (in_array($this->objPage->id, $arrPages) == $check) {
                return false;
            }
        } elseif ($this->objModel->addVisibility == 'include') {
            return false;
        }

        // filter out modules by keywords
        if (strlen($this->objModel->keywords) > 0) {
            $arrKeywords = preg_split('/\s*,\s*/', trim($this->objModel->keywords), -1, PREG_SPLIT_NO_EMPTY);

            if (is_array($arrKeywords) && !empty($arrKeywords)) {
    
                foreach ($arrKeywords as $keyword) {
                    $negate  = substr($keyword, 0, 1) == '!';
                    $keyword = $negate ? substr($keyword, 1, strlen($keyword)) : $keyword;
                    
                    if (Input::get($keyword) != $negate) {
                        if (empty($arrKeywordPages) || (!empty($arrKeywordPages) && in_array($this->objPage->id, $arrKeywordPages))) {
                            return false;
                        }
                    }
                }
            }
        }
    
        // filter out by feature
        if ($this->objModel->feature) {
            $start = $this->objModel->feature_start;
            $stop  = $this->objModel->feature_stop;

            // check if in time
            $blnFeatureActive = ($start == '' || $start <= $time) && ($stop == '' || $stop > $time + 60);
            $blnFeatureCookie = $this->objModel->feature_count > 0;

            if ($blnFeatureActive && $blnFeatureCookie) {
                $cookieCount  = Input::cookie($this->objModel->feature_cookie_name);
                $displayCount = $cookieCount == null ? 0 : intval($cookieCount);

                if ($cookieCount === null && session_status() == PHP_SESSION_DISABLED) {
                    $blnFeatureActive = true;
                } else {
                    if ($displayCount < $this->objModel->feature_count) {
                        setcookie($this->objModel->feature_cookie_name, ++$displayCount, $time + $this->objModel->feature_cookie_expire, '/');
                        $blnFeatureActive = true;
                    } else {
                        $blnFeatureActive = false;
                    }
                }
            }

            $this->objModel->featureActive = $blnFeatureActive;

            return $blnFeatureActive;

        }
    
        if (isset($GLOBALS['TL_HOOKS']['isBlockVisibleHook']) && is_array($GLOBALS['TL_HOOKS']['isBlockVisibleHook'])) {
            foreach ($GLOBALS['TL_HOOKS']['isBlockVisibleHook'] as $callback) {
                if(!($visible = Controller::importStatic($callback[0])->{$callback[1]}($this->objModel))) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Do not use global $objPage, as long as pagelink module is enabled
     * because $objPage will hold the target page
     */
    protected function determineCurrentPage()
    {
        global $objPage;

        if (!in_array('pagelink', version_compare(VERSION, '4.0', '<') ? ModuleLoader::getActive() : array_keys(System::getContainer()->getParameter('kernel.bundles'))) && null !== $objPage) {

            return $objPage;
        }

        $pageId  = Frontend::getPageIdFromUrl();
        $objPage = PageModel::findPublishedByIdOrAlias($pageId) ?: Frontend::getRootPageFromUrl();

        if ($objPage instanceof \Contao\Model\Collection) {
            $objPage = $objPage->current();
        }

        return $objPage;
    }
}