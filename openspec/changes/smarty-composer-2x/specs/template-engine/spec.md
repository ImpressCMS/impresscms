## ADDED Requirements

### Requirement: Smarty is installed through Composer
The system SHALL obtain the Smarty library from the `smarty/smarty` Composer package and SHALL NOT ship a hand-vendored copy under `htdocs/libraries/smarty/`.

#### Scenario: Library resolved from vendor
- **WHEN** `Icms\View\Tpl` is instantiated
- **THEN** the `Smarty` class is loaded through the Composer autoloader from `vendor/smarty/smarty`

### Requirement: ImpressCMS plugins live outside the vendor tree
ImpressCMS-specific Smarty plugins (compilers, functions, the `db` resource) SHALL be located in `htdocs/libraries/Icms/View/Smarty/plugins/` and registered in `plugins_dir` ahead of the vendor plugins.

#### Scenario: Custom tag still compiles
- **WHEN** a template uses `<{includeq file="..."}>`, `<{foreachq}>`, `<{xoAppUrl}>` or `<{xoImgUrl}>`
- **THEN** it renders exactly as before the move

#### Scenario: DB templates still load
- **WHEN** a template is requested as `db:<name>`
- **THEN** the `db` resource resolves it from the template set or theme override as before

### Requirement: SMARTY_DIR remains available
The `SMARTY_DIR` constant SHALL be defined, pointing at the vendor Smarty `libs/` directory with a trailing slash, as soon as the template engine class is loaded (Smarty defines it itself; `include/constants.php` no longer does).

#### Scenario: Legacy code references SMARTY_DIR
- **WHEN** third-party code reads `SMARTY_DIR` after `Icms\View\Tpl` has been loaded
- **THEN** it resolves to an existing directory containing `Smarty.class.php`
