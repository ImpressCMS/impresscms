<?php
/**
 * UrlLink Handler
 *
 * @copyright   http://www.impresscms.org/ The ImpressCMS Project
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category    ICMS
 * @package     Data
 * @subpackage  Urllink
 * @since       1.3
 * @author      Phoenyx
 * @version     $Id: Handler.php 10849 2010-12-05 18:46:02Z phoenyx $
 */

declare(strict_types=1);

namespace Icms\Data\Urllink;

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

/**
 * UrlLink handler class.
 *
 * @category    ICMS
 * @package     Data
 * @subpackage  Urllink
 */
class Handler extends \Icms\Ipf\Handler
{
    /**
     * Constructor.
     *
     * @param object $db database connection
     */
    public function __construct($db)
    {
        parent::__construct($db, "data_urllink", "urllinkid", "caption", "desc", "icms");
    }
}

\class_alias(Handler::class, 'icms_data_urllink_Handler');
