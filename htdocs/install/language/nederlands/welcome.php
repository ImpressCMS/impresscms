<?php
// $Id: welcome.php 12227 2013-07-19 08:07:21Z fiammy $
$content .= '
<p>
	ImpressCMS is een Website beheer systeem geschreven in PHP. Het is open source en gratis in gebruik. Het is de ideale tool om kleine tot middelgrote dynamische websites te ontwikkelen zoals bijvoorbeeld
	community websites, intranets, bedrijfssites, blogs en veel meer.
</p>
<p>
	ImpressCMS wordt ter beschikking gesteld onder de 
	<a href="https://www.gnu.org/licenses/old-licenses/gpl-2.0.html#top" rel="external">GNU General Public License (GPL) v2</a>
	en is vrij om te gebruiken en aan te passen.
	Het is vrij om te herverdelen zolang de termen van de GPLv2 worden gevolgd.
</p>
<h3>Vereisten</h3>
<ul>
	<li>- Web Server: <a href="http://www.apache.org/" rel="external">Apache</a>, NGinx, IIS, Roxen, etc</li>
	<li>- Script: <a href="http://www.php.net/" rel="external">PHP</a> 7.0+ en minstens 16MB beschikbaar geheugen.</li>
	<li>- Database: <a href="http://www.mysql.com/" rel="external">MySQL</a> 4.1.0 of nieuwer, <a href="https://mariadb.org/" rel="external">MariaDB</a> 5.1 of nieuwer</li>
</ul>
<h3>Voorbereiding voor de installatie</h3>
<ul>
	<li>Zorg voor een werkende instalatie van de web server, PHP en de database server.</li>
	<li>Bereid een database voor die ImpressCMS kan gebruiker. Dit kan een bestaande of een nieuwe database zijn.</li>
	<li>Geef op de database server een gebruiker de nodige rechten tot die database (alle rechten).</li>
	<li>Geef schrijfrechten aan de webserver aan de mappen uploads/, cache/, templates_c/, modules/ (chmod 777 of 755 - afhankelijk van de server).</li>
	<li>Geef schrijfrechten tot het bestand mainfile.php (chmod 666 afhankelijk van de server).</li>
	<li>Zorg ervoor dat cookies en javascript toegelaten zijn in uw browser.</li>
</ul>
';
?>
