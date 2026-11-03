## Why

The Laravel host runs from the trust path and shares the ImpressCMS runtime, session and identity, but no real page runs on it yet. The Symfony variant proved its IPF building blocks on the system module's mimetype administration (`admin.php?fct=mimetype`), the smallest pure-IPF page. Doing the same page on Laravel, with the same specs, scenarios and snapshots, tests whether Laravel carries IPF as well, and yields the pieces the other IPF pages and modules need.

The page has two security gaps that the move closes: the edit form carries no CSRF token, and the token of the delete confirmation is never checked.

## What Changes

- Take over all requests to `/modules/system/admin.php?fct=mimetype` (list, create/edit form, save, delete with confirmation) with a Laravel controller, under the same URL. Site configuration can switch the takeover off, which serves the legacy page again.
- **Metadata-driven IPF forms**: fields, options and validation rules derived from an `Icms\Ipf\Entity`'s variable definitions and its handler's permissions, validated with Laravel's validator. The legacy field names are kept, so the rendered IPF form and its POST data stay compatible.
- **Admin page integration**: rendering inside the ImpressCMS admin theme without `exit()`, legacy-compatible redirect messages (redirect page and `use_custom_redirection`), and the access rules and denials of `modules/system/admin.php`.
- Module IPF handlers injected into controllers through the same per-module cache as `icms_getModuleHandler()`.
- Rendering stays legacy: the Smarty template, `Icms\Ipf\View\Table` and the IPF form HTML, so the page matches the Symfony characterization snapshots.
- **BREAKING (security hardening)**: saving and deleting a mimetype require a valid ImpressCMS security token.
- **BREAKING (validation)**: a save that leaves a required field empty, exceeds a maximum length or names an unknown option is rejected; legacy stored it.

## Capabilities

### New Capabilities
- `ipf-forms`: Builds, binds and validates forms from IPF entity metadata (fields, controls, required and length rules, handler permissions), with legacy field names and storage behaviour.
- `admin-page-integration`: Runs framework-handled pages inside the ImpressCMS administration: admin theme rendering, legacy redirect messages, system admin function access.
- `system-mimetype-admin`: The system module's mimetype administration, served by the framework application with unchanged URLs, output and storage, plus token-protected save and delete.

### Modified Capabilities
- `request-ownership`: Each takeover of a legacy URL can be switched off by site configuration.

## Impact

- **Host code**:
  - `app/Ipf/` (form building and validation, persister)
  - `app/Admin/` (admin context and access, renderer, legacy redirects)
  - `app/Http/Controllers/System/MimetypeController.php`
  - module handlers through the existing `icms.handlers` (`module:<dir>/<name>`)
  - route macros for takeover keys and `fct` from query or body, `icms:routes:ownership` reporting disabled takeovers
- **Library (`Icms\`)**: no behavioural change. The docblock fix in `Icms\Ipf\View\Column` from the Symfony pilot is taken over for PHPStan.
- **Legacy files**: `htdocs/modules/system/admin/mimetype/main.php` stays as the fallback.
- **Tests**: the Symfony pilot's characterization corpus and snapshots, the fixture mimetypes and the two extra system administrators of the test site.
- **Dependencies**: none; `illuminate/validation` is part of Laravel.
- **Out of scope**: Blade rendering of IPF forms and tables, upload and image controls, the other five IPF system pages, user-side IPF pages.
