<?php
/**
 * Core constants
 *
 * This file has all core errors and warning constants.
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		languages
 * @since		1.2
 * @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id
 */

define('_CORE_MEMORYUSAGE', '内存使用率');
define('_CORE_BYTES', 'bytes');
define('_CORE_KILOBYTES', 'Kilo Bytes');
define('_CORE_MEGABYTES', 'Mega Bytes');
define('_CORE_GIGABYTES', 'Giga Bytes');
define('_CORE_KILOBYTES_SHORTEN', 'Kb');
define('_CORE_MEGABYTES_SHORTEN', 'Mb');
define('_CORE_GIGABYTES_SHORTEN', 'Gb');
define('_CORE_MODULEHANDLER_NOTAVAILABLE', '处理程序不存在<br />模块: %s<br />名称: %s');
define('_CORE_COREHANDLER_NOTAVAILABLE', 'Class <b>%s</b> 不存在<br />处理程序名称: %s');
define('_CORE_NOMODULE', '没有模块被加载');
define('_CORE_PAGENOTDISPLAYED', '页面未显示，内部出错。<br/><br/>可提交下面的信息给网站管理员以便解决问题:<br /><br />错误: %s<br />');
define('_CORE_TOKEN', 'XOOPS_TOKEN');
define('_CORE_TOKENVALID', 'Token 验证');
define('_CORE_TOKENNOVALID', '未提交有效的token');
define('_CORE_TOKENINVALID', '未提交有效的token');
define('_CORE_TOKENISVALID', '提交有效的token');
define('_CORE_TOKENEXPIRED', '有效的token延期');
define('_CORE_CLASSNOTINSTANIATED', 'class不能被示例!');

define('_CORE_DB_NOTRACE', 'notrace:mysql扩展未加载');
define('_CORE_DB_NOTALLOWEDINGET', '在进行GET请求时，数据库不能更新');
define('_CORE_DB_NOTRACEDB', 'notrace:不能连接到数据库');
define('_CORE_DB_INVALIDEMAIL', '无效Email');
define('_CORE_PASSLEVEL1','密码太短');
define('_CORE_PASSLEVEL2','密码安全性低');
define('_CORE_PASSLEVEL3','密码安全性好');
define('_CORE_PASSLEVEL4','密码安全性高');
define('_CORE_UNAMEPASS_IDENTIC','用户名、密码相同.');

/* Added in 1.3 */

define('_CORE_CHECKSUM_FILES_ADDED',' 文件已被添加');
define('_CORE_CHECKSUM_FILES_REMOVED',' 文件已被移除');
define('_CORE_CHECKSUM_ALTERED_REMOVED',' 文件被改名或移除');
define('_CORE_CHECKSUM_CHECKFILE','正在检查文件');
define('_CORE_CHECKSUM_PERMISSIONS_ALTERED',' 文件权限已被更改');
define('_CORE_CHECKSUM_CHECKFILE_UNREADABLE', '文件中包含的检测数据不存在或不可读取，验证未能完成。');
define('_CORE_CHECKSUM_ADDING',' 添加');
define('_CORE_CHECKSUM_CHECKSUM',' 验证');
define('_CORE_CHECKSUM_PERMISSIONS',' 权限');

define('_CORE_DEPRECATED', '未通过');
define('_CORE_DEPRECATED_REPLACEMENT', '使用 %s 替代');
define('_CORE_DEPRECATED_CALLSTACK', '<br />Call Stack: <br />');
define('_CORE_DEPRECATED_MSG', '%s 在 %s, 行 %u <br />');
define('_CORE_DEPRECATED_CALLEDBY', 'Called by: ');
define('_CORE_REMOVE_IN_VERSION', '在 %s 版中将被移除');
define('_CORE_DEBUG', '调试');
define('_CORE_DEVELOPER_DASHBOARD', 'Developer Dashboard');
