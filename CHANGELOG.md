# Changelog
All notable changes to this project will be documented in this file.

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
- uncached function, output block module as "{{insert_block_module::id|uncached}}" if you dont want to cache the block module (contao cache)

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
