<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces([
    'HeimrichHannot',
]);


/**
 * Register the classes
 */
ClassLoader::addClasses([
    // Modules
    'HeimrichHannot\Blocks\ModuleBlock'                        => 'system/modules/blocks/modules/ModuleBlock.php',

    // Classes
    'HeimrichHannot\Blocks\Backend\Content'                    => 'system/modules/blocks/classes/backend/Content.php',
    'HeimrichHannot\Blocks\Hooks'                              => 'system/modules/blocks/classes/Hooks.php',
    'HeimrichHannot\Blocks\InsertTagsListener'                 => 'system/modules/blocks/classes/InsertTagsListener.php',
    'HeimrichHannot\Blocks\BlockChild'                         => 'system/modules/blocks/classes/BlockChild.php',
    'HeimrichHannot\Blocks\Exception\NoBlockChildrenException' => 'system/modules/blocks/classes/Exception/NoBlockChildrenException.php',
    'HeimrichHannot\Blocks\DataContainer\ModuleContainer'      => 'system/modules/blocks/DataContainer/Exception/ModuleContainer.php',

    // Elements
    'HeimrichHannot\Blocks\ContentBlock'                       => 'system/modules/blocks/elements/ContentBlock.php',

    // Models
    'HeimrichHannot\Blocks\BlockModel'                         => 'system/modules/blocks/models/BlockModel.php',
    'HeimrichHannot\Blocks\BlockModuleModel'                   => 'system/modules/blocks/models/BlockModuleModel.php',
]);


/**
 * Register the templates
 */
TemplateLoader::addFiles([
    'mod_breadcrumb'  => 'system/modules/blocks/templates/modules',
    'mod_block'       => 'system/modules/blocks/templates/modules',
    'blocks_wrapper'  => 'system/modules/blocks/templates/blocks',
    'ce_block_module' => 'system/modules/blocks/templates/elements',
]);
