# ImpressCMS Multisite Support

ImpressCMS now supports **domain-based multisite installations**, allowing you to run multiple independent websites from a single shared codebase.

## What is Multisite?

Multisite support enables you to:
- Host multiple websites (domains/subdomains) using one ImpressCMS installation
- Each site has its own database, configuration, and content
- All sites share the same PHP codebase (modules, themes, core)
- Reduces maintenance overhead - one update applies to all sites

## Quick Start

### Prerequisites
- ImpressCMS 2.0 or later
- Multiple domains or subdomains pointing to the same web root
- A directory outside your web root for site-specific configurations (Trust Path)

### Basic Setup (3 Steps)

1. **Enable multisite mode**
   ```php
   // Create /var/www/multisite-config.php
   define('ICMS_MULTISITE_ROOT_PATH', '/var/www/impresscms-trust');
   ```

2. **Create site configurations**
   ```bash
   mkdir -p /var/www/impresscms-trust/sites/example.com
   cp htdocs/install/templates/mainfile.site.dist.php \
      /var/www/impresscms-trust/sites/example.com/mainfile.php
   # Edit mainfile.php with site-specific settings
   ```

3. **Configure PHP to load multisite config**
   ```ini
   # Add to php.ini or .user.ini
   auto_prepend_file = /var/www/multisite-config.php
   ```

That's it! Visit `http://example.com/install/` to set up the site.

## How It Works

The multisite dispatcher in `mainfile.php`:
1. Reads the incoming `HTTP_HOST` header
2. Sanitizes it to prevent security issues
3. Looks for a matching configuration in `{TRUST_PATH}/sites/{domain}/mainfile.php`
4. Loads the site-specific settings (database, URL, paths, etc.)
5. Bootstraps ImpressCMS with the correct configuration

### Hostname Resolution

The dispatcher tries these locations in order:
1. Exact match: `example.com` → `/sites/example.com/mainfile.php`
2. Without www: `www.example.com` → `/sites/example.com/mainfile.php`
3. Default fallback: `/sites/default/mainfile.php`
4. Legacy single-site: `/htdocs/mainfile.dist.php`

## Documentation

- **Full Setup Guide**: [docs/multisite-setup.md](docs/multisite-setup.md)
- **Examples & Quick Start**: [docs/multisite-examples/README.md](docs/multisite-examples/README.md)
- **Site Config Template**: [htdocs/install/templates/mainfile.site.dist.php](htdocs/install/templates/mainfile.site.dist.php)

## Architecture

```
/var/www/
├── impresscms/                    # Shared codebase (one copy)
│   └── htdocs/
│       ├── mainfile.php           # Multisite dispatcher
│       ├── modules/               # Shared modules
│       ├── themes/                # Shared themes
│       └── libraries/             # Shared libraries
│
└── impresscms-trust/              # Trust path (outside web root)
    └── sites/                     # Site-specific configs
        ├── example.com/
        │   └── mainfile.php       # Site 1 database & settings
        ├── blog.example.com/
        │   └── mainfile.php       # Site 2 database & settings
        └── default/
            └── mainfile.php       # Fallback config
```

## Key Features

✅ **Security First**
- HTTP_HOST sanitization prevents directory traversal and injection attacks
- Site configs stored outside web root
- Each site uses unique password salt
- 100% test coverage on sanitization logic

✅ **Flexible Database Configuration**
- Each site can use a separate database
- Or share a database with different table prefixes
- Or mix both approaches

✅ **Backward Compatible**
- Falls back to single-site mode if multisite is not configured
- Existing installations continue to work unchanged

✅ **Easy Maintenance**
- Update core code once, applies to all sites
- Install modules/themes once, available to all sites
- Optional: site-specific cache/uploads directories

## Use Cases

1. **Multi-brand websites**: Run different branded sites with shared infrastructure
2. **Development/staging/production**: Multiple environments from one codebase
3. **Multi-language sites**: Separate domains for different languages
4. **Client hosting**: Host multiple client sites with one installation
5. **Subdomain-based features**: Different subdomains for different purposes

## Upgrading from Single-Site

To migrate an existing single-site installation:

1. Back up everything (files and database)
2. Create the multisite directory structure
3. Copy your existing `mainfile.php` → `/sites/yourdomain.com/mainfile.php`
4. Define `ICMS_MULTISITE_ROOT_PATH`
5. Replace `htdocs/mainfile.php` with the multisite dispatcher

See [docs/multisite-setup.md](docs/multisite-setup.md) for detailed migration instructions.

## Testing

A test suite validates the HTTP_HOST sanitization:

```bash
php tests/test-multisite-dispatcher.php
```

All 25 security and functionality tests pass ✅

## Security

The multisite dispatcher includes comprehensive security measures:
- Strips port numbers from HTTP_HOST
- Converts to lowercase
- Removes all non-alphanumeric characters except dots and dashes
- Prevents directory traversal (`../`)
- Blocks command injection attempts
- Validates hostname format

## Performance

The multisite dispatcher adds minimal overhead:
- One file check per request to locate site config
- No database queries during routing
- Recommended: Enable OPcache to cache PHP files
- Site config files are loaded once per request

## Requirements

- PHP 7.4 to 8.4
- ImpressCMS 2.0 or later
- Ability to configure `auto_prepend_file` or equivalent
- Write access to trust path directory

## Support

- **Documentation**: See `docs/multisite-setup.md`
- **Forums**: https://www.impresscms.org/modules/forum/
- **GitHub Issues**: https://github.com/ImpressCMS/impresscms/issues
- **Test Suite**: `tests/test-multisite-dispatcher.php`

## License

GPL 2.0 - Same as ImpressCMS

---

**Note**: This feature is available in ImpressCMS 2.0 and later. For older versions, you'll need to upgrade first.
