<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
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
	// Models
	'HeimrichHannot\Blocks\BlockModuleModel'        => 'system/modules/blocks/models/BlockModuleModel.php',
	'HeimrichHannot\Blocks\BlockModel'              => 'system/modules/blocks/models/BlockModel.php',

	// Modules
	'HeimrichHannot\Blocks\ModuleBlock'             => 'system/modules/blocks/modules/ModuleBlock.php',

	// Elements
	'HeimrichHannot\Blocks\ContentBlock'            => 'system/modules/blocks/elements/ContentBlock.php',

	// Classes
	'HeimrichHannot\Blocks\Hooks'                   => 'system/modules/blocks/classes/Hooks.php',
	'HeimrichHannot\Blocks\BlocksCarousel'          => 'system/modules/blocks/classes/carousel/BlocksCarousel.php',
	'HeimrichHannot\Blocks\BlocksCarouselBootstrap' => 'system/modules/blocks/classes/carousel/BlocksCarouselBootstrap.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_block'                 => 'system/modules/blocks/templates/modules',
	'mod_breadcrumb'            => 'system/modules/blocks/templates/modules',
	'ce_block_module'           => 'system/modules/blocks/templates/elements',
	'blocks_carousel_bootstrap' => 'system/modules/blocks/templates/carousel',
));
