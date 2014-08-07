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
$GLOBALS['TL_LANG']['tl_block_module']['type'] = array('Type', 'Please select the type.');
$GLOBALS['TL_LANG']['tl_block_module']['title'] = array('Title', 'Please provide a title.');
$GLOBALS['TL_LANG']['tl_block_module']['module'] = array('Module', 'Please select a module.');
$GLOBALS['TL_LANG']['tl_block_module']['section'] = array('Custom layout section', 'Please select the custom layout you want to display.');
$GLOBALS['TL_LANG']['tl_block_module']['addSectionPageDepth'] = array('Activate page inheritance', 'Take over the custom layout content for child-pages as long as the child-page has no content for the custom layout section.');
$GLOBALS['TL_LANG']['tl_block_module']['addSectionPages'] = array('Embed layout area in the following pages', 'Specify that the layout area will always appear on this page.');
$GLOBALS['TL_LANG']['tl_block_module']['articleAlias'] = array('Related article', 'Please select the items you wish to insert.');
$GLOBALS['TL_LANG']['tl_block_module']['imgSRC'] = array('Thumbnail', 'Select a thumbnail for the article.');
$GLOBALS['TL_LANG']['tl_block_module']['addVisibility'] = array('Show module on certain pages', 'Limiting the visibility of the module for specific pages');
$GLOBALS['TL_LANG']['tl_block_module']['pages'] = array('Page filter', 'Specify on which pages the module should be displayed or not.');
$GLOBALS['TL_LANG']['tl_block_module']['addPageDepth'] = array('Activate page inheritance', 'Should the page filter be applied to child pages?');
$GLOBALS['TL_LANG']['tl_block_module']['hide'] = array('Hide module', 'Should the module be hidden for certain users (based on the frontend login)?');
$GLOBALS['TL_LANG']['tl_block_module']['keywords'] = array('Keywords', 'Comma seperated keywords like as "auto_item" indicates to include or exclude this module from Pages including these parameters. Negate via "!auto_item".');
$GLOBALS['TL_LANG']['tl_block_module']['cssID'] = array('CSS ID/class', 'Here you can set an ID and one or more classes.');
$GLOBALS['TL_LANG']['tl_block_module']['space'] = array('Space in front and after', 'Here you can enter the spacing in front of and after the block-module in pixel. You should try to avoid inline styles and define the spacing in a style sheet, though.');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_block_module']['type_legend'] = 'Type';
$GLOBALS['TL_LANG']['tl_block_module']['title_legend'] = 'Title';
$GLOBALS['TL_LANG']['tl_block_module']['section_legend'] = 'Layout sections';
$GLOBALS['TL_LANG']['tl_block_module']['article_legend'] = 'Article';
$GLOBALS['TL_LANG']['tl_block_module']['module_legend'] = 'Module';
$GLOBALS['TL_LANG']['tl_block_module']['page_legend'] = 'Pages';
$GLOBALS['TL_LANG']['tl_block_module']['hide_legend'] = 'Hide module';
$GLOBALS['TL_LANG']['tl_block_module']['expert_legend'] = 'Expert settings';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_block_module']['new'] = array('New block-module','Create a new block-module');
$GLOBALS['TL_LANG']['tl_block_module']['edit']	= array('Edit content elements','Edit block-module ID %s content elements');
$GLOBALS['TL_LANG']['tl_block_module']['editheader']	= array('Edit block-module','Edit block-module ID %s');
$GLOBALS['TL_LANG']['tl_block_module']['copy'] = array('Duplicate block-module','Duplicate block-module ID %s');
$GLOBALS['TL_LANG']['tl_block_module']['delete']= array('Delete block-module','Delete block-module ID %s');
$GLOBALS['TL_LANG']['tl_block_module']['show'] = array('Show block-module','Show block-module ID %s');

/**
 * References
 */
$GLOBALS['TL_LANG']['tl_block_module']['type_reference']['default'] = 'Module';
$GLOBALS['TL_LANG']['tl_block_module']['type_reference']['section'] = 'Layout sections';
$GLOBALS['TL_LANG']['tl_block_module']['type_reference']['article'] = 'Article';
$GLOBALS['TL_LANG']['tl_block_module']['type_reference']['content'] = 'Content elements';
$GLOBALS['TL_LANG']['tl_block_module']['exclude'] = 'All pages except';
$GLOBALS['TL_LANG']['tl_block_module']['include'] = 'Only on the following pages';
$GLOBALS['TL_LANG']['tl_block_module']['dont_hide'] = 'Don\'t hide';
$GLOBALS['TL_LANG']['tl_block_module']['hide_logged_in'] = 'Hide for logged in users';
$GLOBALS['TL_LANG']['tl_block_module']['hide_not_logged_in'] = 'Hide for not logged in users';