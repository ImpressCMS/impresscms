<?php

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) ) $mydirname = 'protector' ;
$constpref = '_MI_' . strtoupper( $mydirname ) ;

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( $constpref.'_LOADED' ) ) {





// Appended by Xoops Language Checker -GIJOE- in 2009-01-14 11:10:53
define($constpref.'_DBLAYERTRAP','Enable DB Layer trapping anti-SQL-Injection');
define($constpref.'_DBLAYERTRAPDSC','Almost SQL Injection attacks will be canceled by this feature. This feature is required a support from databasefactory. You can check it on Security Advisory page.');

// Appended by Xoops Language Checker -GIJOE- in 2008-11-21 04:44:31
define($constpref.'_DEFAULT_LANG','Default language');
define($constpref.'_DEFAULT_LANGDSC','Specify the language set to display messages before processing common.php');
define($constpref.'_BWLIMIT_COUNT','Bandwidth limitation');
define($constpref.'_BWLIMIT_COUNTDSC','Specify the max access to mainfile.php during watching time. This value should be 0 for normal environments which have enough CPU bandwidth. The number fewer than 10 will be ignored.');

// Appended by Xoops Language Checker -GIJOE- in 2007-07-30 16:31:33
define($constpref.'_BANIP_TIME0','דֹֿ  ַבדהÚ  בבד״ׁזֿםה .. ַָבֻזַהם');
define($constpref.'_OPT_BIPTIME0','זÞÝ  הװַ״ ַבÚײז דִÞÊַ');
define($constpref.'_DOSOPT_BIPTIME0','זÞÝ  הװַ״ ַבÚײז דִÞÊַ');

// Appended by Xoops Language Checker -GIJOE- in 2007-04-08 04:24:49
define($constpref.'_ADMENU_MYBLOCKSADMIN','ַבÊױַׁםֽ');

define( $constpref.'_LOADED' , 1 ) ;

// The name of this module
define($constpref."_NAME","ַבַֽׁ׃ בׂזָ׃");

// A brief description of this module
define($constpref."_DESC","ו׀ַ ַבָׁהַדּ םזÝׁ בß ֽדַםֹ ײֿ ÚדבםַÊ ַבַ־ÊַׁÞ ַבד־ÊבÝֹ זַבֽÞה זוּזד ַבֿז׃  . ״ָÚַ בם׃ ßב ַהזַÚַבוּזד  זבßה בבדװוזׁ זַב־״םׁ דהוַ");

// Menu
define($constpref."_ADMININDEX","ַבֶׁם׃םֹ");
define($constpref."_ADVISORY","ÊÝֽױ ַבֽדַםֹ");
define($constpref."_PREFIXMANAGER","ַַֹֿׁ  ÞַÚֹֿ ַבָםַהַÊ");

// Configs
define($constpref.'_GLOBAL_DISBL','דÚ״ב דִÞÊַ');
define($constpref.'_GLOBAL_DISBLDSC','הÚד בÊÚ״םב ַבֽדַםֹ זבַ בÊװÛםב ַבֽדַםֹ םהÝÚß<br /> ÊÝÚםב ו׀ַ ַב־םַׁ Ýם ַֽבֹ ׁÛָÊ ַױבַֽ דװßבֹ דÚםהֹ זבַÊ׃ל ַה ÊÞזד ָÊÝÚםבֹ ָÚֿ ֽב ַבדװßבֹ');

define($constpref.'_RELIABLE_IPS','ַבַםָםוַÊ ַבדזֻזÞ דהוַ');
define($constpref.'_RELIABLE_IPSDSC','ײÚ ָםה ßב ַםָ זַ־ׁ Úבַדֹ | זÚבַדֹ ^ Ýם ַָֿםֹ ßב ׃״ׁ ּֿםֿ');

define($constpref.'_LOG_LEVEL','ד׃Êזל ַב׃ּב');
define($constpref.'_LOG_LEVELDSC','');

define($constpref.'_LOGLEVEL0','בַםזּֿ');
define($constpref.'_LOGLEVEL15','ָ׃ם״');
define($constpref.'_LOGLEVEL63','ַָ׃״');
define($constpref.'_LOGLEVEL255','ßבם');

define($constpref.'_HIJACK_TOPBIT','ַבֽדַםֹ דה  ׃ׁÞֹ זַ׃Ê־ֿד ßזßםׂ ַבדֿםׁ ַז ַם Úײז');
define($constpref.'_HIJACK_TOPBITDSC','בבֽדַםֹ דה ַבֽÞה זÚדבםַÊ ׃ׁÞֹ ַבßזßםׂ<br />ַבזײÚם ַבַÝÊַׁײם וז 32 (׃Êד ֽדַםֹ ַבßב)<br />Ýם ַֽבֹ בד Êßה Êדבß ַםָם ַָֻÊ<br /> ַ׀ ßַה ַבַםָם ַב׀ם Ê׃Ê־ֿד ָםה  192.168.0.0-192.168.0.255, ײÚ 24 Ýם ו׀ֹ ַבַֽבֹ זוז ַבַÝײב');
define($constpref.'_HIJACK_DENYGP','ַבדּדזÚֹ ַבÛםׁ דױֽׁ ָדהÚוַ');
define($constpref.'_HIJACK_DENYGPDSC','דײַֿ ׃ׁÞֹ ַבßזßםׂ:<br />ַ־Êַׁ ַבדּדזÚֹ ַבÊם ÊׁÛָ  ָÚֿד ַב׃דַֽ ָדהÚוַ <br />(ַבַÝײב ַ־Êםַׁ  דּדזÚֹ ַבַַֿׁ ַז דֿםׁ ַבדזÞÚ)');
define($constpref.'_SAN_NULLBYTE','ÊÚÞםד ַבַזַדׁ');
define($constpref.'_SAN_NULLBYTEDSC','Ûַבָ ַבדֿ־בַÊ ַבדֽÊזםֹ Úבל ַבׁדׂ "\\0" Ýום Ûַבַָ Êװםׁ ַבם Úדבםֹ Ê־ׁםָםֹ<br />Ýם ַֽבֹ זּזֿ דֻב ו׀ֹ ַבַזַדׁ ׃םÊד Êֽזםבוַ ַבם ÝַׁÛַÊ<br />(ÊÝÚםב ו׀ַ ַבַדׁ Ýם Ûַםֹ ַבַודםֹ)');
define($constpref.'_DIE_NULLBYTE','Ýם ַֽבֹ זּזֿ ַזַדׁ ־ָםֹֻ ׃םÊד ַב־ׁזּ דה ַבַדׁ');
define($constpref.'_DIE_NULLBYTEDSC','Ûַָ ַבדֿ־בַÊ ַבדֽÊזםֹ Úבל ַבׁדׂ "\\0" Ýום Ûַבַָ Êװםׁ ַבם Úדבםֹ Ê־ׁםָםֹ<br />Ýם ַֽבֹ זּזֿ דֻב ו׀ֹ ַבַזַדׁ ׃םÊד Êֽזםבוַ ַבם ÝַׁÛַÊ<br />(ÊÝÚםב ו׀ַ ַבַדׁ Ýם Ûַםֹ ַבַודםֹ)');
define($constpref.'_DIE_BADEXT','ÊזÞÝ !! Ýם ַֽבֹ ַßÊװַÝ דבÝ  ײַׁ Êד ׁÝÚֹ ַָבדזÞÚ');
define($constpref.'_DIE_BADEXTDSC','Ýם ַֽבֹ דַֽזבֹ ׁÝÚ ַם דבÝ Ûםׁ דױֽׁ ָו דֻבַ דבÝַÊ ַבָם ַÊװ ָם ׃םÞזד ַבָׁהַדּ ַָב־ׁזּ דה ַבדזÞÚ זַָ״ַב ַדׁ ַבׁÝÚ<br />Þד ָÊÚ״םב ַבַדׁ ַ׀ ßהÊ ÊׁÛָ ָׁÝÚ דבÝַÊ דבÝַÊ ַָדÊַֿֿם ָם ַÊװ ָם');
define($constpref.'_CONTAMI_ACTION','ַבÚדב Ýם ַֽבֹ זּזֿ Êבזֻ זַדׁ ־ָםֻ');
define($constpref.'_CONTAMI_ACTIONDS','ַ־Êַׁ ַבַדׁ ַב׀ם Êׁםֿ ײוזֹׁ בבדוַּד Ýם ַֽבֹ ַßÊװַÝ ַדׁ ־ָםֻ  ַבַÝײב ַ־Êםַׁ Ùוזׁ ױÝֹֽ ָםײֱַ  ÝַׁÛֹ');
define($constpref.'_ISOCOM_ACTION','ַבÚדב Ýם ַֽבֹ ַßÊװַÝ ַזַדׁ Ûםׁ דױֽׁ ָוַ Ýם ַבÊÚבםÞַÊ');
define($constpref.'_ISOCOM_ACTIONDSC','דַהÚ ַבֽÞה Ýם ÞַÚֹֿ ַבָםַהַÊ<br />ַ־Êַׁ ַבַדׁ Ýם ַֽבֹ ַßÊװַÝ ַדׁ ־ָםֻ ַָבÊÚבםÞ דֻב "/*" ַה זּֿ<br />"ÊÚÞםד ַבַדׁ" םדÚהם ַÙַÝֹ  ַבׁדׂ "*/" Ýם ַבהוַםֹ<br />(םהױֽ ַָ־Êםַׁ ÊÚÞםד ַבַדׁ)');
define($constpref.'_UNION_ACTION',' Union ַבÚדב Ýם ַֽבֹ זּזֿ ַזַדׁ ַבַÊַֽֿ ');
define($constpref.'_UNION_ACTIONDSC','דײַֿ וּדַÊ ֽÞה ÞַÚֹֿ ַבָםַהַÊ:<br />ַבÚדב Ýם ַֽבֹ ַßÊװַÝ דֻב ו׀ַ ַבֽÞה ַָבÞַÚֹֿ.<br />"uni-on" ַבם "union"ÊÚÞםד ַבַדׁ" ָדÚהם ÊÛםׁ ßבדֹ" <br />(םהױֽ ַָ־Êםַׁ ÊÚÞםד ַבַדׁ)');
define($constpref.'_ID_INTVAL',' Select ID ַבÚדב Ýם ַֽבֹ ״בָ ַדׁ דה ÞַÚֹֿ ַבָםַהַÊ  דֻבַ ״בָ ַבַדׁ ');
define($constpref.'_ID_INTVALDSC','ַדׁ Ûםׁ װׁÚם "*id" ׃םÚÊָׁ ַבַדׁ<br />ו׀ַ ַב־םַׁ ׃םֽדםß דה ָÚײ ַבוּדַÊ  זַבֽÞה בÞַÚֹֿ ַבָםַהַÊ<br />דה ַבַÝײב ÊÚ״םב ו׀ַ ַבַדׁ ֽםֻ ַהו םÊ׃ָָ ַָםÞַÝ Úדב ָÚײ ַבדזֿםבַÊ ַבַ־ׁל דֻב Þזֶַד ַבָׁםֿ זדזֿםבַÊ ַבÝםֿםז');
define($constpref.'_FILE_DOTDOT','ַבֽדַםֹ דה ַבדּבַֿÊ ַבדÊהÞבֹ');
define($constpref.'_FILE_DOTDOTDSC','דהÚ ßב ַבַזַדׁ ַבÊל ÊßÊװÝ Úבל ַהוַ ַזַדׁ הÞב דּבַֿÊ ָװßב דÊßׁׁ');

define($constpref.'_BF_COUNT','דַהÚ Êßַׁׁ ַ׃ד ַבֿ־זב');
define($constpref.'_BF_COUNTDSC','ײÚ Úֿֿ ַבדַֽזבַÊ ַבÊם םÞזד ָוַ Ú׀ז דÚםה ַבֿ־זב ַָ׃דו זßבדֹ ַב׃ׁ ָװßב ־ַ״םֱ Þָב ״ֿׁו דה ַבדזÞÚ זַÚÊַָׁו Úײז םַֽזב ׃ׁÞֹ ַָ׃זֿׁ Úײז ַ־ׁ');

define($constpref.'_DOS_SKIPMODS','ַבדזֿםבַÊ ַבד׃Êֻהַֹ דה דֽׁßַÊ ַבָֻֽ זÊÝֽױ ַבֿז׃');
define($constpref.'_DOS_SKIPMODSDSC','ײÚ ַ׃דֱַ ַבדזֿםבַÊ זַÝױבוַ ָÚבַדֹ|. ו׀ַ ַבַ־Êםַׁ םהÝÚ Ýם ַֽבֹ זּזֿ דזֿםבַÊ ֿֿׁװֹ זדַֹֻֽֿ ');

define($constpref.'_DOS_EXPIRE','דַׁÞָֹ דַׁÊ Êֽדםב ַבױÝַֽÊ ַָבֻזַהם');
define($constpref.'_DOS_EXPIREDSC','f5 דהÚ וּדַÊ ÊÚֿֿ ״בָ ַבױÝֹֽ ַבַזבם זַבַדַׁב׀ם םÚםֿ Êֽֿםֻ ַבױÝֹֽ דה ַבßםָזֿׁ ');

define($constpref.'_DOS_F5COUNT','F5  Úֿֿ ַבדַׁÊ בַֽÊ׃ַָוַ וּדֹ דה Þָב  ַבַדׁ');
define($constpref.'_DOS_F5COUNTDSC','ַÚַֹֿ Êֽדםב ַבױÝֹֽ ַßֻׁ דה ַבׁÞד ַבד׀ßזׁ ַָבַÚבל ׃םÚÊָׁ וּדֹ  דה ־בַב ַבֿז׃');
define($constpref.'_DOS_F5ACTION','F5  ַבÚדב ַבדײַֿ ַֽבֹ ַßÊװַÝ וּדֹ דה הזÚ');

define($constpref.'_DOS_CRCOUNT','Úֿֿ Úדבםֹ ַבÝוׁ׃ֹ דה Þָב דֽׁßַÊ ַבָֻֽ');
define($constpref.'_DOS_CRCOUNTDSC','בדהÚ דֽׁßַÊ ַבָֻֽ ַב׃םֶֹ דה ַַֻֽֿ ױÛ״ Úבל ַבדזÞÚ');
define($constpref.'_DOS_CRACTION','ַבÚדב Ýם ַֽבֹ ַßÊװַÝ דֽׁßַÊ Ýוׁ׃ֹ ַז ַבָֻֽ ד׃ָָֹ בײÛ״ Úבל ַבדזÞÚ');

define($constpref.'_DOS_CRSAFE','דֽׁßַÊ ַבָֻֽ ַבדָֽזָֹ זַבדָֽׁ ָוַ');
define($constpref.'_DOS_CRSAFEDSC','דֽׁßַÊ ַבָֻֽ ַבדָֽזָֹ זַבÊם ÊÚÊָׁ ֻÞֹ זבַ Ê׃ָָ ַ׀ם בבדזÞÚ דֻב דֽׁß ָֻֽ ַבםַוז זַבּזּב ַה ßַה בֿםß דֽׁßַÊ ַ־ׁל דזֻזÞֹ ײÚוַ Ýם ַבַÚבל בםÊד ַבÊÚַדב דÚוַ Úבל ַהוַ ֽדםֹֿ');

define($constpref.'_OPT_NONE','בַװםֱ');
define($constpref.'_OPT_SAN','ÊÚÞםד ַבַדׁ');
define($constpref.'_OPT_EXIT','ױÝֹֽ ÝַׁÛֹ ָםײֱַ');
define($constpref.'_OPT_BIP','״ֿׁ ַבַםָם');

define($constpref.'_DOSOPT_NONE','בַװםֱ');
define($constpref.'_DOSOPT_SLEEP','Úֿ ד ַ׃Êַָֹּ');
define($constpref.'_DOSOPT_EXIT','ױÝֹֽ ÝַׁÛֹ ָםײֱַ');
define($constpref.'_DOSOPT_BIP','״ֿׁ ַבַםָם');
define($constpref.'_DOSOPT_HTA','״ֿׁ ָזַ׃״ֹ דבÝ.htaccess(בַױַָֽ ַב־ָֹׁ)');

define($constpref.'_BIP_EXCEPT','ַבדּדזÚֹ ַבÊם בה םÊד ״ֿׁוַ ַַָֿ');
define($constpref.'_BIP_EXCEPTDSC','ַם Úײז ָוֹ ַבדּדזÚֹ בה םÊד ״ֹֿׁ זםהױֽ ַָ־Êםַׁ דּדזÚֹ ַבַַֹֿׁ');

define($constpref.'_DISABLES','ÊÚ״םב ָÚײ ַבדַׂםֱַ דה דּבֹ ׂזָ׃ ');

define($constpref.'_BIGUMBRELLA','ÊÝÚםב anti-XSS (ַבדײבֹ)');
define($constpref.'_BIGUMBRELLADSC',' ו׀ַ ַבהזÚ דה ַבוּדַÊ םÞזד ָ׃ׁÞֹ ַבßזßםׂ דה ־בַב ַזדׁ ַּÝַ ד־ÊבÝֹ זםÞזד ָוּדַÊ  ַ־ׁל םÚÊָׁ דה ַ־״ׁ ַבוּדַÊ  ב׃ׁÞֹ ָׁםֿ ַבÚײז Úבל ַבםַוז זַבוזÊדםב זÛםׁוַ');

define($constpref.'_SPAMURI4U',' Spam Úֿֿ ַבׁזַָ״ ַָם דזײזÚ ßַÊָֹ Úײז ד׃ּב ַָבדזÞÚ  Þָב ַÚÊַָׁוַ ׃ַָד');
define($constpref.'_SPAMURI4UDSC','ו׀ַ ַבַדׁ םהÝÚ Ýם ַֽבֹ זּזֿ ׂזַׁ םײÚזה ׁזַָ״ דÊÚֿֿו  ָÞױֿ ַבֿÚַםֹ ײÚ ׁÞד ױÝׁ בÊÚ״םב ַבַדׁ');
define($constpref.'_SPAMURI4G','Spam Úֿֿ ַבׁזַָ״ ַָם דזײזÚ ßַÊָֹ Úײז ֶַׁׂ ַָבדזÞÚ  Þָב ַÚÊַָׁוַ ׃ַָד');
define($constpref.'_SPAMURI4GDSC','ו׀ַ ַבַדׁ םהÝÚ Ýם ַֽבֹ זּזֿ ׂזַׁ םײÚזה ׁזַָ״ דÊÚֿֿו  ָÞױֿ ַבֿÚַםֹ ײÚ ׁÞד ױÝׁ בÊÚ״םב ַבַדׁ');

}

?>