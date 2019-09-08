<?php
// $Id: blauer-fisch $
//%%%%%%	Admin Module Name  MailUsers	%%%%%
if (!defined('_AM_DBUPDATED')) {
    define("_AM_DBUPDATED", "数据库更新成功!");
}

//%%%%%%	mailusers.php 	%%%%%
define("_AM_SENDTOUSERS", "给这些用户发送信息:");
define("_AM_SENDTOUSERS2", "发送给:");
define("_AM_GROUPIS", "群组(可选)");
define("_AM_TIMEFORMAT", "(格式 yyyy-mm-dd, 可选)");
define("_AM_LASTLOGMIN", "上次登录晚于");
define("_AM_LASTLOGMAX", "上次登录早于");
define("_AM_REGDMIN", "注册日期晚于");
define("_AM_REGDMAX", "注册日期早于");
define("_AM_IDLEMORE", "上次登录已是 X 天以前(可选)");
define("_AM_IDLELESS", "上次登录已是 X 天以后(可选)");
define("_AM_MAILOK", "只给设定接收通知的用户发送信息(可选)");
define("_AM_INACTIVE", "只给未激活用户发送信息(可选)");
define("_AMIFCHECKD", "如果选中这项，那以上所有私人信息都会被忽略");
define("_AM_MAILFNAME", "发件人(仅在邮件中)");
define("_AM_MAILFMAIL", "发件邮箱(仅在邮件中)");
define("_AM_MAILSUBJECT", "主题");
define("_AM_MAILBODY", "内容");
define("_AM_MAILTAGS", "有用的标签:");
define("_AM_MAILTAGS1", "{X_UID} 将会显示用户id");
define("_AM_MAILTAGS2", "{X_UNAME} 将会显示用户名称");
define("_AM_MAILTAGS3", "{X_UEMAIL} 将会显示用户邮箱地址");
define("_AM_MAILTAGS4", "{X_UACTLINK} 将会显示用户激活链接");
define("_AM_SENDTO", "收件人");
define("_AM_EMAIL", "邮箱地址");
define("_AM_PM", "私人信息");
define("_AM_SENDMTOUSERS", "给用户发送信息");
define("_AM_SENT", "已发送");
define("_AM_SENTNUM", "%s - %s (合计: %s 名用户)");
define("_AM_SENDNEXT", "下一步Next");
define("_AM_NOUSERMATCH", "没有匹配的用户");
define("_AM_SENDCOMP", "信息已发送.");
