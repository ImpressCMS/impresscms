# DX comparison: Laravel 12 (lara-3) versus Symfony 7.4 (pre-3)

lara-3 rebuilds the strangler-fig migration of ImpressCMS that was first built on Symfony 7.4 LTS, from the same commit (`89bc13930`), with the same specs, scenarios, snapshots and phase order. This log records how the two frameworks compare on that identical task. It is written while the work happens, not afterwards.

The decisions are numbered `L<n>` in `openspec/changes/migrate-to-laravel-foundation/design.md` (and the follow-up changes); `D<n>` refers to the Symfony designs.

## How this is measured

Per phase:
- **Host code**: files and lines added or changed in the host layer (`app/`, `bootstrap/`, `config/`, `routes/`, `public/`, `artisan` for Laravel; `src/`, `config/`, `public/`, `bin/console` for Symfony), from `git diff --shortstat`.
- **Library seams**: how many `Icms\` seams the phase needed, and whether a reused seam had to change.
- **Framework workarounds**: interventions that work against the framework's intended use (replacing a core class, overriding a protected method, suspending a bootstrapper, re-implementing framework logic). Each one is listed with its reason.
- **Suite time**: wall time of each test suite on the same machine (Windows 11, PHP 8.2.35, MySQL 8.0.36 from Herd).
- **Time to first green `Http` snapshot**: from the first host commit of phase 3 to the first passing snapshot through `public/`.
- **Framework lookups**: how often the framework's documentation or source had to be consulted, and for what.

Symfony figures come from the commits on `pre-3`. Its suite timings and lookups were not recorded at the time, so those columns stay empty for Symfony rather than being estimated.

## Measurements

### Symfony baseline (from `pre-3`)

| Phase | Commit | Host code | Library |
|---|---|---|---|
| 1–2 Test infrastructure, characterization | `86f94663b`, `98555bb46` | — | — |
| 3 Seams | `e01c18bd0`, `a498cc573`, `e3bbb613e`, `ff813c6c3` | — | 4 seams; 8 files, +755 −317 |
| 3 Host and bridge | `3c1d278a1`, `dab4b1755` | 32 files, +1565 −3 | — |
| 4 Headless runtime | `7f6e79b4e` | 6 files, +246 | — |
| 4 Database | `6bc20a328` | 8 files, +273 | — |
| 4 Container | `f706fefe8` | 5 files, +227 | — |
| 4 Events | `467ae9f59` | 9 files, +188 −13 | — |
| 5 Session and identity | `d97c195cc` | 14 files, +544 −2 | — |
| Total, foundation | `89bc13930..94141e7cf` | 59 files, +3006 (47 files in `src/`) | 4 seams |

### lara-3

| Phase | Host code | Library seams (changed?) | Workarounds | Suite time | Lookups |
|---|---|---|---|---|---|
| 0 Plan | — | — | — | — | 9 (see below) |
| 1–2 Test infrastructure, characterization | | | | | |
| 3 Seams | | | | | |
| 3 Host and bridge | | | | | |
| 4 Shared runtime | | | | | |
| 5 Session and identity | | | | | |

Time to first green `Http` snapshot: not reached yet.

## Log

### Phase 0: plan and design (2026-11-02)

**Lookups in the Laravel 12.70.2 source and docs** (9): support policy (`docs/12.x/releases`); `Router::fallback()`; `ResolvesRouteDependencies::transformDependency()`; `HandleExceptions`; the default global and `web` middleware (`Configuration\Middleware`); `Connector::createPdoConnection()` and the `db.connector.*` binding; `Session\Store`; `Http\Kernel::handle()`; `LoadEnvironmentVariables` and `Application::normalizeCachePath()`.

**Findings, per design decision:**

- **L1 Support period.** Laravel 12 lost bug-fix support on 2026-08-13 and loses security support on 2027-02-24, before this work could reach production. Symfony 7.4 LTS is supported until November 2029. Laravel has no LTS; its yearly majors raise the PHP minimum (13 needs 8.3). For a multi-year migration that is a recurring cost the Symfony path does not have. *Unfair to Laravel?* Partly: the assignment fixes Laravel 12 because of the PHP baseline, and Laravel 13 would have about 16 months of bug fixes left.
- **L3 Global helpers.** Laravel loads 175 global functions on every request. None collides with the 871 functions declared under `htdocs/`, but legacy declares its functions without guards, so a third-party module defining for example `e()` or `url()` would fatal. Symfony loads a handful (`dump()`, `dd()`, `trigger_deprecation()`, `u()`/`b()`/`s()` from `symfony/string`), and Laravel loads those too.
- **L4 Fallback route.** `Router::fallback()` is GET only, so it cannot carry a legacy POST. An `any` route marked with `->fallback()` works. Laravel, like Symfony, does not produce 405 when a catch-all accepts every method; both need the same re-match workaround. Laravel has no route condition on the query string. That is a new workaround (a route validator plus macro) that Symfony did not need (`condition:` expressions).
- **L5 File scope.** Laravel's front controller is a plain script, so a file-scope `require` needs no custom runtime. Symfony needed a custom `symfony/runtime` runner. Against that, Laravel has no `kernel.response` event (post-processing is middleware), so the real response bypasses it, and `HandleExceptions` (`error_reporting(-1)`, errors become exceptions) must be suspended around legacy scripts. The default input middleware (`TrimStrings`, `ConvertEmptyStringsToNull`) would change what a takeover controller sees, so they are removed.
- **L7 Database.** Laravel has two documented extension points that fit exactly: a container binding per driver connector (`db.connector.mysql`) and `Connection::resolverFor()`. Symfony needed a custom DBAL driver and middleware for the same result. Making legacy the owner would be even smaller in Laravel (`DB::extend()` with a PDO closure, no seam), but it would break the like-for-like comparison.
- **L8 Container.** Laravel's contextual attributes (`ContextualAttribute::resolve()`) are a direct equivalent of `#[IcmsHandler]`. The route dispatcher resolves them before loading the parameter's class, which avoids the problem the Symfony variant solved by extending `#[Autowire]`.
- **L10 Session.** Laravel's session layer never uses PHP's native session, and there is no equivalent of Symfony's `php_bridge` storage. The bridge needs a custom `Store`, a replacement for `StartSession`, and a custom middleware group instead of `web`, because `web` also encrypts cookies and validates Laravel's CSRF token. This is the largest planned workaround.
- **L11 Identity.** `Auth::viaRequest()` plus `Gate::before()` cover the ImpressCMS user, groups and module permissions with less ceremony than a Symfony authenticator and voter. Laravel has no role concept, so `ROLE_*` became abilities.
- **L16 Tests.** Orchestra Testbench is meant for packages, and it would not load lara-3's `bootstrap/app.php`. Laravel's own `Foundation\Testing\TestCase` is the right tool for an application. As with `KernelBrowser`, Laravel's test client never reaches the file-scope runner, so the `Http` suite stays the only way to test legacy execution.

## Where the comparison is unfair

- **The design existed.** The Symfony variant discovered the ImpressCMS constraints (file scope, `exit()`, `ERRMODE_SILENT`, `IConnection`, headless denials, `QUERY_STRING` sorting, header semantics). lara-3 starts from its specs, implementation notes and tests, so Laravel is spared that discovery. Raw effort figures therefore favour Laravel. The comparison is fair on the questions "how much host code" and "how much fighting the framework", less so on "how long".
- **Reused tests.** The `Http` suite uses Symfony BrowserKit and Process. They are framework-agnostic test tools, and Laravel itself depends on Symfony components, but they are more familiar on the Symfony side.
- **Same author and assistant.** Both variants are written by the same people with the same habits, which may fit one framework's style better.
- **Version choice.** Laravel 12 is fixed by the assignment while it is already out of bug-fix support; Symfony 7.4 is a current LTS.

## Conclusion

Written at the end of the IPF pilot, per criterion, without a preference fixed in advance.
