<?php
/**
 * Functions needed by the ImpressCMS installer
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		installer
 * @author		marcan <marcan@impresscms.org>
 * @author		modified by UnderDog <underdog@impresscms.org>
 * @version		$Id: functions.php 12329 2013-09-19 13:53:36Z skenow $
 */


/**
 * Function to get the base domain name from a URL.
 * credit for this function should goto Phosphorus and Lime, it is released under GPL (v2).
 * http://phosphorusandlime.blogspot.com/2007/08/php-get-base-domain.html
 *
 * @param string $url the URL to be stripped.
 * @return string
 */
function imcms_get_base_domain($url): string
{
	$base_domain = '';

	// generic tlds (source: http://en.wikipedia.org/wiki/Generic_top-level_domain)
	$G_TLD = array(
     'biz','com','edu','gov','info','int','mil','name','net','org','aero','asia','cat','coop','jobs','mobi','museum','pro','tel','travel',
     'arpa','root','berlin','bzh','cym','gal','geo','kid','kids','lat','mail','nyc','post','sco','web','xxx',
     'nato', 'example','invalid','localhost','test','bitnet','csnet','ip','local','onion','uucp','co');

	// country tlds (source: http://en.wikipedia.org/wiki/Country_code_top-level_domain)
	$C_TLD = array(
	// active
     'ac','ad','ae','af','ag','ai','al','am','an','ao','aq','ar','as','at','au','aw','ax','az',
     'ba','bb','bd','be','bf','bg','bh','bi','bj','bm','bn','bo','br','bs','bt','bw','by','bz',
     'ca','cc','cd','cf','cg','ch','ci','ck','cl','cm','cn','co','cr','cu','cv','cx','cy','cz',
     'de','dj','dk','dm','do','dz','ec','ee','eg','er','es','et','eu','fi','fj','fk','fm','fo',
     'fr','ga','gd','ge','gf','gg','gh','gi','gl','gm','gn','gp','gq','gr','gs','gt','gu','gw',
     'gy','hk','hm','hn','hr','ht','hu','id','ie','il','im','in','io','iq','ir','is','it','je',
     'jm','jo','jp','ke','kg','kh','ki','km','kn','kr','kw','ky','kz','la','lb','lc','li','lk',
     'lr','ls','lt','lu','lv','ly','ma','mc','md','mg','mh','mk','ml','mm','mn','mo','mp','mq',
     'mr','ms','mt','mu','mv','mw','mx','my','mz','na','nc','ne','nf','ng','ni','nl','no','np',
     'nr','nu','nz','om','pa','pe','pf','pg','ph','pk','pl','pn','pr','ps','pt','pw','py','qa',
     're','ro','ru','rw','sa','sb','sc','sd','se','sg','sh','si','sk','sl','sm','sn','sr','st',
     'sv','sy','sz','tc','td','tf','tg','th','tj','tk','tl','tm','tn','to','tr','tt','tv','tw',
     'tz','ua','ug','uk','us','uy','uz','va','vc','ve','vg','vi','vn','vu','wf','ws','ye','yu',
     'za','zm','zw',
	// inactive
     'eh','kp','me','rs','um','bv','gb','pm','sj','so','yt','su','tp','bu','cs','dd','zr');

	// get domain
	if (!$full_domain = imcms_get_url_domain($url)) {
		return $base_domain;
	}

	// break up domain, reverse
	$DOMAIN = explode('.', $full_domain);
	$DOMAIN = array_reverse($DOMAIN);

	// first check for ip address
	if (count($DOMAIN) === 4 && is_numeric($DOMAIN[0]) && is_numeric($DOMAIN[3])) {
		return $full_domain;
	}

	// if only 2 domain parts, that must be our domain
	if (count($DOMAIN) <= 2) {
		return $full_domain;
	}

	/*
	 finally, with 3+ domain parts: obviously D0 is tld now,
	 if D0 = ctld and D1 = gtld, we might have something like com.uk so,
	 if D0 = ctld && D1 = gtld && D2 != 'www', domain = D2.D1.D0 else if D0 = ctld && D1 = gtld && D2 == 'www',
	 domain = D1.D0 else domain = D1.D0 - these rules are simplified below.
	 */
	if (in_array($DOMAIN[0], $C_TLD) && in_array($DOMAIN[1], $G_TLD) && $DOMAIN[2] !== 'www')
	{
		$full_domain = $DOMAIN[2].'.'.$DOMAIN[1].'.'.$DOMAIN[0];
	} else {
		$full_domain = $DOMAIN[1].'.'.$DOMAIN[0];
	}
	// did we succeed?
	return $full_domain;
}

/**
 * Function to get the domain from a URL.
 * credit for this function should goto Phosphorus and Lime, it is released under GPL (v2).
 * http://phosphorusandlime.blogspot.com/2007/08/php-get-base-domain.html
 *
 * @param string $url the URL to be stripped.
 * @return string
 */
function imcms_get_url_domain($url): string
{
	$_URL = parse_url((string) $url);

	return $_URL['host'] ?? '';
}

/**
 * Opens a PDO connection to the MySQL/MariaDB server using the settings stored by the installer.
 *
 * @param array $vars Installer settings (DB_HOST, DB_USER, DB_PASS and optionally DB_PCONNECT)
 * @return PDO
 * @throws PDOException If the connection could not be established
 */
function icms_install_db_connect(array $vars): PDO
{
	return new PDO(
		'mysql:host=' . ($vars['DB_HOST'] ?? ''),
		$vars['DB_USER'] ?? '',
		$vars['DB_PASS'] ?? '',
		[
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_PERSISTENT => !empty($vars['DB_PCONNECT']),
		]
	);
}

/**
 * Replaces the value of a define() call in the given source code.
 *
 * @param string $content Source code
 * @param string $constant Name of the constant
 * @param string|int $value New value (written unquoted only for integers replacing an unquoted number, or when $raw is true)
 * @param bool $raw Insert the value as is (for example to reference another constant)
 * @return string|null The modified source code, or null if the constant was not found
 */
function icms_install_rewrite_define(string $content, string $constant, $value, bool $raw = false): ?string
{
	$pattern = '/define\(\s*([\'"])' . preg_quote($constant, '/') . '\1\s*,\s*(?:[0-9]+|([\'"])(.*?)\2)\s*\)/';
	if (!preg_match($pattern, $content)) {
		return null;
	}
	return preg_replace_callback($pattern, static function (array $matches) use ($constant, $value, $raw): string {
		$isQuoted = ($matches[2] ?? '') !== '';
		if ($raw || (is_int($value) && !$isQuoted)) {
			$replacement = (string) $value;
		} else {
			$replacement = "'" . addcslashes((string) $value, "\\'") . "'";
		}
		return "define('$constant', $replacement)";
	}, $content);
}

