## MODIFIED Requirements

### Requirement: Smarty is installed through Composer
The system SHALL obtain the Smarty library from the `smarty/smarty` Composer package at version `^5.8` and SHALL NOT ship a hand-vendored copy.

#### Scenario: Library resolved from vendor
- **WHEN** `Icms\View\Tpl` is instantiated
- **THEN** it is an instance of `Smarty\Smarty` loaded from `vendor/smarty/smarty`

### Requirement: ImpressCMS plugins live outside the vendor tree
ImpressCMS-specific functionality (the `db` resource and the `xoAppUrl`, `xoImgUrl`, `xoPageNav`, `xoInboxCount`, `xoops_link` and `resized_image` tags) SHALL be registered on the engine through the Smarty 5 plugin/extension API from classes under `htdocs/libraries/Icms/View/Smarty/`.

#### Scenario: Custom tags work with custom delimiters
- **WHEN** a template using `<{xoImgUrl}>` or `<{xoPageNav}>` is rendered
- **THEN** it produces the same output as under Smarty 2

#### Scenario: DB templates load
- **WHEN** a template is requested as `db:<name>`
- **THEN** the resource resolves it from the template set or theme override and recompiles when its modification time changes

## REMOVED Requirements

### Requirement: SMARTY_DIR remains available
**Reason**: Smarty 5 has no `libs/` entry file or plugin directory to point to.
**Migration**: Use the Composer autoloader; the constant is no longer defined.

## ADDED Requirements

### Requirement: Legacy tag aliases with deprecation notices
The system SHALL accept `<{includeq}>` and `<{foreachq}>` as aliases of `include` and `foreach`, and SHALL log an `E_USER_DEPRECATED` notice naming the template and the replacement each time one is compiled.

#### Scenario: Legacy include
- **WHEN** a template contains `<{includeq file="x.html" a=1}>`
- **THEN** it renders as `<{include file="x.html" a=1}>` and a deprecation notice is logged

### Requirement: Legacy Tpl method aliases with deprecation notices
`Icms\View\Tpl` SHALL keep the Smarty 2 method names used by modules (`assign_by_ref`, `clear_cache`, `clear_all_cache`, `register_function`, `register_modifier`, `get_template_vars`, `template_exists`, `is_cached`) as thin wrappers that log a deprecation notice and delegate to the Smarty 5 method.

#### Scenario: Module calls an old method
- **WHEN** a module calls `$xoopsTpl->get_template_vars('x')`
- **THEN** the value of `x` is returned and a deprecation notice is logged

### Requirement: Compiled templates are rebuilt on upgrade
The upgrade process SHALL delete all compiled templates in the compile directory.

#### Scenario: Upgrade
- **WHEN** the site is upgraded to this version
- **THEN** `templates_c` contains no files compiled by Smarty 2

### Requirement: Bundled templates contain no PHP blocks
Templates shipped with ImpressCMS SHALL NOT use `<{php}>` or `<{include_php}>`; required data SHALL be assigned from PHP.

#### Scenario: Notification token
- **WHEN** `system_notification_select.html` is rendered
- **THEN** the security token is supplied by an assigned variable, not a PHP block
