<?php
/**
 * Preload item base class.
 *
 * Class which is extended by any preload item. This class is empty for now but is there for
 * extended future purposes.
 *
 * @copyright   The ImpressCMS Project http://www.impresscms.org/
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category    ICMS
 * @package     Preload
 * @since       1.1
 * @author      marcan <marcan@impresscms.org>
 * @version     SVN: $Id: Item.php 10326 2010-07-11 18:54:25Z malanciault $
 */

declare(strict_types=1);

namespace Icms\Preload;

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * Preload item (formerly icms_preload_Item).
 *
 * @category    ICMS
 * @package     Preload
 */
class Item
{
    public function __construct()
    {
    }
}

\class_alias(Item::class, 'icms_preload_Item');
