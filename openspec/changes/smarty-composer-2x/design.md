## Context

`Icms\View\Tpl extends \Smarty` and assumes `SMARTY_DIR` holds the library and `plugins/` + `icms_plugins/`. Smarty 2.x on Packagist is `smarty/smarty` v2.6.33, which has a classmap for `libs/Smarty.class.php`, `Smarty_Compiler.class.php` and `Config_File.class.php`. Smarty 2's own `SMARTY_DIR` auto-detection uses `dirname(__FILE__)` so it resolves to `vendor/smarty/smarty/libs/`.

## Goals / Non-Goals

**Goals:** identical rendering behaviour; library managed by Composer; ICMS plugins separated from vendor code.
**Non-Goals:** any Smarty 3/4/5 API, syntax, delimiter or template change (see `smarty-5-upgrade`).

## Decisions

- **Pin `2.6.33`** (exact), not a range, so the only change is the Composer source. Verify the 2.6.31->2.6.33 diff by reading it; add a smoke render test for the custom tags.
- **Move plugins first** to `htdocs/libraries/Icms/View/Smarty/plugins/` so stage 2 only ports code. Files are moved unchanged (git rename).
- **Keep `SMARTY_DIR`**: define it in `constants.php` from the Composer package path (`dirname((new ReflectionClass(Smarty::class))->getFileName()) . '/'`) rather than a hard-coded vendor path; drop the `require_once`.
- **`plugins_dir`** = `[ICMS plugins, SMARTY_DIR . 'plugins']`.

## Risks / Trade-offs

- `vendor/` must be present at runtime and in release archives -> check packaging scripts.
- Smarty 2.6.33 on PHP 8.2-8.4 may emit deprecations the bundled 2.6.31 patches already handled (e.g. dynamic properties, `${}` interpolation) -> diff the bundled copy against upstream before deleting; re-apply any local patches as a Tpl override or document them.
- Compiled templates may embed old paths -> clear `templates_c` on upgrade.

## Migration Plan

Single PR to `pre-3`. Rollback = revert the PR.

## Findings (task 1.1)

- The bundled 2.6.31 differs from upstream mostly by reformatting and method reordering, plus a few PHP 8 hardening edits (`isset()` guards for `get_source`/`quiet`, `$_cache_attrs` initialised, filename length check on resource names).
- Upstream 2.6.31 -> 2.6.33 is tiny: `empty()` instead of `@count()`, an `instanceof StdClass` guard in `foreach`, and the version string.
- Composer blocks every `smarty/smarty` 2.6.x release because of 15 security advisories. Decision: add `config.policy.advisories.ignore` for `smarty/smarty` in `composer.json` with an explanatory comment, as a temporary exception removed by `smarty-5-upgrade`. The bundled copy has the same upstream flaws, so exposure is unchanged.
- PHP 8 fixes missing from stock 2.6.33 are found by running the smoke test with `error_reporting(E_ALL)` on PHP 8.2-8.4 and are carried in `Icms\View\Tpl` overrides (not in vendor code).

## Open Questions

- None blocking.
