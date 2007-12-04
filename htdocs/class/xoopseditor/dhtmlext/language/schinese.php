<?php
/**
 * Extended dhtmltextarea editor for XOOPS
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		4.00
 * @version		$Id: schinese.php,v 1.1 2007/06/05 14:44:26 marcan Exp $
 * @package		xoopseditor
 */
/*
 * Assocated with editor_registry.php
 */
define("_XOOPS_EDITOR_DHTMLEXT", "扩展DHTML编辑器");
$GLOBALS["formtextdhtml_fonts"] = array("宋体", "楷体", "黑体", "Arial", "Courier", "Georgia", "Helvetica", "Impact", "Verdana", "Haettenschweiler");
$GLOBALS["formtextdhtml_sizes"] = array(
	"xx-small"	=> "特小",
	"x-small"	=> "较小",
	"small"		=> "小",
	"medium"	=> "中等",
	"large"		=> "大",
	"x-large"	=> "较大",
	"xx-large"	=> "特大",
	);

define("_ALTURL", "网址");
define("_ALTEMAIL", "Email");
define("_ALTIMG", "图片");
define("_ALTIMAGE", "站内图片");
define("_ALTSMILEY", "表情图");
define("_ALTWMP", "Windows媒体播放器");
define("_ALTFLASH", "Flash");
define("_ALTMMS", "在线流媒体");
define("_ALTRTSP", "Real Player");
define("_ALTIFRAME", "IFRAME");
define("_ALTWIKI", "WIKI链接");
define("_ALTCODE", "源代码");
define("_ALTQUOTE", "引用");
define("_ALTBOLD", "粗体");
define("_ALTITALIC", "斜体");
define("_ALTUNDERLINE", "下划线");
define("_ALTLINETHROUGH", "横穿线");
define("_ENTERIFRAMEURL", "请选择要贴的IFRAME网址：");
define("_ENTERHEIGHT", "请输入高度：");
define("_ENTERWIDTH", "请输入宽度：");
define("_ENTERMMSURL", "请选择要贴的MMS网址：");
define("_ENTERWMPURL", "请选择要贴的WMP网址：");
define("_ENTERFLASHURL", "请选择要贴的FLASH网址：");
define("_ENTERRTSPURL", "请选择要贴的RTSP网址：");
define("_ENTERWIKITERM", "请输入要连接到WIKI的关键词：");

define("_ALTLENGTH", "当前内容长度：%s（中文内容实际长度大于该长度，每个中文字占3字节）");
define("_ALTLENGTH_MAX", "允许长度：");
?>