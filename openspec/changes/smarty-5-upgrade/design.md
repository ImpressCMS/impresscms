## Context

Stage 2 of 2. Starts from Smarty 2.6.33 in Composer with ICMS plugins in `Icms\View\Smarty\plugins` (see `smarty-composer-2x`). Delimiters are `<{ }>`. Templates come from files and from the DB (`db:` resource). Current internals reliance: `Tpl::fetchFromData` uses `smarty_function_eval` and `$_tpl_vars`; `Tpl::touch` uses `_compile_resource` / `_get_compile_path`; the compilers emit `$this->_smarty_include`, `$this->_foreach` and `$this->_tpl_vars`, all gone in Smarty 5.

## Goals / Non-Goals

**Goals:** run on Smarty 5.8 with minimal template churn; legacy aliases for third-party code with deprecation logging.
**Non-Goals:** converting every template to modern syntax; removing the aliases (later major release); changing delimiters.

## Decisions

- **Aliases by compile-time rewrite, not emulation.** Register `includeq`/`foreachq` as tag handlers that delegate to the core `include`/`foreach` compilers and log a deprecation. Bundled templates are migrated off them (41 `includeq` call sites); the alias only serves third-party templates.
- **Legacy method aliases in `Tpl`** via explicit wrapper methods (not `__call` magic) so grep and static analysis can find them; each calls a single `deprecated()` helper that uses `trigger_error(E_USER_DEPRECATED)` plus logging.
- **DB resource as `CustomPlugin`** (`fetch()` returns source and mtime), reusing the existing `smarty_resource_db_tplinfo` logic moved into the class.
- **String templates:** `fetchFromData()` uses the `string:` resource with a child data scope (`createData()`) instead of mutating `$_tpl_vars`.
- **`touch()`** forces a recompile through `clearCompiledTemplate()` plus a compile of the single resource; verify what the tplsets admin needs.
- **`{php}` templates:** `theme_admin.html` (session flash message) and `system_notification_select.html` (token) get their values assigned in PHP (`PageBuilder`/`Theme\Entity`, notification select code); `filter_image.html` builds the filter list in `image-edit.php`.
- **Modifiers:** Smarty 5 only allows registered modifiers and a small allowlist of PHP functions; scan bundled templates (`|substr` etc.) and register the needed ones via `registerPlugin('modifier', ...)`.
- **Security policy:** do not enable Smarty security by default; keep the current trust model.

## Risks / Trade-offs

- Third-party templates using `{php}` or arbitrary PHP modifiers break -> document in release notes; the alias layer covers tags and methods only.
- Variable scoping differs (Smarty 5 data/scope objects, `{include}` scope rules) -> test includes with passed vars in admin and a theme.
- `foreachq` only supported `iteration`/`total`; verify templates using `@iteration`/`@total` map to Smarty 5 `foreach` properties.
- Spec merge order: this change MODIFIES a requirement created in `smarty-composer-2x`; archive that change first.
- Test on PHP 8.2-8.4.

## Migration Plan

One PR to `pre-3`, after stage 1 is merged. The upgrade script wipes `templates_c`. Rollback by revert plus clearing `templates_c`.

## Open Questions

- Which PHP-function modifiers do bundled and common third-party templates actually use? (inventory before coding)
- Should deprecations also surface in the debug bar when `debug_mode` is on?
