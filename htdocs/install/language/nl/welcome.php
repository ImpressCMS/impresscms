<?php

$content .= '
<p>
	ImpressCMS is een publicatiesysteem voor het Web, geschreven in PHP. Het is het ideale hulpmiddel om zowel kleine als grote dynamische community websites, bedrijfsportalen, weblogs en veel meer te ontwikkelen.
</p>
<p>
	ImpressCMS wordt ter beschikking gesteld van de 
	<a href="http://www.gnu.org/copyleft/gpl.html" rel="external">GNU General Public License (GPL)</a>
and is vrij om te gebruiken of te wijzigen.
Het is vrij om te herdistribueren zolang er aan de voorwaarden van de GPL wordt voldaan.
</p>
<h3>Vereisten</h3>
<ul>
	<li>- WWW Server: <a href="http://www.apache.org/" rel="external">Apache</a>, Nginx, Roxen, etc</li>
	<li>- Script: <a href="http://www.php.net/" rel="external">PHP</a> 7.0 of hoger en minimaal 32 MB RAM</li>
	<li>- Database: <a href="http://www.mysql.com/" rel="external">MySQL</a> 4.1.0 or higher, <a href="https://mariadb.org/" rel="external">MariaDB</a> 5.1 or higher</li>
</ul>
<h3>Voor je installeert</h3>
<ul>
	<li>Zet de web server, PHP en database server op zoals het hoort.</li>
	<li>Bereid een databank voor om door ImpressCMS te laten gebruiken. Dat kan een bestaande databank zijn, of een volledig nieuwe.</li>
	<li>Bereid een databank gebruiker voor en geef hem alle rechten op de databank waarin ImpressCMS terecht zal komen.</li>
	<li>Maak de folders uploads/, ../storage/templates_c, ../storage/cache, ../storage/log, ../storage/htmlpurifier, ../modules/ schrijfbaar (chmod 777 or 755 - hangt af van het type server).</li>
	<li>Maak de file ../.env schrijfbaar(chmod 666 - hangt af van de server).</li>
	<li>In je internet browser instellingen, sta toe dat Cookies en Javascript gebruikt worden.</li>
</ul>
';

