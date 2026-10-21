## Context

See `proposal.md` (Why). This change builds on the archived `migrate-to-symfony-foundation` and `relocate-host-into-trust-path` changes. These specs apply: `request-ownership`, `legacy-request-bridge`, `shared-runtime`, `session-identity` and `host-deployment`.

What the legacy page does today (`htdocs/modules/system/admin.php` → `admin/mimetype/main.php`):

- **Entry (`admin.php`)**:
  - defines `ICMS_IN_ADMIN`, includes `mainfile.php` and `include/cp_functions.php`
  - loads the language files `system/admin`, `core/moduleabout` and `system/<fct>`
  - filters `fct`/`op` from GET, with `fct` also accepted from POST
  - checks that the user is logged in and `isAdmin(system mid)`; otherwise it calls `redirect_header(ICMS_URL.'/', 3, _NOPERM)`
  - reads `$modversion['category']` from `admin/mimetype/xoops_version.php` (`XOOPS_SYSTEM_MIMETYPES`), and requires webmaster or `system_admin` for that category through `icms_member_groupperm`
  - includes `modules/system/constants.php` and then `main.php`
- **`main.php`** gets the handler through `icms_getModuleHandler('mimetype')`. That function has a static per-module cache, separate from `icms::handler()`. It then switches on `op`:
  - *default*: `icms_cp_header()`, `Icms\Ipf\View\Table` with columns name/extension/types, the intro button `op=mod` and quick search, then `$icmsAdminTpl->display('db:system_adm_mimetype.html')` and `icms_cp_footer()`
  - `mod`: `getForm(...)` (no CSRF token) assigned to the admin template
  - `addmimetype`: `new Icms\Ipf\Controller($handler)->storeFromDefaultForm(...)`, which sets the entity from `$_POST`, calls `handler->insert()` (which runs the events), stores permissions from `$_POST[perm_name]` and ends with `redirect_header(icms_get_page_before_form(), 2, msg)`
  - `del`: `handleObjectDeletion()`. Without `confirm` it renders `Icms\Core\Message::confirm()` (which includes a token) and posts to `SCRIPT_NAME` with **`fct` as a hidden POST field and no query string**. With `confirm` it calls `handler->delete()` and `redirect_header($_POST['redirect_page'], …)`. The token is never validated.
- **Termination**: `redirect_header()` (functions.php) either sends `Location` plus `$_SESSION['redirect_message']` and exits (`use_custom_redirection`), or displays `db:system_redirect.html` in the theme, calls `icms_cp_footer()` and exits.
- **Admin chrome**: `icms_cp_header()` sends no-cache/`X-Frame-Options` headers via `header()`, builds the admin theme (`Icms\View\Theme\Factory`, `theme_admin_set`, `theme_admin.html`) and sets the global `$icmsAdminTpl`. `icms_cp_footer()` calls `$xoTheme->render()`, which fetches the content template, appends the output buffer and echoes the canvas.
- **Table**: `Icms\View\Table` reads `$_GET`/`$_POST`/`$_COOKIE` itself. Its filter, limit and quick-search forms **POST without a token**.
- **Host**: these are available today:
  - `LegacyRuntime::bootTo()`, with stages that end in `module` and `online`
  - `#[RequiresLegacy]`, `#[RequiresLegacyToken]` (validates on every POST)
  - `HandlerLocator` (wraps `icms::handler()` only)
  - `ModulePermissionVoter` (`icms_perm:`)
  - `SendsHeadersVerbatim`/`LegacyResponse` (collecting `header()` output into a response)
  - the test-only takeover route for `fct=users`

## Goals / Non-Goals

**Goals:**
- Serve the mimetype page completely from a Symfony controller. Pages other than the edit form stay identical to their characterization snapshots, and the edit form differs only by the token field.
- Build reusable pieces for the next IPF pages and modules: a metadata-driven Form type, an admin page renderer, legacy redirects, system admin access, module handler access and switchable takeovers.
- Leave the `Icms\` library unchanged.

**Non-Goals:**
- Twig rendering of forms, tables or the admin chrome.
- Upload/image/file controls, rich text editors, date/time controls. They are rejected explicitly (spec `ipf-forms`).
- Migrating the other five IPF system pages. That is a follow-up change that reuses these pieces.
- Removing `admin/mimetype/main.php`. It stays as the fallback.

## Decisions

### D1. Logic in Symfony, rendering legacy
The controller owns request parsing, access control, validation, persistence, token checks and redirects. Output is still produced by the legacy view layer:
- the Smarty template `system_adm_mimetype.html`
- `Icms\Ipf\View\Table` for the list
- the IPF form HTML from `getSecureForm()`
- the admin theme

Parity can then be verified with the existing snapshot machinery, and the new abstractions (form, persistence, access) are proven before the riskier rendering switch.
*Alternative*: Twig for content and chrome at once. Rejected for the pilot because it mixes two risks and breaks snapshot parity, so behaviour could not be verified.

### D2. One route per admin function, op dispatch inside, switchable takeover
`App\Controller\System\MimetypeController` has one route:
- path `/modules/system/admin.php`, methods GET and POST
- condition `(request.query.get('fct') == 'mimetype' or request.request.get('fct') == 'mimetype') and service('icms_takeovers').enabled('system.mimetype')`

POST-only `fct` is required for the delete confirmation. The controller reads `op` with the legacy precedence (GET first, overridden by POST, both filtered as in `main.php`) and dispatches to `list`, `edit`, `save` and `delete` methods.

`App\Routing\Takeovers` is a `routing.condition_service`. It reads `ICMS_TAKEOVERS_DISABLED`, a comma-separated list such as `system.mimetype`, at runtime from `<trust>/.env.local`. Disabling a takeover therefore needs no cache clear, even with `APP_DEBUG=0`. `icms:routes:ownership` reports disabled takeovers.
*Alternatives*: one route per `op`, with conditions. Rejected because it would duplicate the legacy op-parsing rules in expression strings. A container parameter as toggle. Rejected because it is baked in at compile time and would need a cache clear in production.

### D3. `#[SystemAdminFunction('mimetype')]` sets up the admin context
A `kernel.controller` listener, which runs before `RequiresLegacyListener` and replaces it for these controllers:
1. defines `ICMS_IN_ADMIN` (before the `module` stage)
2. runs `LegacyRuntime::bootTo('online')`
3. includes `include/cp_functions.php` and `modules/system/constants.php`
4. loads the language files `system/admin`, `core/moduleabout` and `system/<fct>`
5. checks access (D5)

This mirrors `admin.php` step by step. The steps live in `App\Admin\SystemAdminContext`, so the other system pages reuse them.

### D4. `App\Admin\AdminPageRenderer`: admin chrome without `exit`
`render(string $contentTemplate, \Closure $build): Response`:
1. starts output buffering at a known level and records `headers_list()`
2. calls `icms_cp_header()`
3. passes `$icmsAdminTpl` to `$build`, which builds the table or form and assigns variables
4. calls `$icmsAdminTpl->display($contentTemplate)` into the buffer
5. calls `icms_cp_footer()`, which renders the theme
6. collects the buffer and the headers added since step 1, removes them with `header_remove()`, and returns a `Response` with status 200 and those headers, de-duplicated

This reuses `SendsHeadersVerbatim`. `renderFragment()` covers content that legacy echoes directly, such as the confirmation from `Icms\Core\Message::confirm()`.
*Alternative*: re-implement the chrome in PHP or Twig. Rejected because it would duplicate theme logic (admin menu, preloads `startOutputInit`, theme plugins).

### D5. System admin access: voter plus legacy denial
`App\Admin\SystemAdminFunctionVoter` supports `icms_system_admin:<fct>`. It grants access when the user is logged in and `isAdmin(system mid)`, and either belongs to `ICMS_GROUP_ADMIN` or `checkRight('system_admin', category, groups, system mid)` succeeds. The category is read from `admin/<fct>/xoops_version.php` (or `icms_version.php`), included in an isolated scope as `admin.php` does, and cached per request.

When access is denied, the listener from D3 returns `LegacyRedirect::to(ICMS_URL.'/', 3, _NOPERM)` (D6) instead of Symfony's 403 or a login redirect. This follows the spec `admin-page-integration`, and taken-over pages keep their legacy denial.

### D6. `App\Admin\LegacyRedirect`: redirect_header without `exit`
`LegacyRedirect::to(string $url, int $delay, string $message): Response` re-implements `redirect_header()`:
- the same URL normalisation, and the same-site check (`$allowExternalLink = false`), whose fallback is `ICMS_URL`
- when `use_custom_redirection` is set: a 302 plus `$_SESSION['redirect_message']`
- otherwise: `db:system_redirect.html`, with `url`, `message` and `lang_ifnotreload` assigned, rendered through `AdminPageRenderer`

This fixes an open redirect: legacy passes `$_POST['redirect_page']` from delete straight to `redirect_header()`.
*Alternative*: call `redirect_header()` and catch the exit. Rejected because an `exit` cannot be recovered from inside the kernel.

### D7. Metadata-driven forms: `App\Ipf\Form\IpfEntityType`
`App\Ipf\Form\IpfFormFactory::create(Icms\Ipf\Entity $entity): FormInterface` returns `createNamed('', IpfEntityType::class, $entity, ['csrf_protection' => false, 'allow_extra_fields' => true])`. The root form is unnamed, so the field names equal the legacy names (spec `ipf-forms`). Extra fields are allowed because the legacy HTML carries `op`, `fct`, `icms_page_before_form` and the token.

`IpfEntityType` walks `$entity->getVars()` and applies the same visibility rules as `Icms\Ipf\Form\Base::createElements()` (`displayOnForm`, hidden fields, the key field). It maps types through `App\Ipf\Form\ControlMap`:

| IPF | Symfony field | Constraints |
|---|---|---|
| `XOBJ_DTYPE_TXTBOX` (no control) | `TextType` | `Length(max: maxlength)` |
| `XOBJ_DTYPE_TXTAREA` (no control) | `TextareaType` | — |
| `XOBJ_DTYPE_INT` | `IntegerType` | `Type('integer')` |
| `selectmulti` with `itemHandler`/`method` (or `options`) | `ChoiceType(multiple)` | `Choice(multiple, choices)` |
| key field | `HiddenType` | — |
| required variable | (any) | `NotBlank` |
| each handler permission | `ChoiceType(multiple)` of groups from `icms_member` `getGroupList()`, `mapped: false` | `Choice` |

Any other control or type throws `UnsupportedIpfControlException(var, control)`. `App\Ipf\Form\IpfEntityDataMapper` maps values with `getVar($key, 'e')` and `setVar()`, and converts `XOBJ_DTYPE_SIMPLE_ARRAY` the way `Icms\Ipf\Controller::postDataToObject()` does. Labels come from `$var['form_caption']` and `$var['form_dsc']`.

### D8. `App\Ipf\IpfPersister`: store and delete without the legacy controller
- **`save($form)`**: when the form is valid, calls `handler->insert($entity)` (which runs the handler events, as in legacy). For each permission, it calls `Icms\Ipf\Permission\Handler::saveItem_Permissions($groups, $id, $perm)` with the *bound* groups instead of `$_POST`. It returns success or the HTML errors.
- **`delete($entity)`**: calls `handler->delete()`, which also removes granted permissions.

The upload branch of `storeFromDefaultForm` is deliberately absent (D7 rejects upload controls).

### D9. Tokens only on state-changing operations
`#[RequiresLegacyToken]` validates every POST, and the table's filter and quick-search forms POST without a token. That attribute is therefore not used here. The controller instead validates `icms::$security->check()` in `save` and in confirmed `delete`, and responds to a missing or invalid token with `LegacyRedirect` and the core message `_CORE_TOKENINVALID` plus `icms::$security->getErrors()`. The edit form is built with `getSecureForm()` (`Hiddentoken`) instead of `getForm()`, and the confirmation already includes a token.

### D10. Module handlers: `App\Legacy\ModuleHandlerLocator`
Module IPF handlers come from `icms_getModuleHandler($name, $dir)`, which keeps its own cache apart from `icms::handler()`. `ModuleHandlerLocator::get('system', 'mimetype')` delegates to that function, so any legacy code in the same request shares the instance. It throws `ServiceNotFoundException` when the function returns `false`. A controller argument attribute `#[IcmsModuleHandler('system', 'mimetype')]` injects it. `HandlerLocator` (core handlers) stays unchanged.

### D11. Characterization first, then switch
Before the controller exists, the `Http` suite records legacy snapshots with the takeover disabled. The corpus is:
- the list
- the sorted list
- quick search
- the create form and the edit form
- create POST and update POST
- the delete confirmation and the confirmed delete
- a delete of an unknown id
- anonymous and non-category-admin denial
- the redirect in both `use_custom_redirection` modes

The same tests then run with the takeover enabled. The edit and create form snapshots must equal the legacy snapshot after the normaliser removes the token `<input>`. POSTs without a token have a separate expectation (rejected), because legacy accepted them. Database effects (rows and `group_permission`) are asserted directly, not only through HTML.

## Risks / Trade-offs

- [The legacy view layer reads superglobals and globals (`$icmsAdminTpl`, `$xoTheme`, `$_GET` in `Table`).] → The host already mirrors the request into superglobals. `AdminPageRenderer` runs the legacy calls in the same order as `main.php`, and the snapshots catch deviations.
- [Header handling: `icms_cp_header()` and `icms_cp_footer()` both send `Content-Type`/cache headers, and duplicates or conflicts could leak.] → Headers are collected and de-duplicated by name, with the last value winning. This matches PHP's `header()` replace semantics, which the legacy page effectively gets.
- [Token enforcement could break administrators' existing bookmarks or scripts that POST without a token.] → Intended (proposal: BREAKING). The legacy fallback stays available through `ICMS_TAKEOVERS_DISABLED`.
- [`IpfEntityType` visibility rules can drift from `Icms\Ipf\Form\Base::createElements()`.] → A test builds both for the mimetype entity and compares field names. When the follow-up pages are migrated, that test is extended to each entity.
- [`ICMS_IN_ADMIN` is a process-wide constant.] → One request per process (worker runtimes are unsupported per the foundation), and it is defined only by the admin context listener.

## Migration Plan

1. Record the characterization snapshots for the mimetype page on legacy (D11).
2. Add the generic pieces with unit and integration tests:
   - takeovers (D2)
   - admin context and access (D3, D5)
   - renderer and redirect (D4, D6)
   - module handler locator (D10)
   - Form type and persister (D7, D8)
3. Add `MimetypeController` and enable the takeover. The snapshots must match, and the token tests must pass.
4. Document the pattern in `docs/symfony-host.md` ("Taking over an IPF admin page").

**Rollback**: add `system.mimetype` to `ICMS_TAKEOVERS_DISABLED` in `<trust>/.env.local`. The legacy page then serves again.

**Follow-up**: `migrate-system-ipf-admin-pages` (adsense, autotasks, customtag, rating, userrank) on the same pieces. After that, `ipf-twig-rendering` (Twig for forms and tables).

## Implementation Notes

The characterization snapshots (D11) showed where legacy differs from the assumptions above. The implementation follows legacy:

- **Denials (D5).** Legacy denies before `admin.php`'s own check is reached. For a visitor who does not administer the system module, the module launch in `common.php` calls `redirect_header(ICMS_URL.'/user.php', 3, _NOPERM, false)`, which exits. For an administrator without the category right, `admin.php` sends `Location: ICMS_URL/admin.php`. `SystemAdminContext` therefore checks the module-admin rule after the `siteState` stage and before the `module` stage (the launch would exit), and reproduces both responses. The voter is not used on this path: the authenticator boots the `module` stage, which would exit in admin mode for those visitors.
- **Superglobals (D3).** Legacy code reads `SCRIPT_NAME`/`PHP_SELF` (the module launch finds the system module from it; the confirmation posts to it). The context hydrates the superglobals and working directory of `admin.php` with the bridge's `LegacyRequestHydrator`, and restores them at `kernel.finish_request`. `Request::overrideGlobals()` (first boot) sorts `QUERY_STRING`, which the table echoes into its forms, so the raw query string is put back.
- **Capture (D4, D6).** `App\Admin\LegacyOutput` captures output, status and `headers_list()` and builds a `LegacyResponse`, as the bridge does for scripts. PHP's `header()` replace semantics already leave each header once (the admin footer replaces the header's `Cache-Control` with `private, no-cache`), so no extra de-duplication is needed. The renderer, the redirect and the `admin.php` denial share it.
- **Redirect page (D6).** Legacy renders the redirect page in the site theme, not the admin theme, and closes it with `icms_cp_footer()` (admin) or `footer.php` (before `cp_functions.php` is loaded). `LegacyRedirect` replays `redirect_header()` step by step, with its local variable names, minus the `exit()`s. The same-site check is stricter than legacy: also protocol-relative URLs, user info and look-alike hosts fall back to the site URL.
- **Data mapping (D7).** Values are read with `getVar($key, 'n')`: `'e'` escapes HTML, which would break length validation and double-escape unchanged fields. `XOBJ_DTYPE_SIMPLE_ARRAY` is read from the raw value, because `getVar()` explodes the stored string and fails on the list that `cleanVars()` needs. Forms are bound with `clearMissing: false`, so a field the browser did not send keeps its value, as with `postDataToObject()`; a permission selection that was not sent removes every group, as with `storeAllPermissionsForId()`.
- **Validation.** Legacy `cleanVars()` skips empty values, so it never enforced `required`; an empty required field was stored. The form rejects it (spec `ipf-forms`).
- **Storage.** Text areas pass the legacy HTML input filter, which appends `<!-- filtered with htmlpurifier --><!-- input filtered -->`; the persister keeps that path, so rows are identical to legacy. Legacy runs `afterSave` before `afterInsert`.
- **Tests (D11).** `MimetypeLegacyCharacterizationTest` serves the page with `ICMS_TAKEOVERS_DISABLED=system.mimetype` and records the snapshots; `MimetypeCharacterizationTest` runs the same corpus against the takeover and checks, through the test-only `X-Test-Icms-Route` header, that the takeover answered. The `public/` document root of the foundation no longer exists, so the suites run on `htdocs/` at routing levels 2 and 3.
- **Dependencies.** `symfony/form` and `symfony/validator` 7.4 are added to the host.
