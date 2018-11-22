#
# Dumping data for table `ranks`
#

INSERT INTO ranks VALUES (1, 'Nouveau membre', 0, 20, 0, 'rank3e632f95e81ca.gif');
INSERT INTO ranks VALUES (2, 'Membre assidu', 21, 40, 0, 'rank3dbf8e94a6f72.gif');
INSERT INTO ranks VALUES (3, 'Membre actif', 41, 70, 0, 'rank3dbf8e9e7d88d.gif');
INSERT INTO ranks VALUES (4, 'Membre efficace', 71, 150, 0, 'rank3dbf8ea81e642.gif');
INSERT INTO ranks VALUES (5, 'Membre important', 151, 10000, 0, 'rank3dbf8eb1a72e7.gif');
INSERT INTO ranks VALUES (6, 'Moderateur', 0, 0, 1, 'rank3dbf8edf15093.gif');
INSERT INTO ranks VALUES (7, 'Webmaster', 0, 0, 1, 'rank3dbf8ee8681cd.gif');

#
# Dumping data for table `smiles`
#

INSERT INTO smiles VALUES (1, ':-D', 'smil3dbd4d4e4c4f2.gif', 'tr&egrave;s heureux', 1);
INSERT INTO smiles VALUES (2, ':-)', 'smil3dbd4d6422f04.gif', 'Sourire', 1);
INSERT INTO smiles VALUES (3, ':-(', 'smil3dbd4d75edb5e.gif', 'Triste', 1);
INSERT INTO smiles VALUES (4, ':-o', 'smil3dbd4d8676346.gif', 'Surpris', 1);
INSERT INTO smiles VALUES (5, ':-?', 'smil3dbd4d99c6eaa.gif', 'Confus', 1);
INSERT INTO smiles VALUES (6, '8-)', 'smil3dbd4daabd491.gif', 'Cool', 1);
INSERT INTO smiles VALUES (7, ':lol:', 'smil3dbd4dbc14f3f.gif', 'rire', 1);
INSERT INTO smiles VALUES (8, ':-x', 'smil3dbd4dcd7b9f4.gif', 'Fou', 1);
INSERT INTO smiles VALUES (9, ':-P', 'smil3dbd4ddd6835f.gif', 'Moqueur', 1);
INSERT INTO smiles VALUES (10, ':oops:', 'smil3dbd4df1944ee.gif', 'Embarrass&eacute;', 0);
INSERT INTO smiles VALUES (11, ':cry:', 'smil3dbd4e02c5440.gif', 'tr&egrave;s triste', 0);
INSERT INTO smiles VALUES (12, ':evil:', 'smil3dbd4e1748cc9.gif', 'M&eacute;chant', 0);
INSERT INTO smiles VALUES (13, ':roll:', 'smil3dbd4e29bbcc7.gif', "T'es null !", 0);
INSERT INTO smiles VALUES (14, ';-)', 'smil3dbd4e398ff7b.gif', "clin d'œil", 0);
INSERT INTO smiles VALUES (15, ':pint:', 'smil3dbd4e4c2e742.gif', 'Tu as bu ?', 0);
INSERT INTO smiles VALUES (16, ':hammer:', 'smil3dbd4e5e7563a.gif', 'Travail !', 0);
INSERT INTO smiles VALUES (17, ':idea:', 'smil3dbd4e7853679.gif', "J'ai une id&eacute;e !", 0);

#
# Dumping data for table `icmscontent`
#

INSERT INTO icmscontent VALUES (1, 1, 0, 1, 'Bienvenue dans la communaut&eacute; ImpressCMS', 'ImpressCMS?', '<h1>Qu&#145;est-ce que ImpressCMS?</h1>\r\n<p>ImpressCMS c&#145;est une communaut&eacute; qui d&eacute;veloppe un syst&egrave;me de gestion de contenu web. Avec cet outil vous pouvez cr&eacute;er un site Web tr&egrave;s facilement, la r&eacute;daction d&#145;un document ou d&#145;un contenu est aussi facile. ImpressCMS est l&#145;outil id&eacute;al pour un large &eacute;ventail de cr&eacute;ation de site internet. Que ce soit pour une communaut&eacute; d&#145;affaires, de grandes entreprises ou pour des personnes qui veulent un site simple et facile &agrave; utiliser. ImpressCMS est un syst&egrave;me puissant qui obtient des r&eacute;sultats remarquables et il est libre et gratuit !</p>\r\n<p><strong>Que pouvez-vous faire avec ImpressCMS?</strong></p>\r\n<p>ImpressCMS peut &ecirc;tre utilis&eacute; pour de nombreux types de sites Web. Le syst&egrave;me est hautement &eacute;volutif et il peut &ecirc;tre utilis&eacute; par exemple en intranet pour une soci&eacute;t&eacute; de 20000 employ&eacute;s ainsi que pour la construction d&#145;un simple site Web pour la promotion de votre entreprise. Le syst&egrave;me est extr&ecirc;mement utile pour la gestion de communaut&eacute;s en ligne, car il a la capacit&eacute; de cr&eacute;er des groupes d&#145;utilisateurs et attribuer des autorisations pour la gestion du contenu pour chaque groupe diff&eacute;rent.</p>\r\n<p>Pour chaque type de site Web ImpressCMS offre diff&eacute;rentes fonctionnalit&eacute;s avec une collection de plus d&#145;une centaines de module libre qui sont disponibles sur http://addons.impresscms.org. Quelques exemples de ce que vous pourriez faire:</p>\r\n<div id="xo-content">\r\n<li>Publier des nouvelles de votre organisation</li>\r\n<li>Permet aux visiteurs de vous contacter par l&#145;interm&eacute;diaire d&#145;un formulaire de contact personnalisable </li>\r\n<li>Cr&eacute;er et g&eacute;rer des articles</li>\r\n<li>Ajouter un forum</li>\r\n<li>Vendre des produits par le biais d&#145;un module de boutique en ligne</li>\r\n<li>...et bien d&#145;autres</li>\r\n<p><strong>Liste des principales caract&eacute;ristiques:</strong></p>\r\n<table style="border-color: #000000; padding: 2pt; background-color: #ffeeee;" border="0">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<p><strong>Base de donn&eacute;es </strong></p>\r\n<p>ImpressCMS utilise une base de donn&eacute;es pour stocker les donn&eacute;es n&eacute;cessaires pour le fonctionnement de votre site ImpressCMS. MySQL est pris en charge.</p>\r\n<p><strong>Enti&egrave;rement modulaire </strong></p>\r\n<p>ce site est g&eacute;r&eacute; par des modules de contenu/applications web. Il vous suffit d&#145;installer le module qu&#145;il vous faut: un module de news, forum, module d&#145;album photo, il en existe beaucoup &agrave; vous de choisir.</p>\r\n<p><strong>Gestion des utilisateurs</strong></p>\r\n<li style="list-style-type: circle; list-style-image: none; list-style-position: inside; margin-left: 24px;"><strong>L&#145;enregistrement des utilisateurs est facultatif</strong>: Les utilisateurs enregistr&eacute;s sont authentifi&eacute;es &agrave; l&#145;aide de la norme ImpressCMS, ou en utilisant LDAP.</li>\r\n<li style="list-style-type: circle; list-style-image: none; list-style-position: inside; margin-left: 24px;"><strong>S&eacute;curit&eacute;</strong>: Filtrage par adresse IP, gestion des acc&egrave;s au contenu par groupe, en arri&egrave;re-plan des fonctionnalit&eacute;s de s&eacute;curit&eacute; pour la manipulation de bases de donn&eacute;es, syst&egrave;me de validation et bien plus encore.</li>\r\n<li style="list-style-type: circle; list-style-image: none; list-style-position: inside; margin-left: 24px;"><strong>Personnalisation</strong>: Les utilisateurs enregistr&eacute;s peuvent modifier leurs profils, s&eacute;lectionner les th&egrave;mes du site, t&eacute;l&eacute;charger des avatars personnalis&eacute;s, et bien plus encore!</li>\r\n<li style="list-style-type: circle; list-style-image: none; list-style-position: inside; margin-left: 24px;"><strong>Syst&egrave;me des autorisations de groupe</strong>: Puissant et convivial syst&egrave;me d&#145;autorisations qui permet aux administrateurs de d&eacute;finir les autorisations par groupe.</li>\r\n<p><strong>soutenu dans le monde entier</strong></p>\r\n<p>ImpressCMS a &eacute;t&eacute; cr&eacute;&eacute; et est constament en &eacute;volution grace &agrave; une &eacute;quipe de b&eacute;n&eacute;voles qui travaillent dans le monde entier.</p>\r\n<p><strong>multi byte support de la langue</strong></p>\r\n<p>Soutient pleinement multi-octets langues, y compris le japonais, simplifi&eacute; et le chinois traditionnel, ou cor&eacute;en, etc.</p>\r\n<p><strong>les Th&egrave;mes de base sont modifiable &agrave; volont&eacute;</strong></p>\r\n<p>ImpressCMS est dot&eacute; d&#145;un puissant syst&egrave;me de th&egrave;me. Les administrateurs et les utilisateurs peuvent modifier l&#145;apparence de l&#145;ensemble du site Web d&#145;un simple clic de souris. Il y a aussi des centaines de th&egrave;mes de qualit&eacute; disponibles en t&eacute;l&eacute;chargement !</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>', '.content_nav {\r\n    padding:3px;\r\n}\r\n\r\n#content_header {\r\n    padding: 0;\r\n    margin:0;\r\n}\r\n\r\n.content_title_admlinks {\r\n    padding: 0;\r\n    padding-bottom:2px;\r\n    margin:0;\r\n    font-size: 18px;\r\n    float:right;\r\n}\r\n\r\nh1.content_title {\r\n    padding: 0;\r\n    padding-bottom:2px;\r\n    margin:0;\r\n    font-size: 18px;\r\n    border-bottom: 1px solid #333333;\r\n}\r\n\r\nh2.content_title_info {\r\n    height: 15px;\r\n    padding: 2px;\r\n    margin: 0;\r\n    background: #efefef;\r\n    font-size: 11px;\r\n    font-style:italic;\r\n    font-weight:normal;\r\n    text-align: right;\r\n}\r\n\r\n.content_body {\r\n   padding:5px;\r\n}\r\n\r\n#content_subs {\r\n   margin-top: 5px;\r\n}\r\n\r\n.content_subs_header {\r\n    padding: 0;\r\n    margin:0;\r\n    font-size: 14px;\r\n    font-weight: bold;\r\n}\r\n\r\n.content_subs_item {}\r\n.content_subs_item even {}\r\n.content_subs_item odd {}\r\nh3.content_subs_item_title {}\r\n.content_subs_item_teaser{\r\n    font-style:italic;\r\n}', 3, 1213474318, 1213474318, 0, 0, 1);

#
# Dumping data for table `icmspage`
#

INSERT INTO icmspage VALUES (1, 1, 'Bienvenue dans la communaut&eacute; ImpressCMS', 'content.php?page=ImpressCMS?', 1);
