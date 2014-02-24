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
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Fatcrobat\Blocks'
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(

	/**
	 * Modules
	 */
	'Fatcrobat\Blocks\ModuleBlock'    															=> 'system/modules/blocks/modules/ModuleBlock.php',

	/**
	 * Models
	 */
	'Fatcrobat\Blocks\BlockModel'    																=> 'system/modules/blocks/models/BlockModel.php',
	'Fatcrobat\Blocks\BlockModuleModel'    													=> 'system/modules/blocks/models/BlockModuleModel.php',

));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_block'    																									=> 'system/modules/blocks/templates',
	'mod_block_carousel'																						=> 'system/modules/blocks/templates',
));