<?php
/**
 * Page handling HTTP errors
 *
 * This page handles some HTTP errors that may occur on a site. The htaccess file needs to be
 * edited as well. An example of such htaccess can be found in htaccess.txt.
 *
 * @copyright    The ImpressCMS Project http://www.impresscms.org/
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package    ImpressCMS\Core
 * @since    1.0
 */

$xoopsOption['pagetype'] = 'error';

define('ICMS_PUBLIC_PATH', __DIR__);

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'mainfile.php';

\icms::$response = new \icms_response_Error($xoopsOption);
\icms::$response->errorNo = isset($_REQUEST['e']) ? (int)$_REQUEST['e'] : 500;
if (isset($_REQUEST['msg'])) {
	\icms::$response->msg = $_REQUEST['msg'];
}
\icms::$response->render();