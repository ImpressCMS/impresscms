## Purpose

Defines how the framework host is deployed as part of an ImpressCMS site. It covers:
- what lives in the web root and what lives in the trust path;
- how requests enter the host;
- how the host is configured;
- how it is installed on new sites and on sites upgraded from releases before the host existed.

## ADDED Requirements

### Requirement: The host lives in the trust path
The system SHALL keep the host's code, dependencies, configuration and runtime files in the trust path recorded in `mainfile.php`. The web root SHALL contain no host configuration and no third-party dependency tree once the host is installed.

#### Scenario: Installed host files
- **WHEN** the host has been installed into the trust path of a site
- **THEN** the trust path contains `host.php`, `composer.json`, `vendor/autoload.php`, `src/`, `config/` and `.env.local`, and the web root contains neither `vendor/` nor `trust_dist/`

### Requirement: The web root's index.php is the entry point
The web root's `index.php` SHALL hand the request to the host when the host is installed in the trust path. It SHALL behave as the legacy home page when it is not. A site that is not installed SHALL still redirect to the installer. Executing the home page from within the host SHALL NOT re-enter the host.

#### Scenario: Home page through the host
- **WHEN** the host is installed and `GET /` arrives
- **THEN** the host handles the request and the legacy home page is rendered through the bridge

#### Scenario: Trust path without a host
- **WHEN** the trust path contains no host and `GET /` arrives
- **THEN** the legacy home page is rendered without the host

#### Scenario: Routing level 3 without a host
- **WHEN** the web server routes every `.php` request to `index.php`, the trust path contains no host, and `GET /user.php` arrives
- **THEN** `index.php` runs `user.php` as the web server would have, and never runs a script in a directory that is never served

#### Scenario: Trust path is read without executing mainfile.php
- **WHEN** the entry point locates the trust path
- **THEN** it reads `XOOPS_TRUST_PATH` from `mainfile.php` without including the file, and it follows `ICMS_MAINFILE` when `mainfile.php` is the uninstalled stub

### Requirement: The host works at every routing level the web server provides
The system SHALL work without any web-server configuration. When the web server routes unknown paths, or all `.php` requests, to the web root's `index.php`, the host SHALL handle those requests too, with responses identical to direct execution of the legacy scripts.

#### Scenario: Symfony route through the fallback
- **WHEN** the web server routes unknown paths to `index.php` and `GET /up` arrives
- **THEN** the host responds with status 200 and body `ok`

#### Scenario: Legacy script through the host
- **WHEN** the web server routes every `.php` request to `index.php` and `GET /user.php` arrives
- **THEN** the host executes `user.php` through the bridge and the response matches the characterization snapshot

#### Scenario: Supported web servers
- **WHEN** the example configuration from `docs/server/` is used on Apache, nginx or FrankenPHP
- **THEN** the HTTP suite passes at routing levels 2 and 3

### Requirement: Configuration does not depend on the server environment
The host SHALL read its environment, debug flag, secret and web-root location from `.env.local` in the trust path. Web requests SHALL take the web root and trust path from the entry point. The host SHALL NOT require environment variables from the web server.

#### Scenario: Server without environment passing
- **WHEN** PHP runs with `variables_order=GPCS` and no server environment variables are set
- **THEN** the host runs with the environment and secret from `.env.local`

### Requirement: The host is installed and upgraded from the staging directory
The installer and the system module update SHALL copy the host from the staging directory `trust_dist/` in the web root into the trust path, verify the copy, and remove the staging directory. Until then, legacy SHALL load its dependencies from the staging directory. Reinstalling SHALL keep `var/` and `.env.local`, SHALL clear the cached container, and SHALL write `.env.local` with a random secret when it is missing.

#### Scenario: Fresh installation
- **WHEN** the installer runs on a web root that contains `trust_dist/`
- **THEN** the host is installed into the chosen trust path, `.env.local` contains a random `APP_SECRET` and the web root, and `trust_dist/` is removed

#### Scenario: Upgrade from a release without Composer
- **WHEN** the files of this release are uploaded over a 2.0.x site and the system module is updated
- **THEN** pages render before the update using the staging dependencies, and after the update the host is installed and `GET /` is handled by the host

#### Scenario: Upgrade from a site with a moved vendor directory
- **WHEN** the trust path contains a `vendor/` moved there by dbversion 49 and the trust-path autoloader stub, and the system module is updated
- **THEN** the old tree is kept as `vendor.pre-50`, the stub is removed and the host is installed

#### Scenario: Reinstall keeps local state
- **WHEN** the host is installed into a trust path that already has `.env.local` and `var/`
- **THEN** `.env.local` is unchanged, `var/log` is kept and `var/cache` is emptied

#### Scenario: Failed copy leaves the site intact
- **WHEN** copying the staging directory fails or the copy does not verify
- **THEN** the trust path keeps its previous files, the staging directory is kept, and the update reports the failure

### Requirement: Host files are never public
The system SHALL refuse to serve or execute the staging directory and the trust path through the host. The installer SHALL propose a trust path outside the document root. The admin dashboard SHALL warn when the trust path lies under the web root.

#### Scenario: Staging directory is not served
- **WHEN** `GET /trust_dist/composer.json` or `GET /trust_dist/host.php` arrives
- **THEN** the response is 404 and nothing is executed

#### Scenario: Default trust path
- **WHEN** the installer's path page is opened for a site at the document root
- **THEN** the proposed trust path lies outside the document root

#### Scenario: Warning for a public trust path
- **WHEN** a webmaster opens the admin dashboard of a site whose trust path lies under the web root
- **THEN** a warning names the trust path and explains how to move it
