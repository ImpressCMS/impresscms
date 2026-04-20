# ImpressCMS Legacy → PSR-4 Refactor — Session Handover

Concise context for resuming the `icms_* → Icms\*` migration in a new session.
Load this file at the start and proceed.

## Pinned decisions

- **Target:** all PHP classes under `htdocs/libraries/icms/` (≈209 files).
- **PHP baseline:** 8.2+ (dev machine runs 8.4).
- **PHPUnit:** `^11` (installed).
- **Directories:** physical PascalCase (done in Phase 1).
- **Renames:** `Object` → `Entity` (legacy soft-reserved). Other renames TBD.
- **BC:** hard requirement — every legacy `icms_x_y` name must still resolve,
  via `class_alias()` at the foot of the modern file or via the rename map
  in `htdocs/libraries/Autoloader.php`.
- **Commits:** one commit per phase. Do not push or merge without asking.
- **Branch:** `ipf-to-composer`.

## Current status

| Phase | Scope | Status | Commit |
|-------|-------|--------|--------|
| 0 | PHPUnit + autoloader bridge | done | early |
| 1 | 58 dirs / 291 files to PascalCase | done | early |
| 2 | `Icms\Core\*` (20 files) | **done** | `d5772e661` |
| 3 | `Icms\Ipf\*` (48 files — original ask) | **done** | `bf67e3c5c` |
| 4 | `Icms\Data\*` + `Feeds`, `Messaging`, `Plugins`, `Preload`, `Auth` | **done** | — |
| 5 | `Icms\Db\*` | pending | — |
| 6 | File/Image/Member/Message/Module/… | pending | — |
| 7 | `Icms\Form\*` + form elements | pending | — |
| 8 | everything else under `libraries/icms/` | pending | — |
| 9 | docs / cleanup | pending | — |

Tests: **101 pass / 307 assertions**.

## Refactor pattern (apply per file)

1. First line: `<?php`
2. Second line: `declare(strict_types=1);`  ← must come before anything else executable
3. Docblock (keep original copyright / @package etc.)
4. `namespace Icms\<Namespace>;`
5. Short PascalCase class/interface/trait name
6. Explicit visibility on every property and method (`public`, `protected`, `private`)
7. Add typed params / typed returns / typed properties **where safe**
8. Prefix every global identifier with `\` inside the namespaced file:
   - `\icms::`, `\icms_core_X::`, `\icms_Event::`, `\icms_setCookieVar()`
   - built-ins in type hints / `new`: `\Exception`, `\DirectoryIterator`, etc.
   - `\class_alias(...)`, `\define(...)` when placed after namespace
9. Last line of class file:
   ```php
   \class_alias(ModernName::class, 'icms_x_legacy_name');
   ```
10. Run `php -l <file>` to verify.
11. Add an entry to `htdocs/tests/Unit/<Namespace>/AliasesTest.php`'s provider.

### Minimally-invasive pass (for very large files, >600 LoC)

Skip property typing and detailed param/return types. Do steps 1–9 + alias only.
Revisit later. Done for `DataFilter`, `Textsanitizer`, `Filesystem`.

### Renamed classes

When the modern name isn't a mechanical transform of the legacy name, add an
entry to BOTH maps:

- Production: `htdocs/libraries/Autoloader.php` → `$renameMap`
- Tests:      `htdocs/tests/bootstrap.php`      → `$icmsRenameMap`

Currently:

- `'icms_core_Object'              => 'Icms\\Core\\Entity'`
- `'icms_ipf_Object'               => 'Icms\\Ipf\\Entity'`
- `'icms_ipf_category_Object'      => 'Icms\\Ipf\\Category\\Entity'`
- `'icms_ipf_seo_Object'           => 'Icms\\Ipf\\Seo\\Entity'`
- `'icms_data_avatar_Object'       => 'Icms\\Data\\Avatar\\Entity'`
- `'icms_data_comment_Object'      => 'Icms\\Data\\Comment\\Entity'`
- `'icms_data_file_Object'         => 'Icms\\Data\\File\\Entity'`
- `'icms_data_notification_Object' => 'Icms\\Data\\Notification\\Entity'`
- `'icms_data_page_Object'         => 'Icms\\Data\\Page\\Entity'`
- `'icms_data_privmessage_Object'  => 'Icms\\Data\\Privmessage\\Entity'`
- `'icms_data_urllink_Object'      => 'Icms\\Data\\Urllink\\Entity'`
- `'icms_auth_Object'              => 'Icms\\Auth\\Entity'`
- `'icms_plugins_Object'           => 'Icms\\Plugins\\Entity'`

## Known pitfalls (already hit, don't repeat)

- `declare(strict_types=1)` MUST be before `defined('ICMS_ROOT_PATH') or die();`.
- `namespace` MUST be before any other statement (except `declare`). Move any
  top-level `define('XOBJ_...')` calls to AFTER the namespace line and prefix
  with `\define(...)`.
- Don't use `Set-Content` on PHP files without `(New-Object System.Text.UTF8Encoding $false)` —
  it injects a BOM that breaks `declare(strict_types=1)`.
- Bulk `class X {` regex misses `class X<newline>{`. Always verify with
  `Select-String '^class '` after a scripted rename.
- `new self()` is safe inside a factory method that returns an instance of the
  current class — use it to replace `new icms_core_X()` in `getInstance()` etc.
- **LSP signature checks (PHP 8+ fatals).** Several legacy child methods diverge
  from their parents (extra params, missing return type). These were warnings in
  PHP 7 but are fatal now, and the refactor surfaces them by loading the classes
  in isolation during tests. When extending a Core class from an Ipf subclass,
  the child's param types and return type must be compatible. Fixed in Phase 3:
  `Ipf\Entity::initVar()`, `Ipf\Entity::setErrors()`, `Ipf\View\Tree::fetchObjects()`.
- **Constants defined twice.** Both `Core\Entity.php` and `Ipf\Entity.php`
  define the `XOBJ_DTYPE_*` constants at the top of the file. Guard every
  `\define()` with `if (!\defined('…'))` so loading both sides is warning-free.
- **Composer / bridge double-include race.** When a modern `Icms\Foo\Bar`
  autoload loads a still-legacy file that declares only the flat `icms_foo_bar`
  name, PHP then moves to composer's PSR-4 loader which uses `include` (not
  `include_once`) and re-runs the same file → `Cannot redeclare` fatal. The
  fix is in both `libraries/Autoloader.php` and `tests/bootstrap.php`: after
  `require_once` in the `Icms\` branch, if the modern class wasn't defined but
  the legacy name was, `class_alias($legacyName, $class)` so the autoload chain
  terminates before composer re-includes the file.
- **`icms_loadLanguageFile()` at file top-level.** `Ipf\Entity.php` calls this
  helper at the top of the file, which fails when tests load the class without
  booting the CMS. The test bootstrap ships a no-op shim for it.

## Key files

- `htdocs/composer.json` — PSR-4 prefix `Icms\\` → `libraries/icms/`
- `htdocs/libraries/Autoloader.php` — legacy → modern bridge + rename map
- `htdocs/tests/bootstrap.php` — mirrors the bridge for unit tests
- `htdocs/tests/Support/LegacyAliasAssertions.php` — `assertLegacyAlias()` trait
- `htdocs/tests/Unit/Core/AliasesTest.php` — reference template for future alias tests
- `htdocs/tests/Unit/Ipf/AliasesTest.php` — 48-entry Ipf alias coverage
- `htdocs/phpunit.xml.dist` — test config

## Running things

```powershell
# Lint a single file
cd htdocs
php -l libraries\icms\Core\Foo.php

# Lint all files in a namespace dir
Get-ChildItem libraries\icms\Core\*.php | ForEach-Object { php -l $_.FullName }

# Regenerate autoload + run tests
composer dump-autoload -o
vendor\bin\phpunit --testdox
```

## Next-session kickoff prompt

> Continuing the ImpressCMS PSR-4 refactor. Read `REFACTOR_NOTES.md` in the
> repo root for conventions and pinned decisions. Current branch is
> `ipf-to-composer`. Phases 2 (Core), 3 (Ipf) and 4 (Data + Feeds, Messaging,
> Plugins, Preload, Auth) are committed. Proceed with Phase <N>:
> `Icms\<Namespace>\*`. Use the same pattern as `Icms\Core\Debug` (modern)
> with `class_alias` to `icms_core_debug` (legacy) at the foot. Add
> data-provider entries to `tests/Unit/<Namespace>/AliasesTest.php`. One
> commit at the end of the phase, ask before pushing.
