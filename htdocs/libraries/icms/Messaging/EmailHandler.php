<?php
/**
 * Class for handling email, extending PHPMailer to email the users
 *
 * @copyright   Copyright (c) 2000 XOOPS.org
 * @copyright   (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category    ICMS
 * @package     Messaging
 * @subpackage  Email
 * @author      Jochen Bünagel (job@buennagel.com)
 */

declare(strict_types=1);

namespace Icms\Messaging;

use PHPMailer\PHPMailer\PHPMailer;

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

require_once ICMS_LIBRARIES_PATH . '/phpmailer/src/Exception.php';
require_once ICMS_LIBRARIES_PATH . '/phpmailer/src/PHPMailer.php';
require_once ICMS_LIBRARIES_PATH . '/phpmailer/src/SMTP.php';

/**
 * Mailer class (formerly icms_messaging_EmailHandler).
 *
 * @category    ICMS
 * @package     Core
 * @subpackage  Mail
 */
class EmailHandler extends PHPMailer
{
    /**
     * "from" address
     *
     * @var string
     * @access private
     */
    public $From = "";

    /**
     * "from" name
     *
     * @var string
     * @access private
     */
    public $FromName = "";

    /**
     * Method to be used when sending the mail.
     *
     * @var string
     * @access private
     */
    public $Mailer = "mail";

    /**
     * Path to sendmail program.
     *
     * @var string
     * @access private
     */
    public $Sendmail = "/usr/sbin/sendmail";

    /**
     * SMTP Host.
     *
     * @var string
     * @access private
     */
    public $Host = "";

    /**
     * Connection prefix.
     * Options are "", "ssl" or "tls".
     *
     * @var string
     */
    public $SMTPSecure = "";

    /**
     * Does your SMTP host require SMTPAuth authentication?
     *
     * @var bool
     * @access private
     */
    public $SMTPAuth = false;

    /**
     * Username for authentication with your SMTP host.
     *
     * @var string
     * @access private
     */
    public $Username = "";

    /**
     * Password for SMTPAuth.
     *
     * @var string
     * @access private
     */
    public $Password = "";

    /**
     * SMTP Port to use.
     *
     * @var int
     * @access private
     */
    public $Port = 25;

    /**
     * Constructor.
     */
    public function __construct()
    {
        global $icmsConfig, $icmsConfigMailer;
        $this->From = $icmsConfigMailer['from'];
        if ($this->From == '') {
            $this->From = $icmsConfig['adminmail'];
        }
        $this->Sender = $this->From;

        if ($icmsConfigMailer["mailmethod"] == "smtpauth") {
            $this->Mailer = "smtp";
            $this->SMTPAuth = true;
            $this->SMTPSecure = $icmsConfigMailer['smtpsecure'];
            $this->Host = implode(';', $icmsConfigMailer['smtphost']);
            $this->Username = $icmsConfigMailer['smtpuser'];
            $this->Password = $icmsConfigMailer['smtppass'];
            $this->Port = $icmsConfigMailer['smtpauthport'];
        } else {
            $this->Mailer = $icmsConfigMailer['mailmethod'];
            $this->SMTPAuth = false;
            $this->Sendmail = $icmsConfigMailer['sendmailpath'];
            $this->Host = implode(';', $icmsConfigMailer['smtphost']);
        }
        $this->CharSet = strtolower(_CHARSET);
        $this->SetLanguage('en', ICMS_LIBRARIES_PATH . "/phpmailer/language/");
        $this->PluginDir = ICMS_LIBRARIES_PATH . "/phpmailer/";
    }

    /**
     * Formats an address correctly.
     */
    public function AddrFormat($addr)
    {
        if (empty($addr[1])) {
            return $addr[0];
        }
        return sprintf('%s <%s>', '=?' . $this->CharSet . '?B?' . base64_encode($addr[1]) . '?=', $addr[0]);
    }

    // to be overidden by lang specific mail class, if needed
    public function encodeFromName($text)
    {
        return $text;
    }

    // to be overidden by lang specific mail class, if needed
    public function encodeSubject($text)
    {
        return $text;
    }

    // to be overidden by lang specific mail class, if needed
    public function encodeBody(&$text)
    {
        return $text;
    }
}

\class_alias(EmailHandler::class, 'icms_messaging_EmailHandler');
