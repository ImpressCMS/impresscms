<?php
/**
 * Manage Notifications
 *
 * @license     LICENSE.txt
 * @copyright   http://www.impresscms.org/ The ImpressCMS Project
 * @author      Michael van Dam <mvandam@caltech.edu>
 * @category    ICMS
 * @package     Data
 * @subpackage  Notification
 * @version     SVN: $Id:Object.php 19775 2010-07-11 18:54:25Z malanciault $
 */

declare(strict_types=1);

namespace Icms\Data\Notification;

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * Notification entity (formerly icms_data_notification_Object).
 *
 * @category    ICMS
 * @package     Data
 * @subpackage  Notification
 */
class Entity extends \Icms\Core\Entity
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('not_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('not_modid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('not_category', XOBJ_DTYPE_TXTBOX, null, false, 30);
        $this->initVar('not_itemid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('not_event', XOBJ_DTYPE_TXTBOX, null, false, 30);
        $this->initVar('not_uid', XOBJ_DTYPE_INT, 0, true);
        $this->initVar('not_mode', XOBJ_DTYPE_INT, 0, false);
    }

    // FIXME:???
    // To send email to multiple users simultaneously, we would need to move
    // the notify functionality to the handler class.  BUT, some of the tags
    // are user-dependent, so every email msg will be unique.  (Unless maybe use
    // smarty for email templates in the future.)  Also we would have to keep
    // track if each user wanted email or PM.

    /**
     * Send a notification message to the user.
     */
    public function notifyUser($template_dir, $template, $subject, $tags): bool
    {
        global $icmsConfigMailer;

        $member_handler = \icms::handler('icms_member');
        $user =& $member_handler->getUser($this->getVar('not_uid'));
        if (!is_object($user)) {
            return true;
        }
        $method = $user->getVar('notify_method');

        $xoopsMailer = new \icms_messaging_Handler();
        include_once ICMS_ROOT_PATH . '/include/notification_constants.php';
        switch ($method) {
            case XOOPS_NOTIFICATION_METHOD_PM:
                $xoopsMailer->usePM();
                $xoopsMailer->setFromUser($member_handler->getUser($icmsConfigMailer['fromuid']));
                foreach ($tags as $k => $v) {
                    $xoopsMailer->assign($k, $v);
                }
                break;

            case XOOPS_NOTIFICATION_METHOD_EMAIL:
                $xoopsMailer->useMail();
                foreach ($tags as $k => $v) {
                    $xoopsMailer->assign($k, preg_replace("/&amp;/i", '&', $v));
                }
                break;

            default:
                return true;
        }

        $xoopsMailer->setTemplateDir($template_dir);
        $xoopsMailer->setTemplate($template);
        $xoopsMailer->setToUsers($user);
        $xoopsMailer->setSubject($subject);
        $success = $xoopsMailer->send();

        include_once ICMS_ROOT_PATH . '/include/notification_constants.php';
        $notification_handler = \icms::handler('icms_data_notification');

        if ($this->getVar('not_mode') == XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE) {
            $notification_handler->delete($this);
            return $success;
        }

        if ($this->getVar('not_mode') == XOOPS_NOTIFICATION_MODE_SENDONCETHENWAIT) {
            $this->setVar('not_mode', XOOPS_NOTIFICATION_MODE_WAITFORLOGIN);
            $notification_handler->insert($this);
        }
        return $success;
    }
}

\class_alias(Entity::class, 'icms_data_notification_Object');
