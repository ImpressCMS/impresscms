<?php
/**
 * Page handling HTTP errors
 *
 * This page handles some HTTP errors that may occur on a site. The htaccess file needs to be
 * edited as well. An example of such htaccess can be found in htaccess.txt.
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	core
 * @since	1.0
 * @author	young-pee <nekro@impresscms.org>
 * @author	malanciault <marcan@impresscms.org)
 */

$xoopsOption['pagetype'] = 'error';

include_once __DIR__ . '/mainfile.php';

$icmsResponse = new \icms_response_Error($xoopsOption);
$icmsResponse->errorNo = isset($_REQUEST['e']) ? $_REQUEST['e'] : 500;
$icmsResponse->render();