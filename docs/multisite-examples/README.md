# Multisite Example Configuration

This directory contains example configuration files and directory structure for setting up a multisite installation.

## Directory Structure

```
sites/
├── example.com/              # Configuration for example.com
│   └── mainfile.php          # Site-specific config
├── subdomain.example.com/    # Configuration for subdomain.example.com
│   └── mainfile.php          # Site-specific config
└── default/                  # Fallback configuration
    └── mainfile.php          # Default site config
```

## Quick Setup

### 1. Create the sites directory structure

```bash
# Create base directory (outside web root for security)
mkdir -p /var/www/impresscms-trust/sites

# Create directories for each domain
mkdir -p /var/www/impresscms-trust/sites/example.com
mkdir -p /var/www/impresscms-trust/sites/subdomain.example.com
mkdir -p /var/www/impresscms-trust/sites/default
```

### 2. Copy and configure site files

```bash
# Copy the template for each site
cp htdocs/install/templates/mainfile.site.dist.php \
   /var/www/impresscms-trust/sites/example.com/mainfile.php

cp htdocs/install/templates/mainfile.site.dist.php \
   /var/www/impresscms-trust/sites/subdomain.example.com/mainfile.php

cp htdocs/install/templates/mainfile.site.dist.php \
   /var/www/impresscms-trust/sites/default/mainfile.php
```

### 3. Edit each site's mainfile.php

For **example.com**:
```php
define('XOOPS_ROOT_PATH', '/var/www/impresscms/htdocs');
define('XOOPS_TRUST_PATH', '/var/www/impresscms-trust');
define('XOOPS_URL', 'https://example.com');

define('XOOPS_DB_PREFIX', 'icms_site1_');
define('XOOPS_DB_HOST', 'localhost');
define('XOOPS_DB_USER', 'dbuser');
define('XOOPS_DB_PASS', 'dbpass');
define('XOOPS_DB_NAME', 'site1_db');
define('XOOPS_DB_SALT', 'unique_salt_for_site1_' . md5('example.com'));
```

For **subdomain.example.com**:
```php
define('XOOPS_ROOT_PATH', '/var/www/impresscms/htdocs');
define('XOOPS_TRUST_PATH', '/var/www/impresscms-trust');
define('XOOPS_URL', 'https://subdomain.example.com');

define('XOOPS_DB_PREFIX', 'icms_site2_');
define('XOOPS_DB_HOST', 'localhost');
define('XOOPS_DB_USER', 'dbuser');
define('XOOPS_DB_PASS', 'dbpass');
define('XOOPS_DB_NAME', 'site2_db');
define('XOOPS_DB_SALT', 'unique_salt_for_site2_' . md5('subdomain.example.com'));
```

### 4. Enable multisite mode

Create or edit `/var/www/multisite-config.php`:
```php
<?php
define('ICMS_MULTISITE_ROOT_PATH', '/var/www/impresscms-trust');
```

Then configure your web server or PHP to load this file before mainfile.php.

**Option A: Via php.ini or .user.ini**
```ini
auto_prepend_file = /var/www/multisite-config.php
```

**Option B: Via web server configuration (Apache)**
```apache
php_value auto_prepend_file /var/www/multisite-config.php
```

**Option C: Via web server configuration (Nginx)**
```nginx
fastcgi_param PHP_VALUE "auto_prepend_file=/var/www/multisite-config.php";
```

### 5. Set permissions

```bash
chmod 600 /var/www/impresscms-trust/sites/*/mainfile.php
chown www-data:www-data /var/www/impresscms-trust/sites/*/mainfile.php
```

### 6. Run the installer for each site

Visit each domain in your browser:
- https://example.com/install/
- https://subdomain.example.com/install/

The installer will use the database credentials from each site's mainfile.php.

## File Contents

See the example files in this directory for reference configurations.

For full documentation, see: `/docs/multisite-setup.md`
