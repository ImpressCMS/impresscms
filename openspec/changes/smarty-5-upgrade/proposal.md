## Why

After `smarty-composer-2x`, ImpressCMS runs Smarty 2.6.33 from Composer. Smarty 2 is unmaintained and incompatible with current PHP idioms; Smarty 5 (latest 5.8.x, PHP ^7.2 || ^8.0) is the supported line. Upgrading removes the last EOL runtime dependency.

## What Changes

- Require `smarty/smarty` `^5.8`; `Icms\View\Tpl` extends `Smarty\Smarty` and uses the camelCase API (`setLeftDelimiter('<{')`, `setCompileDir`, `getTemplateVars`, ...).
- Port the `db:` resource to a `Smarty\Resource\CustomPlugin` subclass.
- Port ICMS plugins (`xoAppUrl`, `xoImgUrl`, `xoPageNav`, `xoInboxCount`, `xoops_link`, `resized_image`) to `registerPlugin()` / an extension class.
- **Legacy aliases (deprecated):** `includeq` -> `include`, `foreachq` -> `foreach`, and the old snake_case methods on `Tpl` (`assign_by_ref`, `clear_cache`, `register_*`, `get_template_vars`, ...). Each logs an `E_USER_DEPRECATED` notice (logged, not displayed).
- Replace `fetchFromData()` / `touch()` internals (previously `smarty_function_eval`, `_compile_resource`) with Smarty 5 equivalents (string resource, forced compile).
- Rewrite the three templates using `<{php}>`: `image-editor/plugins/filter/templates/filter_image.html`, `modules/system/templates/system_notification_select.html`, `themes/iTheme/theme_admin.html` (move logic to PHP / assigned variables).
- Sweep the ~46 PHP files that touch the Smarty API (tplsets admin, `cp_functions.php`, `header.php`, `Theme/Entity`, `PageBuilder`, ...).
- Upgrade step wipes `templates_c`; remove `SMARTY_DIR`.
- **BREAKING** for third-party templates/modules: `{php}`, `{include_php}`, PHP functions as modifiers outside Smarty's allowlist, and direct use of Smarty 2 internals no longer work.
- Targets `pre-3`; PR 2 of 2.

## Capabilities

### New Capabilities

### Modified Capabilities
- `template-engine`: engine version, plugin registration, legacy alias behaviour (spec created by `smarty-composer-2x`; archive that change first).

## Impact

- `composer.json/lock`, `htdocs/libraries/Icms/View/Tpl.php`, `htdocs/libraries/Icms/View/Smarty/**`, `htdocs/include/constants.php`, ~46 PHP callers, 3 templates, `upgrade/`.
- Third-party modules and themes; release notes must document the breaks.
