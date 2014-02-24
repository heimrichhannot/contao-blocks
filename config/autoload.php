<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Blocks
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'HeimrichHannot',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Modules
	'HeimrichHannot\ModuleBlock'      => 'system/modules/blocks/modules/ModuleBlock.php',

	// Models
	'HeimrichHannot\BlockModuleModel' => 'system/modules/blocks/models/BlockModuleModel.php',
	'HeimrichHannot\BlockModel'       => 'system/modules/blocks/models/BlockModel.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_block_carousel' => 'system/modules/blocks/templates',
	'mod_block'          => 'system/modules/blocks/templates',
));
