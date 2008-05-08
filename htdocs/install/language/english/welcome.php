<?php
// $Id: welcome.php 565 2006-06-23 06:39:22Z skalpa $
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
	<li>WWW Server (<a href="http://www.apache.org/" rel="external">Apache</a>, IIS, Roxen, etc)</li>
	<li><a href="http://www.php.net/" rel="external">PHP</a> 4.3.0 or higher (5.1 or higher recommended)</li>
	<li><a href="http://www.mysql.com/" rel="external">MySQL</a> 3.23 or higher (4.1 or higher recommended)</li>
</ul>
<h3>Before you install</h3>
<ul>
	<li>Setup the web server, PHP and database server properly.</li>
	<li>Prepare a database for ImpressCMS. This can be an existing database as well as a newly created one.</li>
	<li>Prepare a user account and grant this user access to the database (all rights).</li>
	<li>Make the directories of uploads/, cache/, templates_c/, writable (chmod 777 or 755 - depending on servers).</li>
	<li>Make the file mainfile.php writable (chmod 666 depending on server).</li>
	<li>In your internet browser settings turn on the allowance of cookies and JavaScript.</li>
</ul>
';
?>