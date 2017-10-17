# Blocks

Contao blocks module is a site-dependent container, that manipulates the visibility of elements.

## Features

### Hooks

Name | Arguments | Expected return value | Description
---- | --------- | --------------------- | -----------
renderCustomBlockModule | $objBlockModule, $strReturn | the rendered module as string | Add custom block module type rendering

### Modules

Name | Description
---- | -----------
ModuleBlock | Checks the visibility of the block on the current page and renders it according to his type (article, content, module)