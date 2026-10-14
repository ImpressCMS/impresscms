## Why

The Symfony foundation is in place: shared runtime, session and identity, container access to handlers, and routes that can take over legacy URLs. Not a single real page runs on it yet, and the way IPF maps onto Symfony is still only a plan. The system module's mimetype administration (`admin.php?fct=mimetype`) is the smallest pure-IPF page. It uses an IPF entity with metadata from `quickInitVar()`, a `Table` list, a `getForm()` edit form, `Controller::storeFromDefaultForm()`, `handleObjectDeletion()` and handler permissions. Five other system pages (adsense, autotasks, customtag, rating, userrank) follow the same pattern, and so do many third-party IPF modules. Moving this one page end to end on top of Symfony yields the reusable IPF-on-Symfony building blocks from a real consumer, instead of designing them in the abstract.

The page also has two security gaps worth closing during the move. The edit form carries no CSRF token. The delete confirmation does carry one, but the token is never checked.

## What Changes

- Take over all requests to `/modules/system/admin.php?fct=mimetype`: list, create/edit form, save and delete with confirmation. A Symfony controller handles them, and the URL stays the same. The takeover can be switched off by configuration, which sends the page back to legacy without a deploy.
- Introduce **metadata-driven IPF forms**: a generic Symfony Form type that derives fields, options and validation constraints from an `Icms\Ipf\Entity`'s variable definitions (`initVar`/`quickInitVar`, controls). It also covers the handler's permission fields. The form binds to the IPF entity and keeps the legacy field names, so the existing rendered form and its POST data stay compatible.
- Introduce **admin page integration**:
  - Symfony controllers can render content inside the ImpressCMS admin theme (cp header/footer, admin menu, Smarty admin templates) without legacy code calling `exit()`.
  - The legacy redirect-with-message behaviour is reproduced, including both the redirect page and the `use_custom_redirection` mode.
  - Access to a system admin function is checked with the same rules and denial behaviour as `modules/system/admin.php`.
- Rendering stays legacy for now: the mimetype page keeps its Smarty template, its `Icms\Ipf\View\Table` list and the existing IPF form HTML, so the page looks and behaves the same.
- **BREAKING (security hardening)**: saving and deleting a mimetype now require a valid ImpressCMS security token. A save or delete without one is rejected.

## Capabilities

### New Capabilities
- `ipf-forms`: Builds, binds and validates forms from IPF entity metadata (fields, controls, required/length rules, handler permissions) while keeping legacy field names and storage behaviour.
- `admin-page-integration`: Lets framework-handled pages run inside the ImpressCMS administration. It covers admin theme rendering, legacy-compatible redirect messages and system admin function access control.
- `system-mimetype-admin`: The mimetype administration page of the system module, served by the framework application with unchanged URLs, output and storage behaviour, plus token-protected save and delete.

### Modified Capabilities
- `request-ownership`: Adds that individual URL takeovers can be switched off by configuration, so that a taken-over page falls back to legacy.

## Impact

- **New code (host)**:
  - `src/Ipf/Form/*` (Form type, data mapper, metadata-to-constraint mapping)
  - `src/Admin/*` (admin page renderer, legacy redirect responses, system admin function access)
  - `src/Controller/System/MimetypeController.php`
  - a takeover registry used in route conditions
- **Library (`Icms\`)**: no changes are expected. If one turns out to be needed, it stays an additive, inert seam, as in the foundation.
- **Legacy files**: `htdocs/modules/system/admin/mimetype/main.php` stays in place as the fallback while the takeover is switched off. It is not deleted in this change.
- **Behaviour**: unchanged for list, form and redirects (pinned by characterization snapshots). Changed only for token validation on save and delete.
- **Out of scope**:
  - Twig rendering of IPF forms and tables
  - file upload and image controls
  - the other five IPF system pages
  - IPF handlers as autoconfigured services (the existing `HandlerLocator` is used)
  - user-side IPF pages
