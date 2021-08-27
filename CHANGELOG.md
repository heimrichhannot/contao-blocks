# Changelog

All notable changes to this project will be documented in this file.

## [1.9.2] - 2021-08-27

- Added: php8 support

## [1.9.1] - 2021-07-29

- Added: `tl_block` as ctable of `tl_theme` in order to support deletion of blocks if the parent theme is removed

## [1.9.0] - 2021-07-29

- added new block module type: included content block module

## [1.8.5] - 2020-08-20

- added database tree cache since only used in this module and too special for contao-utils-bundle

## [1.8.4] - 2020-01-07

- added missing heading translations for backend modules (Themes -> [Theme] -> Blocks)

## [1.8.3] - 2019-08-21

### Fixed

- sorting in tl_block_module broken since contao 4.4.41

## [1.8.2] - 2019-05-23

### changed

- removed unnecessary fields in dca

## [1.8.1] - 2019-05-23

### changed

- move include/exclude filter param handling to hook
- hook is defined in [heimrichhannot/contao-filter-bundle](https://github.com/heimrichhannot/contao-filter-bundle)

## [1.8.0] - 2019-05-23

### Added

- inlcude or exclude block module by filter parameter from `heimrichhannot/contao-filter-bundle` (only available in
  Contao 4.4 or higher and with
  installed [heimrichhannot/contao-filter-bundle](https://github.com/heimrichhannot/contao-filter-bundle))

## [1.7.3] - 2019-04-18

### Fixed

- database error when fresh contao install

## [1.7.2] - 2019-03-29

### Fixed

- contao 3 support

## [1.7.1] - 2019-03-15

### Fixed

- breadcrumb now also respects pageTitle if set for auto_items
- some deprecation warnings
- some namespaces

## [1.7.0] - 2019-01-24

### Fixed

- added `huh.utils.cache.database_tree` caching to get childRecords of tl_page from cache

## [1.6.1] - 2019-01-24

### Fixed

- check if child exists to prevent printing an empty block

## [1.6.0] - 2018-12-17

### Changed

- php7 only

## [1.5.7] - 2018-11-28

### Fixed

- `Blockchild::determineCurrentPage()` that caused $objPage to be null in certain premises

## [1.5.6] - 2018-10-11

### Fixed

- method not found error in tl_block_module

## [1.5.5] - 2018-10-11

### Fixed

- method not found error in tl_block

## [1.5.4] - 2018-09-04

### Fixed

- `ContentBlock` did not render wrapper at all, make use of new `BlockChild` object to get rid off

## [1.5.3] - 2018-07-11

### Fixed

- reverted php 5.4+ support

## [1.5.2] - 2018-06-21

### Changed

- added Module constant

## [1.5.1] - 2018-06-21

### Fixed

- removed missing method call

## [1.5.0] - 2018-06-18

### Added

- `backgroundSize` added to resize block_module background images

## [1.4.3] - 2018-06-18

### Fixed

- Empty buffer check fixed (wrapper was added before due to \n characters)

## [1.4.2] - 2018-06-08

### Fixed

- BlockChild-> check null $this->objModel->backgroundSRC

## [1.4.1] - 2018-06-04

### Changed

- published default true

## [1.4.0] - 2018-06-04

### Added

- toggle visibility
- uncached function, output block module as "{{insert_block_module::id|uncached}}" if you dont want to cache the block
  module (contao cache)

## [1.3.1] - 2018-04-11

### Fixed

- visibility issue

## [1.3.0] - 2018-03-20

### Added

- `tl_block_module.keywordPages` to limit the pages where keywords are considered

### Changed

- licence from `LGPL-3.0+` to `LGPL-3.0-or-later`

## [1.2.4] - 2018-01-15

### Added

- background image to `tl_block_module` wrapper

## [1.2.3] - 2018-01-08

### Fixed

- hiding for visibility "include" and nothing selected

## [1.2.2] - 2017-10-20

### Changed

- `tl_block_module` label in list view for unknown types

## [1.2.1] - 2017-10-20

### Changed

- removed `menatwork/contao-multicolumnwizard` dependency

## [1.2.0] - 2017-10-17

### Added

- hook for custom block module types

## [1.1.1] - 2017-09-26

### Fixed

- invalid request token message when edit module within `tl_block_module`

## [1.1.0] - 2017-09-08

### Fixed

- deprecation functions updated for contao 4
- psr 2 code styling

## [1.0.34] - 2017-07-31

### Fixed

- InsertTags class

## [1.0.33] - 2017-07-27

### Fixed

- enhanced readability of module list

## [1.0.32] - 2017-07-03

### Fixed

- when tl_block addWrapper is active, css id and css class were not generated

## [1.0.31] - 2017-05-09

### Fixed

- too small cssID size

## [1.0.30] - 2017-04-27

### Changed

- varchar lengths to reduce db size

## [1.0.29] - 2017-04-06

### Added

- trigger `$GLOBALS['TL_HOOKS']['getFrontendModule']` for block children of type module 
