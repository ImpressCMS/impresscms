<?php
/**
 * XOOPS authentification class
 * Authorization classes, xoops authorization class file
 *
 * @copyright   http://www.xoops.org/ The XOOPS Project
 * @copyright   http://www.impresscms.org/ The ImpressCMS Project
 * @license     LICENSE.txt
 * @category    ICMS
 * @package     Auth
 * @subpackage  Xoops
 * @since       XOOPS
 * @author      http://www.xoops.org The XOOPS Project
 * @author      modified by UnderDog <underdog@impresscms.org>
 * @version     SVN: $Id: Xoops.php 12313 2013-09-15 21:14:35Z skenow $
 */

declare(strict_types=1);

namespace Icms\Auth;

/**
 * Authentification class for Native XOOPS (formerly icms_auth_Xoops).
 *
 * @category    ICMS
 * @package     Auth
 * @subpackage  Xoops
 * @author      Pierre-Eric MENUET <pemphp@free.fr>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 */
class Xoops extends Entity
{
    /**
     * Authentication method identifier.
     */
    public $auth_method;

    /**
     * Authentication Service constructor
     *
     * @param object $dao reference to dao object
     */
    public function __construct(&$dao)
    {
        $this->_dao = $dao;
        $this->auth_method = 'xoops';
    }

    /**
     * Authenticate user
     *
     * @param string $uname
     * @param string $pwd
     * @return object {@link icms_member_user_Object} icms_member_user_Object object
     */
    public function authenticate($uname, $pwd = null)
    {
        $member_handler = \icms::handler('icms_member');
        $user = $member_handler->loginUser($uname, $pwd);
        \icms::$session->enableRegenerateId = true;
        \icms::$session->sessionOpen();
        if ($user == false) {
            \icms::$session->destroy(session_id());
            $this->setErrors(1, _US_INCORRECTLOGIN);
        }
        return $user;
    }
}

\class_alias(Xoops::class, 'icms_auth_Xoops');
