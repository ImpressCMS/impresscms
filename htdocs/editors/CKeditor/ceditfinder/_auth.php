<?php
/**
 * ImpressCMS authentication for CKEditor
 * Specify authentication and configuration settings based on access level
 *
 * @copyright	ImpressCMS
 * @license
 * @category	ICMS
 * @package		Editors
 * @subpackage	CKEditor
 *
 */
/** Be sure this is not being accessed inappropriately */
defined('ceditFinder') or die('Restricted access');

/* turn off logging in the display */

icms::$logger->disableLogger();

$groups = is_object(icms::$user) ? icms::$user->getGroups() : ICMS_GROUP_ANONYMOUS;
$gperm = icms::handler('icms_member_groupperm');

$agroups = $gperm->getItemIds('use_wysiwygeditor', $groups);

if (count($agroups) == 0) {die(_NOPERM);}