## 1. Inventory

- [ ] 1.1 List modifiers/functions used by all bundled templates; decide which to register
- [ ] 1.2 List every PHP call site of the Smarty 2 API (`Tpl`, `$xoopsTpl`, `$icmsTpl`) and classify

## 2. Engine switch

- [ ] 2.1 `composer require smarty/smarty:^5.8`; update lock
- [ ] 2.2 Rewrite `Icms\View\Tpl` on `Smarty\Smarty` (delimiters, dirs, compile id, caching, debugging)
- [ ] 2.3 Replace `fetchFromData()` and `touch()` internals
- [ ] 2.4 Remove `SMARTY_DIR`

## 3. Plugins and resource

- [ ] 3.1 Port the `db:` resource to a `CustomPlugin` class
- [ ] 3.2 Port `xoAppUrl`, `xoImgUrl`, `xoPageNav`, `xoInboxCount`, `xoops_link`, `resized_image`
- [ ] 3.3 Register needed modifiers (`substr`, others from 1.1)

## 4. Legacy aliases

- [ ] 4.1 `includeq` -> `include` alias with deprecation notice
- [ ] 4.2 `foreachq` -> `foreach` alias with deprecation notice
- [ ] 4.3 Snake_case method wrappers on `Tpl` with deprecation notice
- [ ] 4.4 Migrate bundled templates off `includeq`/`foreachq`

## 5. Callers and templates

- [ ] 5.1 Update the PHP callers found in 1.2 (tplsets admin, `cp_functions`, `header.php`, `Theme/Entity`, `PageBuilder`, ...)
- [ ] 5.2 Remove `<{php}>` from `theme_admin.html`, `system_notification_select.html`, `filter_image.html`
- [ ] 5.3 Fix `cycle`/`counter`/`section`/`@iteration` usages that behave differently

## 6. Upgrade and verify

- [ ] 6.1 Upgrade step wiping `templates_c`
- [ ] 6.2 Tests: custom tags, aliases (assert deprecation), `db:` resource, `fetchFromData`
- [ ] 6.3 Manual pass: frontend theme, admin (iTheme), module admin, tplsets, image manager, notifications
- [ ] 6.4 Release notes: breaking changes for third-party templates
- [ ] 6.5 Open PR targeting `pre-3` (after stage 1 is merged)
