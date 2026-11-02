## Why

ImpressCMS pre-3 moved onto Symfony 7.4 LTS as a strangler fig (`migrate-to-symfony-foundation`, `relocate-host-into-trust-path`, `ipf-on-symfony-mimetype-pilot`). That work started from the PSR-4 `Icms\` library (commit `89bc13930`, the parent of `7d22797d0`). The choice of Symfony was made on architectural fit, but never measured against an alternative doing the same job.

**lara-3** builds the same migration again on **Laravel 12**, from the same commit, with the same scope, order and acceptance criteria. Its main product is an honest comparison of the developer experience (DX) of both frameworks on an identical task (`docs/dx-comparison.md`). A polished product is secondary. The result is an Alpha of ImpressCMS pre-3 on Laravel.

The earlier Laravel prototype (branch `copilot/implement-phase-1-strangler-migration`, change `migrate-basic-framework-to-laravel`) is a reference only. It ran legacy as an isolated second application with no shared database connection, session, identity or services. That is explicitly not the goal here: lara-3 shares all four, as the Symfony host does.

## What Changes

- Introduce a Laravel 12 host with `public/index.php` as the single entry point, next to the unchanged `htdocs/` tree. Pointing the web server directly at `htdocs/` keeps working. The relocation into the trust path follows in a separate change, in the same two steps as the Symfony variant.
- Port the legacy bridge (script resolution, asset serving, superglobal hydration, output/header capture, `exit()` handling) to the Laravel router and HTTP kernel. It becomes the lowest-priority fallback for requests that no Laravel route claims, with the same hardening as the Symfony bridge (no early session start, no disclosure outside debug mode).
- Use one Composer root that autoloads the host plus the `Icms\` library and its legacy alias map, so that only one `vendor/` tree is loaded per process.
- Reuse the framework-agnostic library seams of the Symfony variant unchanged, as separate commits: the autoload guard, `Icms\Kernel\Bootstrap`, `Icms\Db\Factory::setConnectionProvider()` and `Icms\Event::setForwarder()`. A seam is changed only when Laravel cannot be served otherwise, and such a change is recorded as a DX finding.
- Share a single database connection: the Laravel database manager owns it, and `Icms\Db\Factory`, `Icms\Db\Legacy\PdoDatabase` and all handlers run on top of its PDO (design L7, to be confirmed).
- Expose ImpressCMS core services and handlers through the Laravel container, as the same instances that `icms::$…` and `icms::handler()` return.
- Share one session and one identity: Laravel routes see the ImpressCMS session through the Laravel session API, and the ImpressCMS user through a Laravel guard, with gates for webmaster, group and module permissions.
- Reuse the `Http` characterization suite and snapshots of the Symfony variant literally as the acceptance criterion.
- Keep a DX log (`docs/dx-comparison.md`) with qualitative findings and measured data per phase.

## Capabilities

The capability names, requirements and `#### Scenario`s are those of `migrate-to-symfony-foundation`, unchanged, so that both changes can be compared line by line. They are written framework-neutrally ("new application"). Only the design and the tasks differ.

### New Capabilities
- `request-ownership`: Decides per request whether the Laravel application or the legacy ImpressCMS application handles it, including health checks and method-not-allowed behaviour.
- `legacy-request-bridge`: Executes legacy ImpressCMS scripts and assets behind the Laravel host with unchanged observable behaviour, safe path resolution and non-disclosing error handling.
- `shared-runtime`: Makes the `Icms\` runtime (database connection, configuration, core services, IPF and module handlers, events) available to Laravel-handled requests while keeping the `icms::` API and `icms_*` aliases backward compatible.
- `session-identity`: Provides one session and one authenticated identity across Laravel-handled and legacy-handled requests, including group-based access control for Laravel routes.

### Modified Capabilities
<!-- None: openspec/specs/ is empty on this branch. -->

## Impact

- **Base**: commit `89bc13930` ("Merge pull request #1701 from fiammybe/ipf-to-psr4"), branch `lara-3`. The Symfony branches are not touched.
- **New code (host)**: root `composer.json`, `public/index.php`, `bootstrap/app.php`, `bootstrap/providers.php`, `app/*` (`App\`), `config/*.php`, `routes/*.php`, `artisan`. This code depends on Laravel; the `Icms\` library does not.
- **Changed code (library, seams only)**: identical to the Symfony variant:
  - `htdocs/libraries/icms.php` and `htdocs/include/common.php` (autoload guard; `common.php` becomes a thin runner over `Icms\Kernel\Bootstrap`)
  - `htdocs/libraries/Icms/Db/Factory.php` (connection provider)
  - `htdocs/libraries/Icms/Event.php` (forwarder hook)
  - new `htdocs/libraries/Icms/Kernel/` (bootstrap stages and exceptions)
- **Dependencies (root)**:
  - added: `laravel/framework` `^12.70` (12.70.2 on 2026-10-27)
  - carried over: the `htdocs/composer.json` requirements (`composer/composer`, HTMLPurifier, SimplePie, WideImage)
  - test only: `phpunit/phpunit` ^11 and the Symfony test tools that the reused `Http` suite needs (`symfony/browser-kit`, `symfony/http-client`, `symfony/process`), at the 7.x versions Laravel 12 resolves to
- **PHP baseline**: `>=8.2 <8.5`, unchanged from `2.1-dev` and equal to the Symfony variant. Laravel 12 accepts PHP 8.2 to 8.5; Laravel 13 needs 8.3 and is out of scope.
- **Support period**: Laravel 12 was released on 2025-02-24. Its bug fixes ended on 2026-08-13 and its security fixes end on **2027-02-24**, less than four months after this change starts. Symfony 7.4 LTS gets bug fixes until November 2028 and security fixes until November 2029. Design L1 records what that means for the comparison.
- **Modules/themes**: No changes are required. IPF classes, Smarty templates, the `icms::` API and `icms_*` class names keep their current behaviour.
- **Out of scope, as follow-up changes** (in the same order as the Symfony variant):
  - `relocate-laravel-host-into-trust-path`: the host in the trust path, the `index.php` preamble, `trust_dist/` and dbversion 50
  - `ipf-on-laravel-mimetype-pilot`: the mimetype admin page served by a Laravel controller
