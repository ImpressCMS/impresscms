# ImpressCMS - Copilot Coding Agent Instructions

## Repository Overview

ImpressCMS is a community-developed PHP Content Management System (CMS) focused on speed, multi-language support, and security. It is built on PHP (requires 7.4+, supports up to 8.4) and MySQL/MariaDB.

- **Repository Size**: ~82MB with ~1,535 PHP files
- **Language**: PHP (primary), JavaScript, CSS
- **Framework**: Custom MVC-style architecture with modules, plugins, and themes
- **Database**: MySQL/MariaDB (PDO connection only)
- **License**: GPL 2.0

## Project Structure

```
/
├── .github/workflows/     # GitHub Actions (apigen.yml, generate-diff-zip.yml)
├── docs/                  # Documentation (changelog, license)
├── extras/plugins/        # Extra plugin components
├── htdocs/                # Web root - MAIN APPLICATION CODE
│   ├── editors/           # Rich text editors (CKEditor, etc.)
│   ├── images/            # System images and icons
│   ├── include/           # Core includes and functions
│   │   ├── common.php     # Main bootstrap file
│   │   ├── constants.php  # System constants
│   │   ├── functions.php  # Helper functions
│   │   └── version.php    # Version info (ICMS_VERSION_NAME, BUILD)
│   ├── install/           # Installation wizard
│   ├── language/          # Language files (english, etc.)
│   ├── libraries/         # Core libraries
│   │   ├── icms/          # ImpressCMS core classes
│   │   ├── icms.php       # Main kernel/services manager class
│   │   ├── smarty/        # Template engine
│   │   ├── phpmailer/     # Email library
│   │   └── [others]       # Various libraries
│   ├── modules/           # Installable modules
│   │   └── system/        # Core system module
│   │       ├── icms_version.php  # Module version config
│   │       └── include/update.php # Database upgrades
│   ├── plugins/           # System plugins
│   │   └── preloads/      # Preload event handlers
│   ├── themes/            # Site themes (iTheme, reflex)
│   ├── index.php          # Site entry point
│   └── mainfile.php       # Configuration bootstrap
└── upgrade/               # Version upgrade scripts
```
Many subfolders in the `/htdocs/libraries` folder are third-party/vendor libraries (for example `smarty/`, `phpmailer/`, and similar), and those should generally not be modified except when upgrading or fixing vendored code. Some subdirectories, such as `icms/`, `image-editor/`, and `paginationstyles/`, contain ImpressCMS core code and are part of the project and may be changed as needed.
## Key Files to Know

| File | Purpose |
|------|---------|
| `htdocs/mainfile.php` | Site configuration (redirects to install if not configured) |
| `htdocs/include/common.php` | Core bootstrap - loads kernel and services |
| `htdocs/include/version.php` | Version constants (ICMS_VERSION_NAME, ICMS_VERSION_BUILD) |
| `htdocs/libraries/icms.php` | Main kernel class - services and autoloading |
| `htdocs/modules/system/icms_version.php` | System module version definition |
| `htdocs/modules/system/include/update.php` | Database migration scripts |

## Development Guidelines

### Code Style
- new and updated code should follow PSR-12 styling rules

### PHP Standards
- **Minimum PHP**: 7.4
- **Maximum PHP**: 8.4
- Use `icms::handler()` for service handlers
- Use `icms::$module`, `icms::$user`, `icms::$db` for global services
- Language constants are defined with `define()` in `htdocs/language/*/` files

### Important Patterns
```php
// Get a handler
$handler = icms::handler('icms_member');

// Access global services
icms::$db      // Database connection (PDO)
icms::$user    // Current user object
icms::$module  // Current module
icms::$config  // Configuration service
```

## Validation and Quality Checks

### Code Quality Tools (External CI)
The repository uses external services for code analysis. These run automatically on PR:
- **Code Climate**: Style checking, complexity analysis (`.codeclimate.yml`)
- **Scrutinizer CI**: PHP static analysis, duplication detection (`.scrutinizer.yml`)

### Manual Validation Steps
1. **Syntax Check**: `php -l htdocs/path/to/file.php`
2. **Test in Browser**: Access the site via web server after changes
3. **Check Admin Panel**: Admin changes require testing at `/admin.php`

### No Local Test Suite
There is **no PHPUnit or automated test suite** in this repository. Validation is done through:
- PHP syntax checking
- Manual browser testing
- External CI services on pull requests

## Making Changes

### Module Changes
- Module configs are in `htdocs/modules/[module]/icms_version.php`
- Database migrations go in `htdocs/modules/[module]/include/update.php`
- Admin pages are in `htdocs/modules/[module]/admin/`

### Language/Translation Changes
- English is the base language at `htdocs/language/english/`
- Translations managed via Crowdin (`crowdin.yml`)
- Define constants with `define('_CONSTANT_NAME', 'value');`

### Theme Changes
- Themes are in `htdocs/themes/`
- Use Smarty template syntax (`.html` files)

### Adding New Features
1. Check if a preload event exists in `htdocs/plugins/preloads/`
2. Consider adding as a module if feature is substantial
3. Update version constants in `htdocs/include/version.php` if needed

## Common Gotchas

1. **mainfile.php**: The default mainfile.php redirects to install wizard. A configured site will have database credentials here.

2. **Database migrations**: Always increment `ICMS_SYSTEM_DBVERSION` in `htdocs/include/version.php` when adding DB changes.

3. **Global variables**: The codebase uses `icms::$module` (not `$icmsModule` which is deprecated).

4. **Libraries**: Most libraries in `htdocs/libraries/` are bundled (not via Composer). Check specific library versions before updating.

5. **Excluded paths**: Code Climate and Scrutinizer exclude many paths (see config files). Changes to excluded paths won't trigger CI issues.

## Branch Strategy
- Active development branches: `MAJOR.MINOR.x` format (e.g., `2.0.x`)
- Branch from the most similar version branch for fixes/features

## Trust These Instructions
These instructions are accurate for the current state of the repository. Only search for additional information if you encounter errors or find the instructions incomplete.
