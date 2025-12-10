# Changelog

## [2.10.0] - 2025-12-10
### Added
- automatic icon detection from page blueprint in `Menu::page()`


## [2.9.0] - 2025-12-10
### Added
- `$link` param in `Menu::page()` now accepts UUIDs


## [2.8.0] - 2025-12-09
### Added
- optional `$prefix` parameter in `Translation->loadDir()` and `Translation->load()` methods (useful for loading plugin translations)


## [2.7.0] - 2025-07-03
### Added
- `Embed::type()` method

### Fixed
- `Embed::vimeoId()` method's incorrect regex pattern


## [2.6.0] - 2025-06-09
### Added
- `v()` helper function (adds a `?v=XXX` parameter to the URL for cache busting)


## [2.5.0] - 2025-01-13
### Added
- `Translation::loadDir` method

### Changed
- autoloading translations
    - support for naming translation files with a prefix (i.e. some-file.en.yaml)
    - support for multiple translation files in the same language (i.e. en.yaml and some-file.en.yaml, translations will be merged)
    - support for translation files in subdirectories


## [2.4.0] - 2024-10-07
### Added
- `JanHerman\Utils\Menu` class


## [2.3.0] - 2024-06-04
### Added
- Autoload translations from site/languages/translations directory


## [2.2.0] - 2024-05-28
### Added
- `JanHerman\Utils\Translation` class


## [2.1.0] - 2024-02-20
### Added
- `JanHerman\Utils\Embed` class


## [2.0.0] - 2024-01-19
### Added
- `JanHerman\Utils\Url` class

### Removed
- image related file methods (`thumbWebp`, `srcsetWebp` & `ratioPercentage`)
    - moved to separate plugin `jan-herman/kirby-images`


## [1.1.0] - 2023-08-12
### Added
- 'unhtml' field method


## [1.0.0] - 2023-02-19
### Added
- Initial release
