<?php
// $Id: welcome.php 9674 2009-12-23 17:14:26Z Phoenyx $
$content .= '
<p>
	ImpressCMS is een webpublicatie systeem dat is geschreven in PHP. Het is het ideale hulpmiddel om 
	kleine tot grote dynamische gemeenschapswebsites, intranetportalen, bedrijfswebsites, webloggen en ga zo maar door te maken.
</p>
<p>
	ImpressCMS is vrijgegeven onder de regelgeving van de
	<a href="http://www.gnu.org/copyleft/gpl.html" rel="external">GNU General Public License (GPL)</a>
	en is vrij om te gebruiken en om aanpassingen aan te brengen.
	Het is toegestaan om te (her)distribueren zolang u dit doet onder de daarvoor geldende regelgeving van de GPL.
</p>
<h3>Systeemeisen</h3>
<ul>
	<li>WWW Server (<a href="http://www.apache.org/" rel="external">Apache</a>, IIS, Roxen, etc)</li>
	<li><a href="http://www.php.net/" rel="external">PHP</a> 5.2 of hoger (5.2.8 of hoger is aanbevolen, <strong>5.3 wordt nog niet ondersteund</strong>) en 16mb minimum toegewezen geheugen</li>
	<li><a href="http://www.mysql.com/" rel="external">MySQL</a> 4.1.0 of hoger</li>
</ul>
<h3>Voordat u gaat beginnen met installeren</h3>
<ul>
	<li>Controleer of de webserver goed is geconfigureerd, met de juiste versies van PHP en de MySQL database.</li>
	<li>Creeer of bereid een MySQL database voor, voor gebruik door ImpressCMS.</li>
	<li>Maak een gebruiker aan voor de database, en zorg dat deze gebruiker de juiste toegangsrechten heeft tot deze database (alle rechten).</li>
	<li>Zorg er voor dat de mappen uploads/, cache/, templates_c/ en modules/ door het systeem te beschrijven zijn (CHMOD 755 of 777 - afhankelijk van de server).</li>
	<li>Zorg er voor dat het bestand mainfile.php door het systeem te beschrijven is (CHMOD 666 - afhankelijk van de server).</li>
	<li>Zorg er voor dat uw webbrowser cookies en javascript accepteert.</li>
</ul>
';
