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
	// Classes
	'HeimrichHannot\Blocks\BlocksCarousel'          => 'system/modules/blocks/classes/carousel/BlocksCarousel.php',
	'HeimrichHannot\Blocks\BlocksCarouselBootstrap' => 'system/modules/blocks/classes/carousel/BlocksCarouselBootstrap.php',
	'HeimrichHannot\Blocks\Hooks'                   => 'system/modules/blocks/classes/Hooks.php',

	// Elements
	'HeimrichHannot\Blocks\ContentBlock'            => 'system/modules/blocks/elements/ContentBlock.php',

	// Models
	'HeimrichHannot\Blocks\BlockModel'              => 'system/modules/blocks/models/BlockModel.php',
	'HeimrichHannot\Blocks\BlockModuleModel'        => 'system/modules/blocks/models/BlockModuleModel.php',

	// Modules
	'HeimrichHannot\Blocks\ModuleBlock'             => 'system/modules/blocks/modules/ModuleBlock.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'blocks_carousel_bootstrap' => 'system/modules/blocks/templates/carousel',
	'ce_block_module'           => 'system/modules/blocks/templates/elements',
	'mod_block'                 => 'system/modules/blocks/templates/modules',
));
