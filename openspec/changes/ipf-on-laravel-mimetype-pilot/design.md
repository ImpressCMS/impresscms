## Context

See `proposal.md` (Why). This change builds on the archived `migrate-to-laravel-foundation` and `relocate-laravel-host-into-trust-path` changes and their specs (`request-ownership`, `legacy-request-bridge`, `shared-runtime`, `session-identity`, `host-deployment`).

The Symfony variant did this pilot in `05285172c` (with `749b01d28` for external servers). Its design (D1–D11) and its implementation notes describe what the legacy page does: the entry steps of `modules/system/admin.php`, the op dispatch of `admin/mimetype/main.php`, `redirect_header()`, the admin chrome, `Icms\Ipf\View\Table`, and where legacy turned out to differ from the first assumptions (the denial order, `SCRIPT_NAME`/`PHP_SELF`, `getVar(..., 'n')`, `SIMPLE_ARRAY`, the HTML input filter, `afterSave` before `afterInsert`). Those facts are about ImpressCMS, not about Symfony, and are not repeated here. This design records how the Laravel host carries them.

What the Laravel host already has:
- `Route::whereQuery()` and `Route::matching(<RouteCondition class>)`, stored as plain data in the route action (route caching is refused)
- `App\Legacy\LegacyRuntime::bootTo(<stage>)` and the middleware `icms.legacy:<stage>` and `icms.token`
- `App\Legacy\HandlerLocator` and the contextual attribute `#[IcmsHandler('<name>')]` for core handlers
- `App\LegacyBridge\LegacyRequestHydrator`, `SendsHeadersVerbatim` and `LegacyResponse`
- the test-only takeover of `fct=users`, and `App\Testing\HostMarker` on `RequestHandled`

## Goals / Non-Goals

**Goals:**
- Serve the mimetype page from a Laravel controller. Every page matches the Symfony pilot's characterization snapshots; the forms differ only by the token field.
- The same reusable pieces as the Symfony pilot, in Laravel's idiom: form building and validation, persister, admin context and access, renderer, redirects, module handler access, switchable takeovers.
- Leave the `Icms\` library unchanged in behaviour.
- Record the DX of each piece, in particular forms and validation, in `docs/dx-comparison.md`.

**Non-Goals:**
- Blade rendering of forms, tables or the admin chrome.
- Upload, image, file, rich text, date and time controls (rejected explicitly, spec `ipf-forms`).
- The other five IPF system pages, and removing `admin/mimetype/main.php`.

## Decisions

### L25. Logic in Laravel, rendering legacy
As Symfony D1. The controller owns request parsing, access, validation, persistence, tokens and redirects. Output comes from the Smarty template `system_adm_mimetype.html`, `Icms\Ipf\View\Table`, the HTML of `getSecureForm()` and the admin theme, so the Symfony snapshots prove parity.

### L26. One route, `fct` from query or body, a takeover key
```php
Route::match(['GET', 'POST'], '/modules/system/admin.php', MimetypeController::class)
    ->whereInput(['fct' => 'mimetype'])
    ->takeover('system.mimetype')
    ->middleware('icms.admin:mimetype')
    ->name('system.mimetype');
```
- `whereInput()` is a new route macro next to `whereQuery()`. It matches a parameter from the query string, or from the POST body when the query lacks it, as `admin.php` reads `fct`. The delete confirmation posts `fct` only in the body.
- `takeover()` stores the key in the route action. `App\Routing\Takeovers` decides with `config('icms.takeovers.disabled')`, which `config/icms.php` reads from `ICMS_TAKEOVERS_DISABLED` (comma-separated) in `<trust>/.env.local`. `RequestConditionValidator` consults it while matching, so a disabled takeover falls through to the legacy bridge. `icms:routes:ownership` names a disabled takeover.
- The controller is invokable and dispatches on `op` with the legacy precedence (GET, overridden by POST, filtered as in `main.php`) to `list`, `edit`, `save` and `delete`.

*Choice (presented)*: `config()` rather than reading `.env.local` on every request. It is Laravel's configuration model, and Larastan rejects `env()` outside `config/`. The host never caches its configuration itself; after a manual `php artisan config:cache`, switching a takeover needs `php artisan config:clear`. Symfony reads the variable at runtime and needs no cache clear. This difference is documented and recorded in the DX log.
*Alternatives*: one route per `op` (duplicates the op rules in route constraints); a `RouteCondition` class per page (a class for every takeover, and the key would not be visible to `icms:routes:ownership`).

### L27. Admin context as route middleware `icms.admin:<fct>`
`App\Http\Middleware\SystemAdminFunction` takes the admin function as parameter and replaces `icms.legacy` for these routes. Through `App\Admin\SystemAdminContext` it:
1. hydrates the superglobals and working directory of `modules/system/admin.php` (`LegacyRequestHydrator`), and restores them after the response
2. defines `ICMS_IN_ADMIN`
3. boots to `siteState`, checks the module-admin rule (L28), then boots to `online`
4. includes `include/cp_functions.php` and `modules/system/constants.php`, and loads the language files `system/admin`, `core/moduleabout` and `system/<fct>`
5. checks the category right (L28)

Middleware is Laravel's place for per-route preconditions that may answer instead of the controller. Symfony uses an attribute and a `kernel.controller` listener for the same steps.

### L28. Access in the middleware, not a Gate
The rules are those of spec `admin-page-integration` and the Symfony implementation notes: a visitor who does not administer the system module gets the redirect of the module launch (`ICMS_URL/user.php`, `_NOPERM`), and an administrator without the category right gets `Location: ICMS_URL/admin.php`. The category comes from `admin/<fct>/xoops_version.php` (or `icms_version.php`), included in an isolated scope.
*Alternative*: a Gate ability `icms_system_admin:<fct>` with `can:` middleware. Rejected for this path. The legacy denial is a redirect, not a 403. Resolving the identity through the guard boots the `module` stage, which exits in admin mode for exactly these visitors. Symfony found the same with its voter. The route therefore does not use the `auth` middleware.

### L29. `App\Admin\AdminPageRenderer` and `App\Admin\LegacyOutput`
`LegacyOutput` captures output, status and the headers added since a starting point, removes them with `header_remove()`, and builds a `LegacyResponse` (`SendsHeadersVerbatim`). PHP's `header()` replace semantics leave each header once. `AdminPageRenderer::render(string $template, Closure $build): Response` runs `icms_cp_header()`, the build closure with `$icmsAdminTpl`, `display($template)` and `icms_cp_footer()` inside it. `renderFragment(Closure $echo)` does the same for content that legacy echoes, such as `Icms\Core\Message::confirm()`.

### L30. `App\Admin\LegacyRedirect`
`LegacyRedirect::to(string $url, int $delay, string $message): Response` replays `redirect_header()` step by step without its `exit()`s: URL normalisation, a same-site check that falls back to `ICMS_URL`, `use_custom_redirection` (302 plus `$_SESSION['redirect_message']`), otherwise `db:system_redirect.html` in the site theme, closed with `icms_cp_footer()` or `footer.php` as legacy does. It also serves the denials of L28.

### L31. Metadata-driven forms on Laravel's validator: `App\Ipf\Form\IpfForm`
*Choice (presented)*: a form object of our own on `Illuminate\Validation\Factory`, not a `FormRequest`.

```php
$form = $this->forms->for($entity)->submit($request);
if ($form->fails()) {
    return LegacyRedirect::to($back, 3, $form->errorsHtml());
}
$this->persister->save($form);
```

- `App\Ipf\Form\IpfFormFactory::for(Icms\Ipf\Entity $entity): IpfForm` walks `$entity->getVars()` with the visibility rules of `Icms\Ipf\Form\Base::createElements()` (`displayOnForm`, hidden fields, the key field) and the handler's permissions.
- `App\Ipf\Form\ControlMap` turns each variable into Laravel rules:

| IPF | Rules |
|---|---|
| required variable | `required`; otherwise `nullable` |
| `XOBJ_DTYPE_TXTBOX` (no control) | `string`, `max:<maxlength>` |
| `XOBJ_DTYPE_TXTAREA` (no control) | `string` |
| `XOBJ_DTYPE_INT` | `integer` |
| `selectmulti` with `itemHandler`/`method` (or `options`) | `array`, and `in:<options>` on `<field>.*` |
| key field | carried as hidden value, not validated as input |
| each handler permission | `array`, `<perm>.*` `in:<group ids of getGroupList()>` |

  Any other control or type throws `App\Ipf\Form\UnsupportedIpfControlException(var, control)`.
- The field captions (`form_caption`) are the validator's attribute names, so messages name the field ("The Extension field is required."). The validator's locale follows the ImpressCMS language when Laravel has a translation for it, otherwise English.
- The submitted values are read from the top level of the request (spec `ipf-forms`), only for the form's fields. Extra fields (`op`, `fct`, `icms_page_before_form`, the token) are ignored.
- On success, `IpfForm` applies the values with `setVar()` and keeps the selected groups per permission. Values come from `getVar($key, 'n')`, `SIMPLE_ARRAY` from the raw value. A field the browser did not send keeps its value; a permission that was not sent selects no group (the Symfony implementation notes).

*Why not a `FormRequest`*: it validates when it is resolved. With one route that dispatches on `op`, a `FormRequest` parameter would validate list and delete requests too, so `save()` would have to resolve it by hand. Its failure path (redirect back with errors in the session) and `authorize()` would both be replaced, by `LegacyRedirect` and by L28. Its rules depend on the entity's metadata at runtime, not on the request class. What remains is the validator, which `IpfForm` uses directly. A `FormRequest` would fit a later Blade form with a route per action.
*DX to record*: Laravel has no form component. Binding, data mapping, hidden values and the field set are host code, where Symfony provides them (`FormType`, `DataMapper`, `createNamed('')`). Validation itself is a rule array.

### L32. `App\Ipf\IpfPersister`
As Symfony D8: `save(IpfForm $form)` calls `handler->insert($entity)` (handler events as in legacy, text areas through the legacy HTML input filter), then `Icms\Ipf\Permission\Handler::saveItem_Permissions($groups, $id, $perm)` with the bound groups; it returns success or the HTML errors. `delete($entity)` calls `handler->delete()`, which removes granted permissions.

### L33. Tokens only on state-changing operations
`icms.token` validates every POST, and the table's filter and quick-search forms post without a token, so it is not used. `save` and the confirmed `delete` call `icms::$security->check()` and answer a missing or invalid token with `LegacyRedirect` and `_CORE_TOKENINVALID` plus `icms::$security->getErrors()`. The edit form is built with `getSecureForm()`.

### L34. Module handlers: `App\Legacy\ModuleHandlerLocator` and `#[IcmsModuleHandler]`
`ModuleHandlerLocator::get('system', 'mimetype')` delegates to `icms_getModuleHandler()`, so legacy code in the same request shares the instance, and throws the PSR-11 `EntryNotFoundException` of `HandlerLocator` when it returns `false`. `#[IcmsModuleHandler('system', 'mimetype')]` is a contextual attribute like `#[IcmsHandler]`, so a controller method receives the handler as a parameter.

### L35. Characterization first, from the Symfony corpus
The fixture mimetypes, the two system administrators and the `tests/Http/System` corpus with its snapshots are taken over from the Symfony pilot, adapted to the Laravel file names. `MimetypeLegacyCharacterizationTest` starts `php -S` with `ICMS_TAKEOVERS_DISABLED=system.mimetype` and must reproduce the snapshots on legacy first. It is skipped against an external server, whose environment the test cannot set (`749b01d28`). `MimetypeCharacterizationTest` runs the same corpus against the takeover. `HostMarker` adds `X-Test-Icms-Route` with the route name in the `test` environment, so the tests can tell that the takeover answered. Database effects (rows, `group_permission`) are asserted directly.

## Risks / Trade-offs

- [The legacy view layer reads superglobals and globals (`$icmsAdminTpl`, `$xoTheme`, `$_GET` in `Table`).] → L27 hydrates the superglobals of `admin.php`; the renderer calls legacy in the order of `main.php`; the snapshots catch deviations.
- [Laravel middleware order: the `icms` group (session, identity) could resolve the user before the module-admin check.] → The route uses only `icms.admin:<fct>`, which boots the legacy session itself; a test covers anonymous and non-category administrators.
- [Validation messages are Laravel's, not ImpressCMS language constants.] → They name the caption, which is what the spec requires. Legacy had no such messages, because it did not validate. Recorded as a DX point.
- [A cached configuration hides a changed `ICMS_TAKEOVERS_DISABLED`.] → Documented with the rollback; the host itself never caches configuration.
- [`IpfForm` visibility rules can drift from `Icms\Ipf\Form\Base::createElements()`.] → A test compares the field names of both for the mimetype entity.
- [`ICMS_IN_ADMIN` is a process-wide constant.] → One request per process, as in the foundation; only the admin middleware defines it.

## Migration Plan

1. Take over the fixtures and the characterization corpus, and prove them on legacy (L35).
2. Add the generic pieces with tests: takeovers and `whereInput()` (L26), admin context and access (L27, L28), renderer and redirect (L29, L30), module handlers (L34), forms and persister (L31, L32).
3. Add `MimetypeController` and enable the takeover; the snapshots and the token tests must pass.
4. Document "Taking over an IPF admin page" in `docs/laravel-host.md`, and the DX in `docs/dx-comparison.md`.

**Rollback**: add `system.mimetype` to `ICMS_TAKEOVERS_DISABLED` in `<trust>/.env.local` (and run `php artisan config:clear` if the configuration was cached). The legacy page serves again.
