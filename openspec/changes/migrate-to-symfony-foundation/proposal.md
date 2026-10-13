## Why

The `2.1-dev` branch of `impresscms/impresscms` now has a PSR-4 compliant core library, `Icms\` in `htdocs/libraries/Icms/`. A central legacy alias map (`Icms/aliases.php`) and the lazy alias autoloader (`libraries/Autoloader.php`) keep every `icms_*` name working. That library is the foundation for moving ImpressCMS onto a maintained framework without a big-bang rewrite. The architecture has to survive the move: the IPF (`Icms\Ipf\Entity`/`Handler`/`Controller`/`Form`/`View`) with its declarative object model, and the `icms::` service API that modules depend on.

Symfony fits that architecture. Its DI container, EventDispatcher, Form/Validator components and Doctrine DBAL each match a concept that `Icms\` already has. A Laravel-based prototype (branch `copilot/implement-phase-1-strangler-migration`) instead put Eloquent-style conventions next to IPF. It ran legacy ImpressCMS as an isolated second application with no shared database connection, session, identity or services. Its follow-up proposal also targeted files that do not exist. This change replaces that direction: a Symfony host runs on top of the `2.1-dev` `Icms\` library and shares its runtime with it.

## What Changes

- Introduce a Symfony 7.4 LTS HttpKernel host with `public/index.php` as the single entry point, next to the unchanged `htdocs/` tree. Pointing the web server directly at `htdocs/` keeps working.
- Port the legacy bridge design from the Laravel prototype (script resolution, asset serving, superglobal hydration, output/header capture, `exit()` handling) to the Symfony kernel. It becomes the lowest-priority fallback for requests that no Symfony route claims. The bridge is hardened:
  - It no longer starts a PHP session before `Icms\Core\Session` has configured its handler and name.
  - It no longer discloses paths, exception messages or captured output in non-debug failure responses.
- Use one Composer root that autoloads the host plus the `Icms\` library and its legacy alias map, so that only one `vendor/` tree (and one Symfony version) is loaded per process.
- Add small, framework-agnostic seams to the `Icms\` library. Each seam does nothing when the host is absent:
  - an autoload guard in `icms::setup()` and `include/common.php`
  - a connection provider in `Icms\Db\Factory`
  - an event forwarder in `Icms\Event`
  - a staged, headless bootstrap (`Icms\Kernel\Bootstrap`) extracted from `include/common.php`
- Share a single database connection: Doctrine DBAL owns it, and `Icms\Db\Factory`, `Icms\Db\Legacy\PdoDatabase` and all handlers run on top of it.
- Expose ImpressCMS core services and IPF/module handlers through the Symfony container, as the same instances that `icms::$…` and `icms::handler()` return. The `icms::` static API and every `icms_*` alias stay backward compatible.
- Share one session and one identity: Symfony routes see the ImpressCMS session and logged-in user, and can restrict access by ImpressCMS group membership.
- Add an HTTP-level characterization test suite that pins current `2.1-dev` behaviour before the host is introduced.
- Supersede the Laravel prototype and its `migrate-basic-framework-to-laravel` change, which has been removed from `openspec/changes/`.

## Capabilities

### New Capabilities
- `request-ownership`: Decides per request whether the Symfony application or the legacy ImpressCMS application handles it, including health checks and method-not-allowed behaviour.
- `legacy-request-bridge`: Executes legacy ImpressCMS scripts and assets behind the Symfony host with unchanged observable behaviour, safe path resolution and non-disclosing error handling.
- `shared-runtime`: Makes the `Icms\` runtime (database connection, configuration, core services, IPF and module handlers, events) available to Symfony-handled requests while keeping the `icms::` API and `icms_*` aliases backward compatible.
- `session-identity`: Provides one session and one authenticated identity across Symfony-handled and legacy-handled requests, including group-based access control for Symfony routes.

### Modified Capabilities
<!-- None: openspec/specs/ is empty. -->

## Impact

- **Base branch**: `impresscms/impresscms` `2.1-dev` (PSR-4 `Icms\` library in `htdocs/libraries/Icms/`).
- **New code (host)**: `composer.json` at the repository root, `public/index.php`, `src/Kernel.php`, `src/LegacyBridge/*`, `src/Security/*`, `config/*`. This code depends on Symfony; the `Icms\` library does not.
- **Changed code (library, seams only)**:
  - `htdocs/libraries/icms.php` (autoload guard)
  - `htdocs/include/common.php` (autoload guard; it becomes a thin runner over `Icms\Kernel\Bootstrap`)
  - `htdocs/libraries/Icms/Db/Factory.php` (connection provider)
  - `htdocs/libraries/Icms/Event.php` (forwarder hook)
  - new `htdocs/libraries/Icms/Kernel/` (bootstrap stages and exceptions)
- **Dependencies (root)**:
  - added: `symfony/framework-bundle`, `symfony/runtime`, `symfony/security-bundle`, `symfony/twig-bundle` (new routes only), `symfony/monolog-bundle`, `doctrine/dbal` ^4 and `doctrine/doctrine-bundle`, all at `7.4.*` / current stable
  - carried over: the `htdocs/composer.json` requirements
- **PHP baseline**: `>=8.2 <8.5`, unchanged from `2.1-dev`. Symfony 7.4 LTS supports it; Symfony 8 would require PHP 8.4.
- **Modules/themes**: No changes are required. IPF classes, Smarty templates, the `icms::` API and `icms_*` class names keep their current behaviour.
- **Out of scope, as follow-up changes**:
  - `ipf-on-symfony`: IPF handlers as autoconfigured services, `Icms\Db\Criteria` → DBAL QueryBuilder, Form/Validator generation from `initVar` metadata, Twig rendering for `Icms\Ipf\View\*`
  - migrating individual system-module pages to Symfony controllers
  - replacing Smarty
