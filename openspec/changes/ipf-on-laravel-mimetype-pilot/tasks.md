## 1. Characterization of the legacy mimetype page (L35)

- [ ] 1.1 Take over the test fixtures of the Symfony pilot: three mimetypes with `use_extension` groups, a `mediaadmin` user (system module administrator without `system_admin` for `XOOPS_SYSTEM_MIMETYPES`) and a category administrator (`tests/Support/Install/install-fixtures.php`, `bin/test-db`, `TestSite`)
- [ ] 1.2 Take over `tests/Http/System` (`MimetypePage`, the corpus, the snapshots) and the token option of `SnapshotNormalizer`, adapted to the Laravel file names; take over the docblock fix of `Icms\Ipf\View\Column`
- [ ] 1.3 `MimetypeLegacyCharacterizationTest` with `ICMS_TAKEOVERS_DISABLED=system.mimetype`, skipped against an external server: the snapshots must match legacy before any host code exists
- [ ] 1.4 Tag every test with `#[Group('spec:system-mimetype-admin')]` (and the other new specs) and its `@scenario`

## 2. Switchable takeovers and `fct` from the body (L26)

- [ ] 2.1 `config/icms.php` `takeovers.disabled` from `ICMS_TAKEOVERS_DISABLED`; `App\Routing\Takeovers`
- [ ] 2.2 Route macros `whereInput()` and `takeover()`, checked by `RequestConditionValidator`
- [ ] 2.3 `icms:routes:ownership` reports a disabled takeover with its key
- [ ] 2.4 Tests (spec `request-ownership`): enabled is Laravel, disabled is legacy; `fct` only in the body matches; another `fct` stays legacy

## 3. Admin context and access (L27, L28)

- [ ] 3.1 `App\Admin\SystemAdminContext` and the middleware `icms.admin:<fct>`: hydration of `admin.php`, `ICMS_IN_ADMIN`, the boot stages, `cp_functions.php`, `constants.php`, the language files
- [ ] 3.2 The module-admin check before the `module` stage and the category check from `xoops_version.php`/`icms_version.php`, with the legacy denials
- [ ] 3.3 Tests: webmaster, category administrator, `mediaadmin`, anonymous (spec `admin-page-integration`)

## 4. Admin rendering and redirects (L29, L30)

- [ ] 4.1 `App\Admin\LegacyOutput` and `App\Admin\AdminPageRenderer::render()`/`renderFragment()`
- [ ] 4.2 `App\Admin\LegacyRedirect::to()`: normalisation, same-site check, both `use_custom_redirection` modes, the footer legacy uses
- [ ] 4.3 Tests: admin headers once, output buffer level restored, both redirect modes, external target falls back to the start page

## 5. Module handler access (L34)

- [ ] 5.1 `App\Legacy\ModuleHandlerLocator` and `#[IcmsModuleHandler('<dir>', '<name>')]`
- [ ] 5.2 Test: the same instance as `icms_getModuleHandler('mimetype', 'system')`; an unknown handler throws

## 6. Metadata-driven IPF forms (L31, L32)

- [ ] 6.1 `App\Ipf\Form\ControlMap` (rules per type and control) and `UnsupportedIpfControlException`
- [ ] 6.2 `App\Ipf\Form\IpfForm` and `IpfFormFactory`: field set and visibility, captions as attribute names, top-level binding, the `getVar(..., 'n')`/`setVar()` and `SIMPLE_ARRAY` mapping, permissions
- [ ] 6.3 `App\Ipf\IpfPersister::save()`/`delete()`
- [ ] 6.4 Tests (spec `ipf-forms`): the field set equals the IPF form's; the `selectmulti` options equal `icms_module` `getActive()`; legacy HTML binds every value; required and length violations name the caption; an `upload` control is rejected; handler events fire; permissions are replaced; delete removes them

## 7. Mimetype controller (L25, L26, L33)

- [ ] 7.1 `App\Http\Controllers\System\MimetypeController` with the route of L26 and the legacy `op` parsing
- [ ] 7.2 `list`, `edit`, `save` and `delete` as in `main.php`, with `getSecureForm()`, the token checks and `LegacyRedirect`
- [ ] 7.3 `HostMarker`: `X-Test-Icms-Route` in the `test` environment
- [ ] 7.4 Enable the takeover and run the corpus of group 1 against it; all snapshots match, the forms after token removal
- [ ] 7.5 Token tests: save without a token, a replayed token on delete, a confirmation without a token; nothing is stored or deleted
- [ ] 7.6 Rollback test: with `ICMS_TAKEOVERS_DISABLED=system.mimetype` the legacy snapshots are served again

## 8. Documentation and verification

- [ ] 8.1 "Taking over an IPF admin page" in `docs/laravel-host.md`, and `ICMS_TAKEOVERS_DISABLED` in its configuration table
- [ ] 8.2 DX log: phase 7 baseline (Symfony `05285172c`) and the Laravel figures, with forms and validation as a separate point
- [ ] 8.3 `bin/spec-coverage` without gaps; all suites at routing levels 2 and 3; PHPStan and the deprecation check
