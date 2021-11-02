<?php

$content .= '
<p>ImpressCMS ist ein in PHP geschriebenes Web-Publishing-System. Es ist ein ideales Werkzeug für
Entwicklung kleiner bis großer dynamischer Community-Websites, unternehmensinterner Portale, Unternehmensportale, Weblogs und vieles mehr.</p>
<p>
	ImpressCMS ist freigegeben unter den Bedingungen der
	<a href="http://www.gnu.org/copyleft/gpl.html" rel="external">GNU General Public License (GPL)</a>
	und ist frei zu verwenden und zu Ändern.
	Es ist frei, so lange Ãnderungen, wie Sie durch die Bestimungen der GPL genannt sind, erhalten bleiben.
</p>
<h3>Anforderungen</h3>
<ul>
	<li>- WWW Server: <a href="http://www.apache.org/" rel="external">Apache</a>, IIS, Roxen, etc</li>
	<li>- Script: <a href="http://www.php.net/" rel="external">PHP</a> 5.5+  oder Höher and 16mb minimum Memory-Limit</li>
	<li>- Database: <a href="http://www.mysql.com/" rel="external">MySQL</a> 4.1.0 oder Höher, <a href="https://mariadb.org/" rel="external">MariaDB</a> 5.1  oder Höher</li>
</ul>
<h3>Vorbereitungen</h3>
<ul>
	<li>Setup des HTTP-Servers, PHP und der Datenbankrechte.</li>
	<li>Erstellen Sie eine Datenbank für Ihre ImpressCMS - Webseite. Es kann auch eine bereits vorhandene Datenbank benutzt werden.</li>
	<li>Erstellen Sie ein Benutzerkonto und gewähren Sie diesem Benutzer Zugriff auf die Datenbank (alle Rechte).</li>
	<li>Geben Sie den folgenden Verzeichnisse uploads, templates_c, cache, log, htmlpurifier, modules die Berechtchtigung (chmod 777 oder 755 - je nach Server ).</li>
	<li>Machen Sie die Datei ../.env schreibbar (chmod 666 je nach Server).</li>
	<li>Aktivieren Sie in den Einstellungen Ihres Internetbrowsers das Zulassen von Cookies und JavaScript.</li>
</ul>
';

