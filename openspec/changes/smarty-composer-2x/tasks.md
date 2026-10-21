## 1. Preparation

- [x] 1.1 Diff `htdocs/libraries/smarty/` against upstream 2.6.31 and 2.6.33; record any local patches
- [x] 1.2 Add a smoke test rendering `includeq`, `foreachq`, `xoAppUrl`, `xoImgUrl` and a `db:` template

## 2. Move plugins

- [x] 2.1 `git mv` `icms_plugins/*` to `htdocs/libraries/Icms/View/Smarty/plugins/`
- [x] 2.2 Update `Tpl::__construct` `plugins_dir`

## 3. Switch to Composer

- [x] 3.1 `composer require smarty/smarty:2.6.33`, commit `composer.lock`
- [x] 3.2 Define `SMARTY_DIR` from the Composer package location; drop `require_once` in `Tpl.php`
- [x] 3.3 Delete `htdocs/libraries/smarty/`
- [x] 3.4 Update `.codeclimate.yml` and `.scrutinizer.yml` excludes
- [x] 3.5 Re-apply any local patches found in 1.1

## 4. Verify

- [x] 4.1 Run the PHPUnit suite and smoke test (Library suite green; Integration/Functional need the test DB)
- [x] 4.2 Load frontend and admin pages with an empty `templates_c` (lar.test: frontend, dashboard, tplsets, blocks, mimetype render; no Smarty errors in the log)
- [x] 4.3 Check release packaging includes `vendor/smarty/smarty`
- [ ] 4.4 Open PR targeting `pre-3`
