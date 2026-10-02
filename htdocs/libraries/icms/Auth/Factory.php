<?php
/**
 * Authorization classes, factory class file
 *
 * @copyright   http://www.impresscms.org/ The ImpressCMS Project
 * @license     LICENSE.txt
 * @category    ICMS
 * @package     Auth
 * @author      modified by UnderDog <underdog@impresscms.org>
 * @version     SVN: $Id: Factory.php 12313 2013-09-15 21:14:35Z skenow $
 */

declare(strict_types=1);

namespace Icms\Auth;

/**
 * Authentification class factory (formerly icms_auth_Factory).
 *
 * @copyright   http://www.xoops.org/ The XOOPS Project
 * @copyright   http://www.impresscms.org/ The ImpressCMS Project
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since       XOOPS
 * @category    ICMS
 * @package     Auth
 * @author      Pierre-Eric MENUET <pemphp@free.fr>
 */
class Factory
{
    /**
     * Get a reference to the only instance of authentication class.
     *
     * If the class has not been instantiated yet, this will also take
     * care of that.
     *
     * @param string $uname Username to get Authentication class for
     * @return object Reference to the only instance of authentication class
     */
    public static function &getAuthConnection($uname)
    {
        static $auth_instance;
        if (isset($auth_instance)) {
            return $auth_instance;
        } else {
            global $icmsConfigAuth;

            if (empty($icmsConfigAuth['auth_method'])) {
                // If there is a config error, we use xoops
                $auth_method = 'xoops';
            } else {
                $auth_method = $icmsConfigAuth['auth_method'];
                /*
                 * @todo we need to add this in the preference
                 */
            }
            // Verify if uname allow to bypass LDAP auth
            if (in_array($uname, $icmsConfigAuth['ldap_users_bypass'])) {
                $auth_method = 'xoops';
            }
            /* with autoloading in ImpressCMS 1.3, requiring the file is not necessary */
            $class = 'icms_auth_' . ucfirst($auth_method);
            switch ($auth_method) {
                case 'xoops':
                    $dao = &\icms::$xoopsDB;
                    break;

                case 'ldap':
                    $dao = null;
                    break;

                case 'ads':
                    $dao = null;
                    break;

                default:
                    break;
            }
            $auth_instance = new $class($dao);
            return $auth_instance;
        }
    }
}

\class_alias(Factory::class, 'icms_auth_Factory');
