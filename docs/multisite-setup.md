# ImpressCMS Multisite Configuration Guide

## Overview

ImpressCMS now supports domain-based multisite installations, allowing you to run multiple independent websites from a single codebase. Each site can have its own:

- Domain name or subdomain
- Database (or share a database with different table prefixes)
- Configuration settings
- User base
- Content

## Architecture

The multisite system uses a dispatcher in `mainfile.php` that:

1. Reads the incoming `HTTP_HOST` header
2. Sanitizes the hostname for security
3. Maps it to a configuration file in `{TRUST_PATH}/sites/{domain}/mainfile.php`
4. Loads the site-specific configuration
5. Bootstraps ImpressCMS with the correct settings

## Directory Structure

```
/var/www/
├── impresscms/                    # Shared codebase
│   └── htdocs/
│       ├── mainfile.php           # Multisite dispatcher
│       ├── include/
│       ├── modules/
│       └── themes/
│
└── impresscms-trust/              # Trust path (outside web root)
    └── sites/                     # Multisite configurations
        ├── example.com/
        │   ├── mainfile.php       # Site 1 config
        │   ├── cache/             # Optional: site-specific cache
        │   ├── templates_c/       # Optional: site-specific compiled templates
        │   └── uploads/           # Optional: site-specific uploads
        │
        ├── subdomain.example.com/
        │   └── mainfile.php       # Site 2 config
        │
        └── default/
            └── mainfile.php       # Fallback configuration
```

## Setup Instructions

### Step 1: Enable Multisite Mode

Before the multisite dispatcher can work, you need to define the multisite root path. Create or edit a file outside your web root (e.g., `/var/www/multisite-config.php`) with:

```php
<?php
// Define the multisite root path
// This should point to the directory containing the 'sites' folder
define('ICMS_MULTISITE_ROOT_PATH', '/var/www/impresscms-trust');
?>
```

Then, in your web server configuration or in a file loaded before `mainfile.php`, include this configuration:

```php
require_once '/var/www/multisite-config.php';
```

**Alternative:** You can also define this constant in your PHP configuration (`php.ini` or via `auto_prepend_file`).

### Step 2: Create the Sites Directory Structure

```bash
# Create the sites directory in your trust path
mkdir -p /var/www/impresscms-trust/sites

# Create a directory for each domain
mkdir -p /var/www/impresscms-trust/sites/example.com
mkdir -p /var/www/impresscms-trust/sites/subdomain.example.com

# Optional: Create site-specific data directories
mkdir -p /var/www/impresscms-trust/sites/example.com/cache
mkdir -p /var/www/impresscms-trust/sites/example.com/templates_c
mkdir -p /var/www/impresscms-trust/sites/example.com/uploads

# Set proper permissions
chmod 755 /var/www/impresscms-trust/sites
chmod 755 /var/www/impresscms-trust/sites/example.com
chmod 777 /var/www/impresscms-trust/sites/example.com/cache
chmod 777 /var/www/impresscms-trust/sites/example.com/templates_c
chmod 777 /var/www/impresscms-trust/sites/example.com/uploads
```

### Step 3: Configure Each Site

For each site, copy the template and configure it:

```bash
# Copy the site configuration template
cp /var/www/impresscms/htdocs/install/templates/mainfile.site.dist.php \
   /var/www/impresscms-trust/sites/example.com/mainfile.php

# Edit the configuration
nano /var/www/impresscms-trust/sites/example.com/mainfile.php
```

Edit the following settings in each site's `mainfile.php`:

```php
// Physical path to shared htdocs
define('XOOPS_ROOT_PATH', '/var/www/impresscms/htdocs');

// Trust path
define('XOOPS_TRUST_PATH', '/var/www/impresscms-trust');

// Site-specific URL (IMPORTANT: Different for each site!)
define('XOOPS_URL', 'https://example.com');

// Database settings (can be the same or different for each site)
define('XOOPS_DB_TYPE', 'mysql');
define('XOOPS_DB_CHARSET', 'utf8');
define('XOOPS_DB_PREFIX', 'icms_site1_');  // Different prefix per site if sharing DB
define('XOOPS_DB_HOST', 'localhost');
define('XOOPS_DB_USER', 'your_db_user');
define('XOOPS_DB_PASS', 'your_db_password');
define('XOOPS_DB_NAME', 'your_database_name');

// IMPORTANT: Use a unique salt for each site!
define('XOOPS_DB_SALT', 'unique_random_salt_for_this_site_' . md5('example.com'));
```

### Step 4: Create a Default/Fallback Site (Optional)

Create a default configuration that will be used when no specific site configuration is found:

```bash
mkdir -p /var/www/impresscms-trust/sites/default
cp /var/www/impresscms/htdocs/install/templates/mainfile.site.dist.php \
   /var/www/impresscms-trust/sites/default/mainfile.php
# Configure as needed
```

### Step 5: Configure Web Server

#### Apache

Create or update your virtual host configuration:

```apache
<VirtualHost *:80>
    ServerName example.com
    ServerAlias www.example.com
    DocumentRoot /var/www/impresscms/htdocs
    
    <Directory /var/www/impresscms/htdocs>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

<VirtualHost *:80>
    ServerName subdomain.example.com
    DocumentRoot /var/www/impresscms/htdocs
    
    <Directory /var/www/impresscms/htdocs>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx

```nginx
server {
    listen 80;
    server_name example.com www.example.com;
    root /var/www/impresscms/htdocs;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}

server {
    listen 80;
    server_name subdomain.example.com;
    root /var/www/impresscms/htdocs;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### Step 6: Install Each Site

For each site, run the ImpressCMS installer by visiting `http://example.com/install/` in your browser. The installer will use the database settings from the site-specific configuration file.

## Configuration Options

### Shared vs. Site-Specific Resources

#### Shared Database with Different Prefixes

Multiple sites can share one database by using different table prefixes:

```php
// Site 1
define('XOOPS_DB_PREFIX', 'icms_site1_');

// Site 2
define('XOOPS_DB_PREFIX', 'icms_site2_');
```

#### Separate Databases

Each site can use its own database:

```php
// Site 1
define('XOOPS_DB_NAME', 'site1_db');

// Site 2
define('XOOPS_DB_NAME', 'site2_db');
```

#### Site-Specific Data Directories

To keep uploaded files and cached data separate for each site, uncomment these lines in the site's `mainfile.php`:

```php
define('ICMS_CACHE_PATH', XOOPS_TRUST_PATH . '/sites/example.com/cache');
define('ICMS_COMPILE_PATH', XOOPS_TRUST_PATH . '/sites/example.com/templates_c');
define('ICMS_UPLOAD_PATH', XOOPS_TRUST_PATH . '/sites/example.com/uploads');
define('ICMS_UPLOAD_URL', XOOPS_URL . '/uploads/example.com');
```

Make sure these directories exist and are writable by the web server.

## Security Considerations

### 1. Unique Salt Keys

**CRITICAL:** Each site must have a unique `XOOPS_DB_SALT` value. Never copy the same salt across sites. Use a strong, random value for each site:

```php
define('XOOPS_DB_SALT', 'unique_' . bin2hex(random_bytes(32)));
```

### 2. Trust Path Security

Keep the trust path outside your web root to prevent direct access to configuration files:

```
✓ GOOD: /var/www/impresscms-trust/  (outside web root)
✗ BAD:  /var/www/impresscms/htdocs/sites/  (inside web root)
```

### 3. File Permissions

Set restrictive permissions on configuration files:

```bash
chmod 600 /var/www/impresscms-trust/sites/*/mainfile.php
chown www-data:www-data /var/www/impresscms-trust/sites/*/mainfile.php
```

### 4. Host Header Validation

The dispatcher sanitizes the `HTTP_HOST` header to prevent:
- Directory traversal attacks (`../`)
- Port number injection
- Invalid characters
- Case sensitivity issues

Only alphanumeric characters, dots, and dashes are allowed in hostnames.

## Hostname Resolution

The dispatcher uses the following logic to find a site configuration:

1. **Exact match:** `example.com` → `/sites/example.com/mainfile.php`
2. **Without www:** `www.example.com` → tries `/sites/example.com/mainfile.php`
3. **Default fallback:** → `/sites/default/mainfile.php`
4. **Legacy fallback:** → `/htdocs/mainfile.dist.php` (single-site mode)

## Troubleshooting

### Site redirects to installer

- Ensure `ICMS_MULTISITE_ROOT_PATH` is defined before `mainfile.php` is loaded
- Check that the site configuration file exists and is readable
- Verify the hostname matches the directory name exactly (case-sensitive on Linux)

### Wrong site loads

- Check web server configuration for correct `DocumentRoot`
- Verify DNS and hostname resolution
- Enable PHP error logging to see which config file is being loaded

### Database connection errors

- Verify database credentials in the site-specific `mainfile.php`
- Ensure the database exists and the user has proper permissions
- Check that table prefixes don't conflict if sharing a database

### File permission errors

- Ensure cache, templates_c, and uploads directories are writable (chmod 777 or 755 with proper ownership)
- Check ownership: `chown -R www-data:www-data /var/www/impresscms-trust/sites/`

## Migration from Single-Site

To migrate an existing single-site installation to multisite:

1. **Backup everything** (files and database)
2. Create the multisite directory structure
3. Copy your existing `mainfile.php` to `/sites/yourdomain.com/mainfile.php`
4. Update paths in the new file if needed
5. Define `ICMS_MULTISITE_ROOT_PATH` before the dispatcher runs
6. Test the site
7. Replace `htdocs/mainfile.php` with the multisite dispatcher

## Advanced: Dynamic Path Detection

If you prefer not to define `ICMS_MULTISITE_ROOT_PATH` explicitly, the dispatcher will attempt to auto-detect it by looking for a `sites` directory one level up from `htdocs`:

```
/var/www/impresscms/
├── htdocs/           # XOOPS_ROOT_PATH
└── sites/            # Auto-detected as TRUST_PATH/sites
```

However, explicit configuration is recommended for production environments.

## Performance Considerations

- The dispatcher adds minimal overhead (one file check per request)
- Use opcode caching (OPcache) to cache the dispatcher and config files
- Consider using a CDN for static assets shared across sites
- Separate cache and compiled template directories per site improve isolation but use more disk space

## Support

For issues or questions about multisite configuration, please:

1. Check this documentation
2. Review server error logs
3. Visit the ImpressCMS forums
4. Submit a GitHub issue with details about your setup

## License

ImpressCMS Multisite Support is licensed under GPL 2.0, same as ImpressCMS itself.
