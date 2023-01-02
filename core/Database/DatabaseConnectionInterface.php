<?php
/**
 * DatabaseConnectionInterface interface definition
 *
 * @copyright   The ImpressCMS Project <http://www.impresscms.org>
 * @license        http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */

namespace ImpressCMS\Core\Database;

use Aura\Sql\ExtendedPdoInterface;

/**
 * Interface for database adapters.
 *
 * All the methods in this class are PDO methods, with the exception of escape, which is a legacy method
 *
 * @since 1.4
 * @package    ICMS\Database
 */
interface DatabaseConnectionInterface extends ExtendedPdoInterface
{

}
