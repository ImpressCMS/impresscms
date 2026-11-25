---
name: impresscms-local-install
description: Set up a working local ImpressCMS site for testing (database, PHP dev server, and a scripted run of the web installer) from this repository's htdocs/, then tear it down again. Use this whenever someone wants to run, try out, reproduce a bug in, manually test, or click around ImpressCMS locally, test a branch, PR or fix in a real install, get a dev or test instance or sandbox, create a mainfile.php or trust path, or asks how to install ImpressCMS on their machine, even if they don't say "install" (for example "can you check this change actually works in the admin panel" or "spin up a site so I can see the bug").
---

# Local ImpressCMS install for testing

ImpressCMS has no CLI installer. A site only exists after the web wizard in `htdocs/install/` has run: it writes `mainfile.php`, creates a *trust path* folder holding the DB credentials, creates about 38 tables and the first admin account. The bundled scripts drive that wizard with `curl`, so you get a working site in about 10 seconds without a browser, and they remove everything again afterwards.

Scripts (paths are relative to this skill's folder):
- `scripts/setup_local_site.sh`: checks prerequisites, creates the DB user, starts `php -S`, runs every installer step, checks that admin login works, and prints the URLs and credentials.
- `scripts/teardown_local_site.sh`: stops the server, drops the DB, deletes the site folder and, in live mode, restores the git checkout.

Run either one with `--help` to see every option. Each option can also be set as an environment variable (`PORT`, `DB_NAME`, `ADMIN_PASS`, …).

## 1. Pick a mode

| Mode | Web root | Use when |
|---|---|---|
| `copy` (default) | a fresh copy of `htdocs/` under `--site-dir` | Testing install or upgrade behaviour, the released state of a branch, or anything that writes files. The checkout is never touched. |
| `live` | the checkout's own `htdocs/` | Testing code you are editing. Edits show up on the next page load because OPcache is off. |

To test a change you are making, use **live**. For "does a fresh install of this branch work", use **copy**. If you're unsure, ask the user once. If you can't ask, use copy, because it can't damage their working tree.

In live mode the installer **rewrites the tracked file `htdocs/mainfile.php`** (and sets it to mode 0444). It also copies `modules/protector/` into the tree and writes caches to `cache/` and `templates_c/`. Never stage or commit any of these. The teardown script puts them all back. If you are preparing a commit while a live site is running, use `git add <specific files>`, not `git add -A`.

## 2. Make sure a database is reachable

The installer needs MySQL or MariaDB. By default the script connects as `icms`/`icms` on `127.0.0.1:3306`. If that login fails, it tries `mysql -uroot` and then `sudo -n mysql`, and uses whichever works to create the user. Pick one way to get a database:

- **Already running locally** (XAMPP, Homebrew, a distro package): nothing to do. If root needs a password, pass `DB_ROOT_CMD="mysql -uroot -pSECRET"`.
- **Install natively**: Debian/Ubuntu: `apt-get install -y mariadb-server && service mariadb start`. macOS: `brew install mariadb && brew services start mariadb`.
- **Docker**, keeping the host's 3306 free:
  ```bash
  docker run -d --name icms-db -p 3307:3306 \
    -e MARIADB_ROOT_PASSWORD=root -e MARIADB_DATABASE=icms_test \
    -e MARIADB_USER=icms -e MARIADB_PASSWORD=icms mariadb:10.11
  ```
  Then run setup with `DB_PORT=3307`, and give the container about 10 seconds to start first.

PHP 7.4 or newer is needed, with `pdo_mysql mbstring gd curl session pcre xml`. The script lists any missing extension and stops.

## 3. Run setup

```bash
.claude/skills/impresscms-local-install/scripts/setup_local_site.sh --mode live --debug
```

Useful flags: `--site-dir DIR` (default `~/icms-local/site`) holds the trust path, server log and `site.env`. `--port 8081` lets you run a second site next to the first; give it its own `--site-dir` and `--db-name` too. `--modules a,b` installs extra module folders that you placed in `htdocs/modules/` beforehand. `--debug` turns on the Developer Dashboard so PHP and SQL errors show inline.

When it finishes, it prints:

```
ImpressCMS is running.
  Site:        http://127.0.0.1:8080/
  Admin panel: http://127.0.0.1:8080/admin.php   (login: admin / admin123)
  ...
  Server log:  ~/icms-local/site/php-server.log
```

Pass those lines on to the user. If a step fails, the script stops at the first installer page that didn't advance. It saves that page to `<site-dir>/last-step.html`, and the PHP fatal error is in `php-server.log`. Read both before you change anything: the installer's real error message is in the HTML.

## 4. Test against it

- **Log in from scripts or tests** with the *login name*, not the display name:
  `curl -c jar -b jar -d uname=admin -d pass=admin123 -d op=login http://127.0.0.1:8080/user.php`, then reuse `jar`. The display name ("Administrator") won't work.
- **Look at `php-server.log`** after each request. Warnings and notices go there even when nothing shows in the page. Under `php -S` you will always see `Undefined array key "QUERY_STRING" in libraries/icms.php`; that is a known harmless notice, so don't report it as a problem caused by the change.
- **Your first visit to `/admin.php` redirects to a system module update** (`fct=modulesadmin&op=update&module=system`). That is normal on a new install.
- **Protector** is copied into `modules/` but the installer doesn't activate it. If you need it, install it from *Admin → Modules*, or GET `modules/system/admin.php?fct=modulesadmin&op=install&module=protector` while logged in.
- **Inspect data** with `mysql -h127.0.0.1 -uicms -picms icms_test`. Tables use the prefix `icms_` (`icms_users`, `icms_config`, `icms_modules`, …).
- **Change preferences without the UI**, for example to toggle debug mode:
  `UPDATE icms_config SET conf_value='1' WHERE conf_name='debug_mode' AND conf_modid=0;` (values: 0 off, 1 inline, 2 popup, 3 Smarty templates).
- To take a browser screenshot, point Playwright or Chromium at the printed URL.

## 5. Tear down

```bash
.claude/skills/impresscms-local-install/scripts/teardown_local_site.sh --site-dir ~/icms-local/site
```

Do this when the testing is done, and before a re-install. The installer won't run over an existing install, and setup refuses to start when `mainfile.php` is already modified or the DB already has a `<prefix>_users` table. `--keep-db` keeps the database, which is useful when you want to inspect it later. In live mode, finish by checking `git status`: the only changes left should be the user's own.

## Installing by hand (when the user wants to click through)

Start the server yourself with `php -d opcache.enable=0 -S 127.0.0.1:8080 -t <webroot>` and open `http://127.0.0.1:8080/`, which redirects to `install/`. Then enter these values:

| Page | Enter |
|---|---|
| Paths | Web root = absolute path of `htdocs` (no trailing slash). Trust path = an absolute path **outside** the web root; the installer creates it if possible. URL = `http://127.0.0.1:8080` |
| DB connection | type PDO MySQL, host `127.0.0.1` (for a non-default port, `127.0.0.1;port=3307`), user and password |
| DB settings | database name (created if missing), charset `utf8mb4`, a table prefix. Leave the salt as generated. |
| Site init | admin display name, **login name**, email, password (twice) |

Don't click the final "delete install folder" link in live mode, because it deletes the tracked `htdocs/install/` directory. If it gets deleted anyway, restore it with `git checkout -- htdocs/install`.

## Why the scripts do what they do (for when something breaks)

- **OPcache is turned off on the dev server.** `page_configsave.php` writes `mainfile.php`, and the very next request includes it. With PHP's default `opcache.revalidate_freq=2`, that request still sees the old empty file and fails with `Undefined constant "XOOPS_DB_TYPE"`. If you start a server yourself, pass `-d opcache.enable=0`.
- **DB port goes in the host field.** The installer has no port field, and both the installer and the runtime build the DSN as `mysql:host=<DB_HOST>;…`. So `127.0.0.1;port=3307` works throughout, while `127.0.0.1:3307` doesn't.
- **Trust path outside the web root.** This is where `sdata` (the real DB credentials) and Protector's data live. Putting it inside `htdocs` would make those files reachable over the web.
- **Use `127.0.0.1`, not `localhost`.** With `localhost`, PDO tries a Unix socket, which may live somewhere else or not exist in Docker setups. `127.0.0.1` always uses TCP.
- **The installer's page order** is `langselect → start → modcheck → pathsettings → dbconnection → dbsettings → configsave → tablescreate → siteinit → tablesfill → modulesinstall → end`. State is kept in the PHP session, so one cookie jar has to be used for the whole run. On the form pages (`langselect`, `pathsettings`, `dbconnection`, `dbsettings`, `configsave`, `siteinit`), an accepted POST answers with a 303 redirect to the next page; a 200 there means validation failed and the page shows the error. `tablescreate`, `tablesfill` and `modulesinstall` answer their POST with a 200 results page.
