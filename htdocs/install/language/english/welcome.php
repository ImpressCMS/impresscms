<?php

$content .= '
<p>
	ImpressCMS is a Web publishing system written in PHP. It is an ideal tool for
	developing small to large dynamic community websites, intra company portals, corporate portals, weblogs and much more.
</p>
<p>
	ImpressCMS is released under the terms of the
	<a href="http://www.gnu.org/copyleft/gpl.html" rel="external">GNU General Public License (GPL)</a>
	and is free to use and modify.
	It is free to redistribute as long as you abide by the distribution terms of the GPL.
</p>
<h3>Requirements</h3>
<ul>
	<li>- WWW Server: <a href="http://www.apache.org/" rel="external">Apache</a>, IIS, Roxen, etc</li>
	<li>- Script: <a href="http://www.php.net/" rel="external">PHP</a> 5.2.8 or higher and 16mb minimum memory allocation</li>
	<li>- Database: <a href="http://www.mysql.com/" rel="external">MySQL</a> 4.1.0 or higher, <a href="https://mariadb.org/" rel="external">MariaDB</a> 5.1 or higher</li>
</ul>
<h3>Before you install</h3>
<ul>
	<li>Setup the web server, PHP and database server properly.</li>
	<li>Prepare a database for ImpressCMS. This can be an existing database as well as a newly created one.</li>
	<li>Prepare a user account and grant this user access to the database (all rights).</li>
	<li>Make these directories writable (chmod 777 or 755 - depending on servers):
	<ul style="list-style-type: disc; list-style-position: inside">
	<li>uploads/</li>
	<li>modules/</li>
	<li>../storage/templates_c</li>
	<li>../storage/cache</li>
	<li>../storage/log</li>
	<li>../storage/htmlpurifier</li>
	<li>../storage/composer</li>
	<li>../modules/</li>
</ul>
	<li>Make the file ../.env writable (chmod 666 depending on server).</li>
	<li>In your internet browser settings turn on the allowance of cookies and JavaScript.</li>
</ul>
';

