<?php
/**
 * Configuration settings for LDAP authentication store
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Authentication
 * @subpackage	LDAP
 * @version		SVN: $Id: Factory.php 10809 2010-11-20 22:28:44Z phoenyx $
 */

/*
  	// Data for Config Category 7 (Authentication Settings)
	$c=7; // sets config category id
	$p=0; // reset position increment to 0 for new category id
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'auth_method','_MD_AM_AUTHMETHOD','xoops','_MD_AM_AUTHMETHODDESC','select','text', " . $p++ . ")");
	// Insert data for Config Options in selection field. (must be placed before //$i++)
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_AUTH_CONFOPTION_XOOPS', 'xoops', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_AUTH_CONFOPTION_LDAP', 'ldap', $i)");
		$dbm->insert('configoption', " VALUES (" . $ci++ . ", '_MD_AM_AUTH_CONFOPTION_AD', 'ads', $i)");
	// ----------
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'auth_openid','_MD_AM_AUTHOPENID','0','_MD_AM_AUTHOPENIDDSC','yesno','int', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_port','_MD_AM_LDAP_PORT','389','_MD_AM_LDAP_PORT','textbox','int', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_server','_MD_AM_LDAP_SERVER','your directory server','_MD_AM_LDAP_SERVER_DESC','textbox','text', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_base_dn','_MD_AM_LDAP_BASE_DN','dc=icms,dc=org','_MD_AM_LDAP_BASE_DN_DESC','textbox','text', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_manager_dn','_MD_AM_LDAP_MANAGER_DN','manager_dn','_MD_AM_LDAP_MANAGER_DN_DESC','textbox','text', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_manager_pass','_MD_AM_LDAP_MANAGER_PASS','manager_pass','_MD_AM_LDAP_MANAGER_PASS_DESC','password','text', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_version','_MD_AM_LDAP_VERSION','3','_MD_AM_LDAP_VERSION_DESC','textbox','text', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_users_bypass','_MD_AM_LDAP_USERS_BYPASS','".serialize(array('admin'))."','_MD_AM_LDAP_USERS_BYPASS_DESC','textsarea','array', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_loginname_asdn','_MD_AM_LDAP_LOGINNAME_ASDN','uid_asdn','_MD_AM_LDAP_LOGINNAME_ASDN_D','yesno','int', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_loginldap_attr', '_MD_AM_LDAP_LOGINLDAP_ATTR', 'uid', '_MD_AM_LDAP_LOGINLDAP_ATTR_D', 'textbox', 'text', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_filter_person','_MD_AM_LDAP_FILTER_PERSON','','_MD_AM_LDAP_FILTER_PERSON_DESC','textbox','text', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_domain_name','_MD_AM_LDAP_DOMAIN_NAME','mydomain','_MD_AM_LDAP_DOMAIN_NAME_DESC','textbox','text', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_provisionning','_MD_AM_LDAP_PROVIS','0','_MD_AM_LDAP_PROVIS_DESC','yesno','int', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_provisionning_group','_MD_AM_LDAP_PROVIS_GROUP','a:1:{i:0;s:1:\"2\";}','_MD_AM_LDAP_PROVIS_GROUP_DSC','group_multi','array', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_mail_attr','_MD_AM_LDAP_MAIL_ATTR','mail','_MD_AM_LDAP_MAIL_ATTR_DESC','textbox','text', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_givenname_attr','_MD_AM_LDAP_GIVENNAME_ATTR','givenname','_MD_AM_LDAP_GIVENNAME_ATTR_DSC','textbox','text', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_surname_attr','_MD_AM_LDAP_SURNAME_ATTR','sn','_MD_AM_LDAP_SURNAME_ATTR_DESC','textbox','text', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_field_mapping','_MD_AM_LDAP_FIELD_MAPPING_ATTR','email=mail|name=displayname','_MD_AM_LDAP_FIELD_MAPPING_DESC','textsarea','text', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_provisionning_upd', '_MD_AM_LDAP_PROVIS_UPD', '1', '_MD_AM_LDAP_PROVIS_UPD_DESC', 'yesno', 'int', " . $p++ . ")");
	$dbm->insert('config', " VALUES (" . ++$i . ",0,$c,'ldap_use_TLS','_MD_AM_LDAP_USETLS','0','_MD_AM_LDAP_USETLS_DESC','yesno','int', " . $p++ . ")");

 */