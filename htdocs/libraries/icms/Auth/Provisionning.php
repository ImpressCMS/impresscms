<?php
/**
 * Authorization classes, provisioning class file
 *
 * @copyright   http://www.impresscms.org/ The ImpressCMS Project
 * @license     LICENSE.txt
 * @category    ICMS
 * @package     Auth
 * @version     SVN: $Id: Provisionning.php 12313 2013-09-15 21:14:35Z skenow $
 */

declare(strict_types=1);

namespace Icms\Auth;

/**
 * Authentification provisionning class (formerly icms_auth_Provisionning).
 *
 * This class is responsible to provide synchronisation method to the user Database.
 *
 * @copyright   http://www.xoops.org/ The XOOPS Project
 * @copyright   http://www.impresscms.org/ The ImpressCMS Project
 * @since       XOOPS
 * @category    ICMS
 * @package     Auth
 * @author      http://www.xoops.org The XOOPS Project
 * @author      Pierre-Eric MENUET <pemphp@free.fr>
 */
class Provisionning
{
    private $_auth_instance;

    /**
     * Gets instance of {@link Provisionning}.
     *
     * @param object $auth_instance
     * @return object $provis_instance
     */
    public static function &getInstance(&$auth_instance)
    {
        static $provis_instance;
        if (!isset($provis_instance)) {
            $provis_instance = new self($auth_instance);
        }
        return $provis_instance;
    }

    /**
     * Authentication Service constructor.
     *
     * @param object $auth_instance
     */
    public function __construct(&$auth_instance)
    {
        $this->_auth_instance = &$auth_instance;
        global $icmsConfig, $icmsConfigAuth;
        foreach ($icmsConfigAuth as $key => $val) {
            $this->$key = $val;
        }
        $this->default_TZ = $icmsConfig['default_TZ'];
        $this->theme_set = $icmsConfig['theme_set'];
        $this->com_mode = $icmsConfig['com_mode'];
        $this->com_order = $icmsConfig['com_order'];
    }

    /**
     * Return a User Object.
     *
     * @param string $uname Username of the user
     * @return mixed icms_member_user_Object {@link icms_member_user_Object} or false if failed
     */
    public function geticms_member_user_Object($uname)
    {
        $member_handler = \icms::handler('icms_member');
        $criteria = new \icms_db_criteria_Item('uname', $uname);
        $getuser = $member_handler->getUsers($criteria);
        if (count($getuser) == 1) {
            return $getuser[0];
        } else {
            return false;
        }
    }

    /**
     * Launch the synchronisation process.
     *
     * @param array $datas Some Data
     * @param string $uname Username of the user
     * @param string $pwd Password of the user
     * @return object icms_member_user_Object {@link icms_member_user_Object}
     */
    public function sync($datas, $uname, $pwd = null)
    {
        $icmsUser = $this->geticms_member_user_Object($uname);
        if (!$icmsUser) {
            // User Database not exists
            if ($this->ldap_provisionning) {
                $icmsUser = $this->add($datas, $uname, $pwd);
            } else {
                $this->_auth_instance->setErrors(0, sprintf(_AUTH_LDAP_XOOPS_USER_NOTFOUND, $uname));
            }
        } else {
            // User Database exists
            if ($this->ldap_provisionning && $this->ldap_provisionning_upd) {
                $icmsUser = $this->change($icmsUser, $datas, $uname, $pwd);
            }
        }
        return $icmsUser;
    }

    /**
     * Adds a new user to the system.
     *
     * @param array $datas Some Data
     * @param string $uname Username of the user
     * @param string $pwd Password of the user
     * @return array|bool|object
     */
    public function add($datas, $uname, $pwd = null)
    {
        $ret = false;
        $member_handler = \icms::handler('icms_member');
        // Create ImpressCMS Database User
        $newuser = $member_handler->createUser();
        $newuser->setVar('uname', $uname);
        $newuser->setVar('pass', md5(stripslashes($pwd)));
        $newuser->setVar('rank', 0);
        $newuser->setVar('level', 1);
        $newuser->setVar('timezone_offset', $this->default_TZ);
        $newuser->setVar('theme', $this->theme_set);
        $newuser->setVar('umode', $this->com_mode);
        $newuser->setVar('uorder', $this->com_order);
        $tab_mapping = explode('|', $this->ldap_field_mapping);
        foreach ($tab_mapping as $mapping) {
            $fields = explode('=', trim($mapping));
            if ($fields[0] && $fields[1]) {
                // utf8_decode() was removed in PHP 8.2; mb_convert_encoding() is the direct replacement.
                $newuser->setVar(trim($fields[0]), mb_convert_encoding($datas[trim($fields[1])][0], 'ISO-8859-1', 'UTF-8'));
            }
        }
        if ($member_handler->insertUser($newuser)) {
            foreach ($this->ldap_provisionning_group as $groupid) {
                $member_handler->addUserToGroup($groupid, $newuser->getVar('uid'));
            }
            $newuser->unsetNew();
            return $newuser;
        } else {
            redirect_header(ICMS_URL . '/user.php', 5, $newuser->getHtmlErrors());
        }
        return $ret;
    }

    /**
     * Modify user information.
     *
     * @param object $icmsUser reference to icms_member_user_Object Object
     * @param array $datas Some Data
     * @param string $uname Username of the user
     * @param string $pwd Password of the user
     * @return object icms_member_user_Object {@link icms_member_user_Object}
     */
    public function change(&$icmsUser, $datas, $uname, $pwd = null)
    {
        $ret = false;
        $member_handler = \icms::handler('icms_member');
        $icmsUser->setVar('pass', md5(stripslashes($pwd)));
        $tab_mapping = explode('|', $this->ldap_field_mapping);
        foreach ($tab_mapping as $mapping) {
            $fields = explode('=', trim($mapping));
            if ($fields[0] && $fields[1]) {
                // utf8_decode() was removed in PHP 8.2; mb_convert_encoding() is the direct replacement.
                $icmsUser->setVar(trim($fields[0]), mb_convert_encoding($datas[trim($fields[1])][0], 'ISO-8859-1', 'UTF-8'));
            }
        }
        if ($member_handler->insertUser($icmsUser)) {
            return $icmsUser;
        } else {
            redirect_header(ICMS_URL . '/user.php', 5, $icmsUser->getHtmlErrors());
        }
        return $ret;
    }
}

\class_alias(Provisionning::class, 'icms_auth_Provisionning');
