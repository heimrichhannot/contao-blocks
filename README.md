# Blocks

Contao blocks module is a site-dependent container, that manipulates the visibility of elements.

## Features

### Modules

Name | Description
---- | -----------
ModuleBlock | Checks the visibility of the block on the current page and renders it according to his type (article, content, module)

### Fields

tl_block:

Name | Description
---- | -----------
addWrapper | adds wrapper around all elements of block with unique id

tl_block_module:

Name | Description
---- | -----------
type | select type of element (article, content, module)
addVisibility | select if element shall be visible only on certain page or on several pages
pages | select pages for visibility
addPageDepth | adapt pagefilter on subpages
keywords | parameters that should be included or excluded from the model (negation with "!")
