<?php
/**
 * Function for removing users that have not activated their accounts
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @subpackage	Autotasks
 */

/**
 * Deletes users who registered but aren't yet active for X days.
 *
 * To be used in ImpressCMS 1.2
 * @return mixed Did the query succeed or not? Returns nothing if succeeded, false if not succeeded
 */
function remove_usersxdays() {
	$db =& icms_db_Factory::instance();
	global $icmsConfigUser;
	$days = $icmsConfigUser['delusers'];
	$delete_regdate= time() - ($days * 24 * 60 * 60);  // X days/month * 24 hrs/day
	$sql = sprintf("DELETE FROM %s WHERE (level = '0' AND user_regdate < '%s')", $db->prefix('users'), $delete_regdate);
	if (!$result = $db->queryF($sql)) {
		return FALSE;
	}
}

/*
 * Here comes the part for removing inactive users after X days.
 * I used to ad it here, because it is always loaded within core loading.
 * I also made a condition to run the function, only when system is not doing a GET action!
 */
remove_usersxdays();
