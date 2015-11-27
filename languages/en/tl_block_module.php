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
$GLOBALS['TL_LANG']['tl_block_module']['articleAlias'] = array('Related article', 'Please select the items you wish to insert.');
$GLOBALS['TL_LANG']['tl_block_module']['imgSRC'] = array('Thumbnail', 'Select a thumbnail for the article.');
$GLOBALS['TL_LANG']['tl_block_module']['addVisibility'] = array('Show module on certain pages', 'Limiting the visibility of the module for specific pages');
$GLOBALS['TL_LANG']['tl_block_module']['pages'] = array('Page filter', 'Specify on which pages the module should be displayed or not.');
$GLOBALS['TL_LANG']['tl_block_module']['addPageDepth'] = array('Activate page inheritance', 'Should the page filter be applied to child pages?');
$GLOBALS['TL_LANG']['tl_block_module']['hide'] = array('Hide module', 'Should the module be hidden for certain users (based on the frontend login)?');
$GLOBALS['TL_LANG']['tl_block_module']['keywords'] = array('Keywords', 'Comma seperated keywords like as "auto_item" indicates to include or exclude this module from Pages including these parameters. Negate via "!auto_item".');
$GLOBALS['TL_LANG']['tl_block_module']['addWrapper'] = array('Add wrapper', 'Create a wrapper div with a unique CSS ID and any number of classes.');
$GLOBALS['TL_LANG']['tl_block_module']['cssID'] = array('CSS ID/class', 'Here you can set an ID and one or more classes.');
$GLOBALS['TL_LANG']['tl_block_module']['space'] = array('Space in front and after', 'Here you can enter the spacing in front of and after the block element in pixel. You should try to avoid inline styles and define the spacing in a style sheet, though.');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_block_module']['type_legend'] = 'Type';
$GLOBALS['TL_LANG']['tl_block_module']['title_legend'] = 'Title';
$GLOBALS['TL_LANG']['tl_block_module']['article_legend'] = 'Article';
$GLOBALS['TL_LANG']['tl_block_module']['module_legend'] = 'Module';
$GLOBALS['TL_LANG']['tl_block_module']['page_legend'] = 'Pages';
$GLOBALS['TL_LANG']['tl_block_module']['hide_legend'] = 'Hide module';
$GLOBALS['TL_LANG']['tl_block_module']['expert_legend'] = 'Expert settings';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_block_module']['new'] = array('New block element','Create a new block element');
$GLOBALS['TL_LANG']['tl_block_module']['edit']	= array('Edit content elements','Edit block element ID %s content elements');
$GLOBALS['TL_LANG']['tl_block_module']['editheader']	= array('Edit block element','Edit block element ID %s');
$GLOBALS['TL_LANG']['tl_block_module']['copy'] = array('Duplicate block element','Duplicate block element ID %s');
$GLOBALS['TL_LANG']['tl_block_module']['delete']= array('Delete block element','Delete block element ID %s');
$GLOBALS['TL_LANG']['tl_block_module']['show'] = array('Show block element','Show block element ID %s');

/**
 * References
 */
$GLOBALS['TL_LANG']['tl_block_module']['type_reference']['default'] = 'Module';
$GLOBALS['TL_LANG']['tl_block_module']['type_reference']['article'] = 'Article';
$GLOBALS['TL_LANG']['tl_block_module']['type_reference']['content'] = 'Content elements';
$GLOBALS['TL_LANG']['tl_block_module']['exclude'] = 'All pages except';
$GLOBALS['TL_LANG']['tl_block_module']['include'] = 'Only on the following pages';
$GLOBALS['TL_LANG']['tl_block_module']['dont_hide'] = 'Don\'t hide';
$GLOBALS['TL_LANG']['tl_block_module']['hide_logged_in'] = 'Hide for logged in users';
$GLOBALS['TL_LANG']['tl_block_module']['hide_not_logged_in'] = 'Hide for not logged in users';