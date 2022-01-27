<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * Class for handling messaging
 *
 * @copyright	(c) 2007-2008 The ImpressCMS Project - www.impresscms.org
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */

namespace ImpressCMS\Core\Messaging;

use icms;
use ImpressCMS\Core\Models\Group;
use ImpressCMS\Core\Models\User;
use PHPMailer\PHPMailer\Exception;
use XoopsMailerLocal;

/**
 * Class for sending messages.
 *
 * @author	Kazumi Ono (AKA onokazu)
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @package	ICMS\Messaging
 */
class MessageSender {

	/**
	 * Charset
	 *
	 * @var string
	 */
	protected $charSet = 'utf-8';

	/**
	 * Encoding
	 *
	 * @var string
	 */
	protected $encoding = '8bit';

	/**
	 * reference
	 *
	 * @var		Mailer
	 */
	private $multimailer;

	/**
	 * From email address
	 *
	 * @var string
	 */
	private $fromEmail;

	/**
	 * Sender name
	 *
	 * @var string
	 */
	private $fromName;

	/**
	 * sender UID
	 *
	 * @var string|null
	 */
	private $fromUser;

	/**
	 * array of user class objects
	 *
	 * @var array
	 */
	private $toUsers;

	/**
	 * Array of email addresses
	 *
	 * @var string[]
	 */
	private $toEmails;

	/**
	 * custom headers
	 *
	 * @var string[]
	 */
	private $headers;

	/**
	 * Subjet of mail
	 *
	 * @var string
	 */
	private $subject;

	/**
	 * body of mail
	 *
	 * @var string
	 */
	private $body;

	/**
	 * Error messages
	 *
	 * @var string[]
	 */
	private $errors;

	/**
	 * Messages upon success
	 *
	 * @var array
	 */
	private $success;
	private $isMail;
	private $isPM;
	private $assignedTags;
	private $template;
	private $templatedir;
	/**
	 * @var string
	 */
	private $priority;
	/**
	 * @var string
	 */
	private $LE;

	public function __construct() {
		icms_loadLanguageFile('core', 'xoopsmailerlocal');
		icms_loadLanguageFile('core', 'mail');
		if (class_exists('XoopsMailerLocal')) {
			$this->multimailer = new XoopsMailerLocal();
		} else {
			$this->multimailer = new MessageSender();
		}
		$this->reset();
	}

	/**
	 * Resets all properties to default
	 *
	 * @return $this
	 */
	public function reset(): MessageSender
	{
		$this->fromEmail = '';
		$this->fromName = '';
		$this->fromUser = null;
		$this->priority = '';
		$this->toUsers = [];
		$this->toEmails = [];
		$this->headers = [];
		$this->subject = '';
		$this->body = '';
		$this->errors = [];
		$this->success = [];
		$this->isMail = false;
		$this->isPM = false;
		$this->assignedTags = [];
		$this->template = '';
		$this->templatedir = '';
		// Change below to \r\n if you have problem sending mail
		$this->LE = "\n";

		return $this;
	}

	/**
	 * Sets template dir
	 *
	 * @param string $value Template dir
	 *
	 * @return MessageSender
	 */
	public function setTemplateDir(string $value): MessageSender
	{
		if ($value[strlen($value) - 1] !== '/') {
			$value .= '/';
		}
		$this->templatedir = $value;

		return $this;
	}

	/**
	 * Sets template
	 *
	 * @param string $value Template
	 *
	 * @return MessageSender
	 */
	public function setTemplate(string $value): MessageSender
	{
		$this->template = $value;

		return $this;
	}

	/**
	 * Sets from name
	 *
	 * @param string $value From name
	 *
	 * @return MessageSender
	 */
	public function setFromEmail(string $value): MessageSender
	{
		$this->fromEmail = trim($value);

		return $this;
	}

	/**
	 * Sets from name
	 *
	 * @param string $value From name
	 */
	public function setFromName(string $value): MessageSender
	{
		$this->fromName = trim($value);

		return $this;
	}

	/**
	 * Sets message author
	 *
	 * @param User $user Author
	 *
	 * @return $this
	 */
	public function setFromUser(User $user): MessageSender
	{
		$this->fromUser = & $user;

		return $this;
	}

	/**
	 * Sets message priority
	 *
	 * @param int $value Priority
	 *
	 * @return $this
	 */
	public function setPriority($value): MessageSender
	{
		$this->priority = trim($value);

		return $this;
	}

	/**
	 * Sets subject for message
	 *
	 * @param string $value Subject
	 *
	 * @return $this
	 */
	public function setSubject(string $value): MessageSender
	{
		$this->subject = trim($value);

		return $this;
	}

	/**
	 * Sets that sender use mail for the message
	 *
	 * @return $this
	 */
	public function useMail(): MessageSender
	{
		$this->isMail = true;

		return $this;
	}

	/**
	 * Sets that sender uses private messages for the message
	 *
	 * @return $this
	 */
	public function usePM(): MessageSender
	{
		$this->isPM = true;

		return $this;
	}

	/**
	 * Sends message
	 *
	 * @param bool $debug Need to debug ?
	 *
	 * @return bool
	 *
	 * @throws Exception
	 */
	public function send(bool $debug = false): bool
	{
		global $icmsConfig;
		if (empty($this->body) && empty($this->template)) {
			if ($debug) {
				$this->errors[] = _MAIL_MSGBODY;
			}
			return false;
		}

		if ($this->template) {
			$path = ($this->templatedir)?$this->templatedir . '' . $this->template:(ICMS_ROOT_PATH . '/language/' . $icmsConfig['language'] . '/mail_template/' . $this->template);
			if (!($fd = @fopen($path, 'r'))) {
				if ($debug) {
					$this->errors[] = _MAIL_FAILOPTPL;
				}
				return false;
			}
			$this->setBody(fread($fd, filesize($path)));
		}

		// for sending mail only
		if ($this->isMail || !empty($this->toEmails)) {
			if (!empty($this->priority)) {
				$this->headers[] = 'X-Priority: ' . $this->priority;
			}
			//$this->headers[] = "X-Mailer: PHP/" . phpversion();
			//$this->headers[] = "Return-Path: " . $this->fromEmail;
			$headers = implode($this->LE, $this->headers);
		}

		// TODO: we should have an option of no-reply for private messages and emails
		// to which we do not accept replies.  e.g. the site admin doesn't want a
		// a lot of message from people trying to unsubscribe.  Just make sure to
		// give good instructions in the message.

		// add some standard tags (user-dependent tags are included later)
		global $icmsConfig;
		$this->assign('X_ADMINMAIL', $icmsConfig['adminmail']);
		$this->assign('X_SITENAME', $icmsConfig['sitename']);
		$this->assign('X_SITEURL', ICMS_URL);
		// TODO: also X_ADMINNAME??
		// TODO: X_SIGNATURE, X_DISCLAIMER ?? - these are probably best
		//  done as includes if mail templates ever get this sophisticated

		// replace tags with actual values
		foreach ($this->assignedTags as $k => $v) {
			$this->body = str_replace('{' . $k . '}', $v, $this->body);
			$this->subject = str_replace('{' . $k . '}', $v, $this->subject);
		}
		$this->body = str_replace("\r\n", "\n", $this->body);
		$this->body = str_replace("\r", "\n", $this->body);
		$this->body = str_replace("\n", $this->LE, $this->body);

		// send mail to specified mail addresses, if any
		foreach ($this->toEmails as $mailaddr) {
			if (!$this->sendMail($mailaddr, $this->subject, $this->body, $headers)) {
				if ($debug) {
					$this->errors[] = sprintf(_MAIL_SENDMAILNG, $mailaddr);
				}
			} else if ($debug) {
				$this->success[] = sprintf(_MAIL_MAILGOOD, $mailaddr);
			}
		}

		// send message to specified users, if any

		// NOTE: we don't send to LIST of recipients, because the tags
		// below are dependent on the user identity; i.e. each user
		// receives (potentially) a different message
		foreach ($this->toUsers as $user) {
			// set some user specific variables
			$subject = str_replace("{X_UNAME}", $user->uname, $this->subject);
			$text = str_replace("{X_USERLOGINNAME}", $user->login_name, $this->body);
			$text = str_replace("{X_UID}", $user->uid, $text);
			$text = str_replace("{X_UEMAIL}", $user->email, $text);
			$text = str_replace("{X_UNAME}", $user->uname, $text);
			$text = str_replace("{X_UACTLINK}", ICMS_URL . "/user.php?op=actv&id=" . $user->uid . "&actkey=" . $user->actkey, $text);
			// send mail
			if ($this->isMail) {
				if (!$this->sendMail($user->email, $subject, $text, $headers)) {
					if ($debug) {
						$this->errors[] = sprintf(_MAIL_SENDMAILNG, $user->uname);
					}
				} else {
					if ($debug) {
						$this->success[] = sprintf(_MAIL_MAILGOOD, $user->uname);
					}
				}
			}
			// send private message
			if ($this->isPM) {
				if (!$this->sendPM($user->uid, $subject, $text)) {
					if ($debug) {
						$this->errors[] = sprintf(_MAIL_SENDPMNG, $user->uname);
					}
				} else {
					if ($debug) {
						$this->success[] = sprintf(_MAIL_PMGOOD, $user->uname);
					}
				}
			}
		}
		return count($this->errors) <= 0;
	}

	/**
	 * Sets body content;
	 *
	 * @param string $value Body
	 *
	 * @return $this
	 */
	public function setBody($value): MessageSender
	{
		$this->body = trim($value);

		return $this;
	}

	/**
	 * Assign value to template
	 *
	 * @param string|array<string, mixed> $tag Template tag to replace or with tags and valies
	 * @param mixed $value Values
	 *
	 * @return $this
	 */
	public function assign($tag, $value = null): MessageSender
	{
		if (is_array($tag)) {
			foreach ($tag as $k => $v) {
				$this->assign($k, $v);
			}

			return $this;
		}

		if (!empty($tag) && isset($value)) {
			$tag = strtoupper(trim($tag));
			// TEMPORARY FIXME: until the X_tags are all in here
			//				if (substr($tag, 0, 2) != "X_") {
			$this->assignedTags[$tag] = $value;
			//				}
		}

		return $this;
	}

	/**
	 * Send email
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return    boolean    FALSE on error.
	 * @throws Exception
	 */
	public function sendMail($email, $subject, $body, $headers) {
		$subject = $this->multimailer->encodeSubject($subject);
		$this->multimailer->encodeBody($body);
		$this->multimailer->ClearAllRecipients();
		$this->multimailer->AddAddress($email);
		$this->multimailer->Subject = $subject;
		$this->multimailer->Body = $body;
		$this->multimailer->CharSet = $this->charSet;
		$this->multimailer->Encoding = $this->encoding;
		if (!empty($this->fromName)) {
			$this->multimailer->FromName = $this->multimailer->encodeFromName($this->fromName);
		}
		if (!empty($this->fromEmail)) {
			$this->multimailer->Sender = $this->multimailer->From = $this->fromEmail;
		}

		$this->multimailer->ClearCustomHeaders();
		foreach ($this->headers as $header) {
			$this->multimailer->AddCustomHeader($header);
		}
		if (!$this->multimailer->Send()) {
			$this->errors[] = $this->multimailer->ErrorInfo;
			return false;
		}
		return true;
	}

	public function sendPM($uid, $subject, $body)
	{
		$pm_handler = icms::handler('icms_data_privmessage');
		$pm = &$pm_handler->create();
		$pm->subject = $subject;
		$pm->from_userid = $this->fromUser->uid ?? icms::$user->uid;
		$pm->msg_text = $body;
		$pm->to_userid = $uid;
		return (bool)$pm_handler->insert($pm);
	}

	public function getErrors($ashtml = true) {
		if (!$ashtml) {
			return $this->errors;
		}

		if (!empty($this->errors)) {
			$ret = '<h4>' . _ERRORS . '</h4>';
			foreach ($this->errors as $error) {
				$ret .= $error . '<br />';
			}
		} else {
			$ret = '';
		}
		return $ret;
	}

	public function getSuccess($ashtml = true) {
		if (!$ashtml) {
			return $this->success;
		}

		$ret = '';
		if (!empty($this->success)) {
			foreach ($this->success as $suc) {
				$ret .= $suc . '<br />';
			}
		}
		return $ret;
	}

	public function addHeaders($value) {
		$this->headers[] = trim($value) . $this->LE;

		return $this;
	}

	public function setToEmails($email) {
		if (!is_array($email)) {
			if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i", $email)) {
				$this->toEmails[] = $email;
			}
		} else {
			foreach ($email as $e) {
				$this->setToEmails($e);
			}
		}
	}

	public function setToGroups($group) {
		if (!is_array($group)) {
			if (get_class($group) === Group::class) {
				$member_handler = icms::handler('icms_member');
				$this->setToUsers($member_handler->getUsersByGroup($group->groupid, true));
			}
		} else {
			foreach ($group as $g) {
				$this->setToGroups($g);
			}
		}
	}

	/**
	 * Set for whom to send a message
	 *
	 * @param User[]|User $user For whom to send
	 *
	 * @return $this
	 */
	public function setToUsers($user): MessageSender
	{
		if (!is_array($user)) {
			if (get_class($user) === User::class) {
				$this->toUsers[] = $user;
			}
		} else {
			foreach ($user as $u) {
				$this->toUsers[] = $u;
			}
		}

		return $this;
	}

}
