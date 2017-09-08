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

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_block_module']['type']          = ['Type', 'Please select the type.'];
$GLOBALS['TL_LANG']['tl_block_module']['title']         = ['Title', 'Please provide a title.'];
$GLOBALS['TL_LANG']['tl_block_module']['module']        = ['Module', 'Please select a module.'];
$GLOBALS['TL_LANG']['tl_block_module']['articleAlias']  = ['Related article', 'Please select the items you wish to insert.'];
$GLOBALS['TL_LANG']['tl_block_module']['imgSRC']        = ['Thumbnail', 'Select a thumbnail for the article.'];
$GLOBALS['TL_LANG']['tl_block_module']['addVisibility'] = ['Show module on certain pages', 'Limiting the visibility of the module for specific pages'];
$GLOBALS['TL_LANG']['tl_block_module']['pages']         = ['Page filter', 'Specify on which pages the module should be displayed or not.'];
$GLOBALS['TL_LANG']['tl_block_module']['addPageDepth']  = ['Activate page inheritance', 'Should the page filter be applied to child pages?'];
$GLOBALS['TL_LANG']['tl_block_module']['hide']          = ['Hide module', 'Should the module be hidden for certain users (based on the frontend login)?'];
$GLOBALS['TL_LANG']['tl_block_module']['keywords']      = ['Keywords', 'Comma seperated keywords like as "auto_item" indicates to include or exclude this module from Pages including these parameters. Negate via "!auto_item".'];

$GLOBALS['TL_LANG']['tl_block_module']['feature']               = ['Feature block element', 'Feature the block element and control the display with cookies.'];
$GLOBALS['TL_LANG']['tl_block_module']['feature_start']         = ['Show from', 'Do not show the block element on the website before this day.'];
$GLOBALS['TL_LANG']['tl_block_module']['feature_stop']          = ['Show until', 'Do not show the block element on the website on and after this day.'];
$GLOBALS['TL_LANG']['tl_block_module']['feature_count']         = ['Display count by visitor', 'Determine how often the block element should be display per visitor. Enter 0 if you want the block element  to be displayed always.'];
$GLOBALS['TL_LANG']['tl_block_module']['feature_cookie_name']   = ['Cookie name', 'Enter a unique cookie name, that is used for saving the display count for each visitor.'];
$GLOBALS['TL_LANG']['tl_block_module']['feature_cookie_expire'] = ['Cookie duration', 'Enter the duration in milliseconds the visitors cookie should remain. After expiring, the feature will start again with display count 0.'];
$GLOBALS['TL_LANG']['tl_block_module']['feature_cssID']         = ['Overwrite CSS-ID/Class', 'Overwrite CSS-ID/Classes if the element is featured.'];

$GLOBALS['TL_LANG']['tl_block_module']['addWrapper']     = ['Add wrapper', 'Create a wrapper div with a unique CSS ID and any number of classes.'];
$GLOBALS['TL_LANG']['tl_block_module']['headline']       = ['Headline', 'Add a headline to the wrapper.'];
$GLOBALS['TL_LANG']['tl_block_module']['cssID']          = ['CSS ID/class', 'Here you can set an ID and one or more classes.'];
$GLOBALS['TL_LANG']['tl_block_module']['space']          = ['Space in front and after', 'Here you can enter the spacing in front of and after the block element in pixel. You should try to avoid inline styles and define the spacing in a style sheet, though.'];
$GLOBALS['TL_LANG']['tl_block_module']['customTpl']      = ['Custom wrapper-template', 'Overwrite the custom wrapper-template (Default: blocks_wrapper).'];
$GLOBALS['TL_LANG']['tl_block_module']['customBlockTpl'] = ['Custom block-template', 'Overwrite the block-template (Default: block_unsearchable).'];


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_block_module']['type_legend']    = 'Type';
$GLOBALS['TL_LANG']['tl_block_module']['title_legend']   = 'Title';
$GLOBALS['TL_LANG']['tl_block_module']['article_legend'] = 'Article';
$GLOBALS['TL_LANG']['tl_block_module']['module_legend']  = 'Module';
$GLOBALS['TL_LANG']['tl_block_module']['feature_legend'] = 'Feature settings';
$GLOBALS['TL_LANG']['tl_block_module']['page_legend']    = 'Pages';
$GLOBALS['TL_LANG']['tl_block_module']['hide_legend']    = 'Hide module';
$GLOBALS['TL_LANG']['tl_block_module']['expert_legend']  = 'Expert settings';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_block_module']['new']        = ['New block element', 'Create a new block element'];
$GLOBALS['TL_LANG']['tl_block_module']['edit']       = ['Edit content elements', 'Edit block element ID %s content elements'];
$GLOBALS['TL_LANG']['tl_block_module']['editheader'] = ['Edit block element', 'Edit block element ID %s'];
$GLOBALS['TL_LANG']['tl_block_module']['copy']       = ['Duplicate block element', 'Duplicate block element ID %s'];
$GLOBALS['TL_LANG']['tl_block_module']['delete']     = ['Delete block element', 'Delete block element ID %s'];
$GLOBALS['TL_LANG']['tl_block_module']['show']       = ['Show block element', 'Show block element ID %s'];

/**
 * References
 */
$GLOBALS['TL_LANG']['tl_block_module']['type_reference']['default'] = 'Module';
$GLOBALS['TL_LANG']['tl_block_module']['type_reference']['article'] = 'Article';
$GLOBALS['TL_LANG']['tl_block_module']['type_reference']['content'] = 'Content elements';
$GLOBALS['TL_LANG']['tl_block_module']['exclude']                   = 'All pages except';
$GLOBALS['TL_LANG']['tl_block_module']['include']                   = 'Only on the following pages';
$GLOBALS['TL_LANG']['tl_block_module']['dont_hide']                 = 'Don\'t hide';
$GLOBALS['TL_LANG']['tl_block_module']['hide_logged_in']            = 'Hide for logged in users';
$GLOBALS['TL_LANG']['tl_block_module']['hide_not_logged_in']        = 'Hide for not logged in users';