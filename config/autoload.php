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
	'HeimrichHannot\Blocks\ModuleBlock'             => 'system/modules/blocks/modules/ModuleBlock.php',

	// Models
	'HeimrichHannot\Blocks\BlockModuleModel'        => 'system/modules/blocks/models/BlockModuleModel.php',
	'HeimrichHannot\Blocks\BlockModel'              => 'system/modules/blocks/models/BlockModel.php',

	// Classes
	'HeimrichHannot\Blocks\BlocksCarouselBootstrap' => 'system/modules/blocks/classes/carousel/BlocksCarouselBootstrap.php',
	'HeimrichHannot\Blocks\BlocksCarousel'          => 'system/modules/blocks/classes/carousel/BlocksCarousel.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_block'                 => 'system/modules/blocks/templates/modules',
	'blocks_carousel_bootstrap' => 'system/modules/blocks/templates/carousel',
));
