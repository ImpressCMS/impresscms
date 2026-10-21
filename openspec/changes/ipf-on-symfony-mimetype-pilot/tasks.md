## 1. Characterization of the legacy mimetype page (D11)

- [x] 1.1 Extend the test fixture with three mimetypes (with `use_extension` groups) and a `mediaadmin` user: an administrator of the system module without `system_admin` for `XOOPS_SYSTEM_MIMETYPES`
- [x] 1.2 Add `tests/Http/System/MimetypeCharacterizationTest.php` with snapshots of the list, the sorted list (`mimetype_sortsel=extension`, `mimetype_ordersel=DESC`), quick search, the create form and the edit form
- [x] 1.3 Add snapshots for create POST, update POST, the delete confirmation, the confirmed delete and a delete of an unknown id. Assert the database effects (mimetype rows and `group_permission` rows) directly
- [x] 1.4 Add the denial cases: anonymous and `mediaadmin`. Add the redirect page in both modes (`use_custom_redirection` 0 and 1)
- [x] 1.5 Extend `SnapshotNormalizer` with an option that removes the security token `<input>`, used for form comparisons
- [x] 1.6 Record and commit the snapshots against legacy, and tag every test with `#[Group('spec:system-mimetype-admin')]` and its `@scenario`

## 2. Switchable takeovers (D2)

- [x] 2.1 Implement `App\Routing\Takeovers` as a routing condition service that reads `ICMS_TAKEOVERS_DISABLED` (comma-separated) at runtime
- [x] 2.2 Make `icms:routes:ownership` report a disabled takeover, naming the route and the takeover key
- [x] 2.3 Tests: a takeover route with an enabled key is owned by Symfony, and with the key disabled the same URL is legacy, without a cache clear (spec `request-ownership`)
- [x] 2.4 Document `ICMS_TAKEOVERS_DISABLED` in the configuration table of `docs/symfony-host.md`

## 3. Admin context and access (D3, D5)

- [x] 3.1 Implement `#[SystemAdminFunction('<fct>')]` and its `kernel.controller` listener, which runs before `RequiresLegacyListener`. It defines `ICMS_IN_ADMIN`, boots to `online`, includes `cp_functions.php` and `modules/system/constants.php`, and loads the language files
- [x] 3.2 Implement `App\Admin\SystemAdminFunctionVoter` (`icms_system_admin:<fct>`), which reads the category from `xoops_version.php`/`icms_version.php` in an isolated scope
- [x] 3.3 On denial, return the legacy denial (`LegacyRedirect` to `ICMS_URL/` with `_NOPERM`)
- [x] 3.4 Integration tests for the voter: webmaster, category admin, `mediaadmin`, anonymous

## 4. Admin rendering and redirects (D4, D6)

- [x] 4.1 Implement `App\Admin\AdminPageRenderer::render()` and `renderFragment()`, with buffer-level bookkeeping and header collection and de-duplication through `SendsHeadersVerbatim`
- [x] 4.2 Implement `App\Admin\LegacyRedirect::to()`: URL normalisation, a same-site check with `ICMS_URL` as fallback, `use_custom_redirection` (302 plus `$_SESSION['redirect_message']`), and otherwise `db:system_redirect.html` through the renderer
- [x] 4.3 Tests: the admin headers appear exactly once; the output buffer level is restored; the redirect works in both modes; an external target falls back to the start page (spec `admin-page-integration`)

## 5. Module handler access (D10)

- [x] 5.1 Implement `App\Legacy\ModuleHandlerLocator` on top of `icms_getModuleHandler()`, and the `#[IcmsModuleHandler('<dir>', '<name>')]` argument resolver
- [x] 5.2 Test: the instance is the same as `icms_getModuleHandler('mimetype', 'system')` in the same request, and an unknown handler throws `ServiceNotFoundException`

## 6. Metadata-driven IPF forms (D7)

- [x] 6.1 Implement `App\Ipf\Form\ControlMap` with the mappings of D7 and `UnsupportedIpfControlException`
- [x] 6.2 Implement `App\Ipf\Form\IpfEntityType` (the visibility rules of `Icms\Ipf\Form\Base::createElements()`, labels from `form_caption`/`form_dsc`, permission fields) and `IpfEntityDataMapper` (the `getVar(..., 'e')`/`setVar()` mapping and the `SIMPLE_ARRAY` conversion of `postDataToObject()`)
- [x] 6.3 Implement `App\Ipf\Form\IpfFormFactory::create()`: an unnamed root form, no Symfony CSRF, extra fields allowed
- [x] 6.4 Tests (spec `ipf-forms`):
  - the field set equals the IPF form's for the mimetype entity
  - the `selectmulti` options equal `icms_module` `getActive()`
  - legacy HTML posted to the form binds every value
  - required and length violations name the field caption
  - an `upload` control is rejected
- [x] 6.5 Implement `App\Ipf\IpfPersister::save()`/`delete()` (D8), and test them: handler events fire, permissions are replaced by the bound groups, and delete removes the permissions

## 7. Mimetype controller (D1, D2, D9)

- [x] 7.1 Implement `App\Controller\System\MimetypeController` with the takeover route of D2, legacy `op` parsing, and the actions `list`, `edit`, `save` and `delete`
- [x] 7.2 `list`: build `Icms\Ipf\View\Table` with the columns, intro button and quick search of `main.php`, and render `db:system_adm_mimetype.html`
- [x] 7.3 `edit`: `getSecureForm()` with the legacy captions and titles for create and edit, assigned and rendered as in `main.php`
- [x] 7.4 `save`: validate the token, bind and validate through `IpfFormFactory`, store through `IpfPersister`, then `LegacyRedirect` with `_CO_ICMS_MIMETYPE_CREATED`/`_MODIFIED` to `icms_get_page_before_form()`. On errors, redirect with `_CO_ICMS_SAVE_ERROR` plus the errors, as in legacy
- [x] 7.5 `delete`: "not selected" for an unknown id; the confirmation through `renderFragment()` with `Icms\Core\Message::confirm()` (including `fct` as a hidden field); for a confirmed delete, validate the token, delete, then `LegacyRedirect` with `_CO_ICMS_DELETE_SUCCESS`/`_CO_ICMS_DELETE_ERROR` to a same-site `redirect_page`
- [x] 7.6 Enable the takeover and run the characterization tests from group 1 against it. All snapshots must match, with the forms compared after token removal
- [x] 7.7 Add the token tests: save without a token, a replayed token on delete, and a delete confirmation without a token. Each must store or delete nothing
- [x] 7.8 Rollback test: with `ICMS_TAKEOVERS_DISABLED=system.mimetype`, the legacy snapshots are served again

## 8. Documentation and verification

- [x] 8.1 Add "Taking over an IPF admin page" to `docs/symfony-host.md`: attribute, route, renderer, redirect, form factory, persister, tokens, rollback
- [x] 8.2 Run `bin/spec-coverage` with no gaps for `ipf-forms`, `admin-page-integration`, `system-mimetype-admin` and the new `request-ownership` requirement
- [x] 8.3 Run all suites on both document roots and at routing levels 2 and 3. Run PHPStan on `src/` with no new errors, and check deprecations
