<?php
// $Id: welcome.php 565 2006-06-23 06:39:22Z skalpa $
$content .= '
<p>
	ImpressCMS &egrave; un sistema di pubblicazione Web scritto in PHP. &Egrave; lo strumento ideale per
	sviluppare piccoli o grandi siti Web dinamici per community, portali aziendali, weblogs e molto altro ancora.
</p>
<p>
	ImpressCMS &egrave; rilasciato sotto i termini della
	<a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU General Public License (GPL)</a>
	ed si pu&ograve; liberamente usare e modificare.
	&Egrave; gratuito e liberamente distribuibile secondo i termini della licenza GPL.
</p>
<h3>Requisiti di sistema</h3>
<ul>
	<li>WWW Server (<a href="http://www.apache.org/" target="_blank">Apache</a>, IIS, Roxen, ecc.)</li>
	<li><a href="http://www.php.net/" target="_blank">PHP</a> 5.2 o maggiore (5.3 o maggiore è supportato) e 16MB minimo di allocazione di memoria </li>
	<li><a href="http://www.mysql.com/" target="_blank">MySQL</a> 4.1.0 o maggiore</li>
</ul>
<h3>Prima di installare</h3>
<ul>
	<li>Impostare il vostro server web, il PHP e il server database.</li>
	<li>Preparare un database per ImpressCMS con la codifica UTF-8. Questo pu&ograve; essere un database gi&agrave; esistente oppure uno nuovo appositamente creato.</li>
	<li>Preparare un utente per il database e dargli tutti i diritti di amministrazione su quel database.</li>
	<li>Rendere scrivibili le cartelle uploads/ (anche le sotto-cartelle) , cache/, templates_c/ e modules/ (chmod 777 o 755 - dipende dal server).</li>
	<li>Rendere scrivibile il file mainfile.php (chmod 666).</li>
	<li>Nel vostro browser Internet attivare i permessi per cookies e JavaScript.</li>
</ul>
';
