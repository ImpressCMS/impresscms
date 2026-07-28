<?php
/**
 * Classes responsible for managing core page objects
 *
 * @copyright   The ImpressCMS Project <http://www.impresscms.org/>
 * @license     LICENSE.txt
 * @category    ICMS
 * @package     Data
 * @subpackage  Page
 * @since       ImpressCMS 1.1
 * @author      modified by UnderDog <underdog@impresscms.org>
 * @author      Gustavo Pilla (aka nekro) <nekro@impresscms.org> <gpilla@nubee.com.ar>
 * @version     SVN: $Id:Object.php 19775 2010-07-11 18:54:25Z malanciault $
 */

declare(strict_types=1);

namespace Icms\Data\Page;

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * Page entity (formerly icms_data_page_Object).
 *
 * @category    ICMS
 * @package     Data
 * @subpackage  Page
 */
class Entity extends \Icms\Ipf\Entity
{
    public function __construct($handler)
    {
        parent::__construct($handler);

        $this->quickInitVar('page_id', XOBJ_DTYPE_INT);
        $this->quickInitVar('page_moduleid', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('page_title', XOBJ_DTYPE_TXTBOX, true);
        $this->quickInitVar('page_url', XOBJ_DTYPE_TXTBOX, true);
        $this->quickInitVar('page_status', XOBJ_DTYPE_INT, true);
    }
}

\class_alias(Entity::class, 'icms_data_page_Object');
