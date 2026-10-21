## Why

Smarty 2.6.31 is vendored by hand in `htdocs/libraries/smarty/`, so it cannot be updated with the rest of the dependencies and ImpressCMS-specific plugins are mixed in with the vendor tree. Moving it to Composer first, with no behaviour change, isolates dependency plumbing from the later engine upgrade (see `smarty-5-upgrade`).

## What Changes

- Require `smarty/smarty` `2.6.33` in `composer.json` (the last 2.6 release on Packagist) and update `composer.lock`.
- Remove the vendored `htdocs/libraries/smarty/` core and stock `plugins/`, `internals/`, `configs/`.
- Move the ImpressCMS plugins (`icms_plugins/*`) to `htdocs/libraries/Icms/View/Smarty/plugins/`.
- `Icms\View\Tpl` loads Smarty from the Composer autoloader (classmap) instead of `require_once SMARTY_DIR`; `plugins_dir` points to the moved ICMS plugins plus the vendor `libs/plugins`.
- Keep `SMARTY_DIR` defined (pointing at the vendor `libs/` directory) so third-party code does not break.
- Update `.codeclimate.yml` / `.scrutinizer.yml` excludes.
- Review the 2.6.31 -> 2.6.33 diff for behaviour changes.
- No template, delimiter or API changes. Targets the `pre-3` branch.

## Capabilities

### New Capabilities
- `template-engine`: how ImpressCMS obtains and configures its Smarty template engine.

### Modified Capabilities

## Impact

- `composer.json`, `composer.lock`, `htdocs/include/constants.php`, `htdocs/libraries/Icms/View/Tpl.php`.
- Deleted/moved: `htdocs/libraries/smarty/**`.
- Compiled templates should remain valid, but clearing `templates_c` is recommended.
- Release packaging must include `vendor/smarty/smarty`.
