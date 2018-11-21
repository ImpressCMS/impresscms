<?php
// $Id: blauer-fisch $
//%%%%%%	File Name mainfile.php 	%%%%%
define('_PLEASEWAIT','请稍等');
define('_FETCHING','加载中...');
define('_TAKINGBACK','返回之前所在的....');
define('_LOGOUT','登出');
define('_SUBJECT','主题');
define('_MESSAGEICON','信息图标');
define('_COMMENTS','留言');
define('_POSTANON','匿名留言');
define('_DISABLESMILEY','表情符不可用');
define('_DISABLEHTML','html代码不可用');
define('_PREVIEW','预览');

define('_GO','Go!');
define('_NESTED','Nested');
define('_NOCOMMENTS','没有留言');
define('_FLAT','扁平化');
define('_THREADED','线程');
define('_OLDESTFIRST','最早的排前');
define('_NEWESTFIRST','最新的排前');
define('_MORE','更多...');
define('_IFNOTRELOAD','如果页面不能自动响应，请点击<a href="%s">这里</a>');
define('_WARNINSTALL2','警告: 目录 %s 存在于你的服务器中. <br />安全起见，请移除该目录.');
define('_WARNINWRITEABLE','警告: 文件 %s 可被服务器写入。 <br />安全起见，请更改权限。<br /> Unix 中为(444), Win32 中(只读)');
define('_WARNINNOTWRITEABLE','警告: 文件 %s 不可被服务器写入. <br />为启用某些功能，请更改权限。<br /> Unix 中为(777), Win32 中(可写入)');

// Error messages issued by icms_core_Object::cleanVars()
define( '_XOBJ_ERR_REQUIRED', '%s 必须' );
define( '_XOBJ_ERR_SHORTERTHAN', '字符 %s 必须比 %d 字符短.' );

//%%%%%%	File Name themeuserpost.php 	%%%%%
define('_PROFILE','个人资料');
define('_POSTEDBY','留言人');
define('_VISITWEBSITE','访问网站');
define('_SENDPMTO','发送私人信息给 %s');
define('_SENDEMAILTO','发送电子邮件给 %s');
define('_ADD','添加');
define('_REPLY','回复');
define('_DATE','日期');   // Posted date

//%%%%%%	File Name admin_functions.php 	%%%%%
define('_MAIN','主要的');
define('_MANUAL','手动');
define('_INFO','信息');
define('_CPHOME','控制面板首页');
define('_YOURHOME','首页');

//%%%%%%	File Name misc.php (who's-online popup)	%%%%%
define('_WHOSONLINE','有谁在线');
define('_GUESTS', '游客');
define('_MEMBERS', '会员');
define('_ONLINEPHRASE','<b>%s</b> 用户在线');
define('_ONLINEPHRASEX','<b>%s</b> 用户在浏览<b>%s</b>');
define('_CLOSE','Close');  // Close window

//%%%%%%	File Name module.textsanitizer.php 	%%%%%
define('_QUOTEC','引用:');

//%%%%%%	File Name admin.php 	%%%%%
define("_NOPERM","对不起，权限不足。");

//%%%%%		Common Phrases		%%%%%
define("_NO","No");
define("_YES","Yes");
define("_EDIT","编辑");
define("_DELETE","移除");
define("_SUBMIT","提交");
define("_MODULENOEXIST","选中的模块不存在!");
define("_ALIGN","对齐");
define("_LEFT","左对齐");
define("_CENTER","中间对齐");
define("_RIGHT","右对齐");
define("_FORM_ENTER", "请输入%s");
// %s represents file name
define("_MUSTWABLE","文件 %s 必须能被服务器写入!");
// Module info
define('_PREFERENCES', '自定义');
define("_VERSION", "版本");
define("_DESCRIPTION", "描述");
define("_ERRORS", "错误");
define("_NONE", "没有");
define('_ON','on');
define('_READS','阅读');
define('_SEARCH','搜索');
define('_ALL', '全部');
define('_TITLE', '标题');
define('_OPTIONS', '选项');
define('_QUOTE', '引用');
define('_HIDDENC', '隐藏内容:');
define('_HIDDENTEXT', '该内容对匿名访客不可见, 请<a href="'.ICMS_URL.'/register.php" title="Registeration at ' . htmlspecialchars ( $icmsConfig ['sitename'], ENT_QUOTES ) . '">注册</a> 以便访问该内容.');
define('_LIST', '列表');
define('_LOGIN','用户登录');
define('_USERNAME','用户名: ');
define('_PASSWORD','密码: ');
define("_SELECT","选择");
define("_IMAGE","图像");
define("_SEND","发送");
define("_CANCEL","取消");
define("_ASCENDING","按字母排序");
define("_DESCENDING","按字母降序");
define('_BACK', '返回');
define('_NOTITLE', '无标题');

/* Image manager */
define('_IMGMANAGER','图像管理器');
define('_NUMIMAGES', '%s 图片');
define('_ADDIMAGE','添加图像文件');
define('_IMAGENAME','名称:');
define('_IMGMAXSIZE','文件最大尺寸(bytes):');
define('_IMGMAXWIDTH','图像最大宽度(pixels):');
define('_IMGMAXHEIGHT','图像最大高度(pixels):');
define('_IMAGECAT','分类:');
define('_IMAGEFILE','图像文件:');
define('_IMGWEIGHT','排序:');
define('_IMGDISPLAY','显示该图像吗?');
define('_IMAGEMIME','MIME类型:');
define('_FAILFETCHIMG', '不能上传文件%s');
define('_FAILSAVEIMG', '存储图像文件 %s 到数据库时失败');
define('_NOCACHE', '没有缓存');
define('_CLONE', '克隆');
define('_INVISIBLE', '不可见');

//%%%%%	File Name class/xoopsform/formmatchoption.php 	%%%%%
define("_STARTSWITH", "开始于");
define("_ENDSWITH", "结束于");
define("_MATCHES", "匹配");
define("_CONTAINS", "包含");

//%%%%%%	File Name commentform.php 	%%%%%
define("_REGISTER","注册");

//%%%%%%	File Name xoopscodes.php 	%%%%%
define("_SIZE","字体大小");  // font size
define("_FONT","字型");  // font family
define("_COLOR","字体颜色");  // font color
define("_EXAMPLE","样例");
define("_ENTERURL","输入需要添加的链接地址:");
define("_ENTERWEBTITLE","输入网站标题:");
define("_ENTERIMGURL","输入要添加图片的地址.");
define("_ENTERIMGPOS","现在，输入图片的位置.");
define("_IMGPOSRORL","'R' 或 'r' 表示右, 'L' 或 'l' 表示左, 'C' 或 'c' 表示中央, 或者留白.");
define("_ERRORIMGPOS","出错! 输入图片的位置.");
define("_ENTEREMAIL","输入需添加的电子邮箱地址.");
define("_ENTERCODE","输入需添加的代码.");
define("_ENTERQUOTE","输入需引用的文字.");
define("_ENTERHIDDEN","输入需显示给匿名用户的文字.");
define("_ENTERTEXTBOX","请在文字框中输入文字.");

//%%%%%		TIME FORMAT SETTINGS   %%%%%
define('_SECOND', '1 秒');
define('_SECONDS', '%s 秒');
define('_MINUTE', '1 分');
define('_MINUTES', '%s 分');
define('_HOUR', '1 小时');
define('_HOURS', '%s 小时');
define('_DAY', '1 天');
define('_DAYS', '%s 天');
define('_WEEK', '1 周');
define('_MONTH', '1 月');

define("_DATESTRING","Y/n/j G:i:s");
define("_MEDIUMDATESTRING","Y/n/j G:i");
define("_SHORTDATESTRING","Y/n/j");
/*
 The following characters are recognized in the format string:
 a - "am" or "pm"
 A - "AM" or "PM"
 d - day of the month, 2 digits with leading zeros; i.e. "01" to "31"
 D - day of the week, textual, 3 letters; i.e. "Fri"
 F - month, textual, long; i.e. "January"
 h - hour, 12-hour format; i.e. "01" to "12"
 H - hour, 24-hour format; i.e. "00" to "23"
 g - hour, 12-hour format without leading zeros; i.e. "1" to "12"
 G - hour, 24-hour format without leading zeros; i.e. "0" to "23"
 i - minutes; i.e. "00" to "59"
 j - day of the month without leading zeros; i.e. "1" to "31"
 l (lowercase 'L') - day of the week, textual, long; i.e. "Friday"
 L - boolean for whether it is a leap year; i.e. "0" or "1"
 m - month; i.e. "01" to "12"
 n - month without leading zeros; i.e. "1" to "12"
 M - month, textual, 3 letters; i.e. "Jan"
 s - seconds; i.e. "00" to "59"
 S - English ordinal suffix, textual, 2 characters; i.e. "th", "nd"
 t - number of days in the given month; i.e. "28" to "31"
 T - Timezone setting of this machine; i.e. "MDT"
 U - seconds since the epoch
 w - day of the week, numeric, i.e. "0" (Sunday) to "6" (Saturday)
 Y - year, 4 digits; i.e. "1999"
 y - year, 2 digits; i.e. "99"
 z - day of the year; i.e. "0" to "365"
 Z - timezone offset in seconds (i.e. "-43200" to "43200")
 */

//%%%%%		LANGUAGE SPECIFIC SETTINGS   %%%%%
define('_CHARSET', 'utf-8');
define('_LANGCODE', 'zh-cn');

// change 0 to 1 if this language is a multi-bytes language
define("XOOPS_USE_MULTIBYTES", "1");
// change 0 to 1 if this language is a RTL (right to left) language
define("_ADM_USE_RTL","0");

define('_MODULES','模块');
define('_SYSTEM','系统');
define('_IMPRESSCMS_NEWS','新闻');
define('_ABOUT','ImpressCMS项目');
define('_IMPRESSCMS_HOME','项目主页');
define('_IMPRESSCMS_COMMUNITY','社区');
define('_IMPRESSCMS_ADDONS','附加组件');
define('_IMPRESSCMS_WIKI','维基');
define('_IMPRESSCMS_BLOG','博客');
define('_IMPRESSCMS_DONATE','捐赠!');
define("_IMPRESSCMS_Support","支持该项目!");
define('_IMPRESSCMS_SOURCEFORGE','SourceForge项目roject');
define('_IMPRESSCMS_ADMIN','管理');
/** The default separator used in icms_view_Tree::getNicePathFromId */
define('_BRDCRMB_SEP','&nbsp;:&nbsp;');
//Content Manager
define('_CT_NAV','首页');
define('_CT_RELATEDS','相关页面');
//Security image (captcha)
define("_SECURITYIMAGE_GETCODE","输入安全代码");
define("_QUERIES", "查询");
define("_BLOCKS", "区块");
define("_EXTRA", "扩展");
define("_TIMERS", "计时器");
define("_CACHED", "缓存");
define("_REGENERATES", "每 %s 秒重生");
define("_TOTAL", "共计:");
define("_ERR_NR", "错误数量:");
define("_ERR_MSG", "错误信息:");
define("_NOTICE", "注意");
define("_WARNING", "警告");
define("_STRICT", "严格");
define("_ERROR", "错误");
define("_TOOKXLONG", " 需 %s 秒加载.");
define("_BLOCK", "区块");
define("_WARNINGUPDATESYSTEM","祝贺！网站已成功更新至最新版的ImpressCMS!<br />点击这里升级系统模块以完成更新程序.<br />点击这里进行更新.");

// This shows local support site in ImpressCMS menu, (if selected language is not English)
define('_IMPRESSCMS_LOCAL_SUPPORT','http://www.impresscms.org'); //add the local support site's URL
define('_IMPRESSCMS_LOCAL_SUPPORT_TITLE','本地支持站点');
define("_ALLEFTCON","向左对齐.");
define("_ALCENTERCON","中间对齐.");
define("_ALRIGHTCON","向右对齐.");
define( "_TRUST_PATH_HELP", "警告: 系统不能找到真实路径.<br />“真实路径”是一个涉及到ImpressCMS核心及模块安全代码的文件夹.<br />建议将此文件夹存放在根目录外，使浏览器不能访问。<br /><a target='_blank' href='http://wiki.impresscms.org/index.php?title=Trust_Path'>点击这里了解更多关于如何创建真实路径的信息</a>" );
define( "_PROTECTOR_NOT_FOUND", "警告: 系统未能确定Protector是否安装或激活。<br />强烈建议安装并激活Protector以提高网站的安全性。<br />感谢GIJOE为我们提供了这么棒的模块。 /><a target='_blank' href='http://wiki.impresscms.org/index.php?title=Protector'>点击这里获得更多关于Protector的信息。</a><br /><a target='_blank' href='http://xoops.peak.ne.jp/modules/mydownloads/singlefile.php?lid=105&cid=1'>点击这里下载最新版的Protector</a>" );

define('_MODABOUT_ABOUT', '关于');
// if you have troubles with this font on your language or it is not working, download tcpdf from: http://www.tecnick.com/public/code/cp_dpage.php?aiocp_dp=tcpdf and add the required font in libraries/tcpdf/fonts then write down the font name here. system will then load this font for your language.
define('_PDF_LOCAL_FONT', '');
define('_CALENDAR_TYPE',''); // this value is for the local calendar used in this system, if you're not sure about this leave this value as it is!
define('_CALENDAR','日历');
define('_RETRYPOST','对不起，请求超时. 请再次提交'); // autologin hack GIJ

############# added since 1.2 #############
define('_QSEARCH','快速搜索');
define('_PREV','上一页');
define('_NEXT','下一页');
define('_LCL_NUM0','0');
define('_LCL_NUM1','1');
define('_LCL_NUM2','2');
define('_LCL_NUM3','3');
define('_LCL_NUM4','4');
define('_LCL_NUM5','5');
define('_LCL_NUM6','6');
define('_LCL_NUM7','7');
define('_LCL_NUM8','8');
define('_LCL_NUM9','9');
// change 0 to 1 if your language has a different numbering than latin`s alphabet
define("_USE_LOCAL_NUM","0");
define("_ICMS_DBUPDATED","数据库更新成功!");
define('_MD_AM_DBUPDATED',_ICMS_DBUPDATED);

define('_TOGGLETINY','切换编辑器');
define("_ENTERHTMLCODE","输入需要添加的HTML代码.");
define("_ENTERPHPCODE","输入需要添加的PHP代码.");
define("_ENTERCSSCODE","输入需要添加的CSS代码.");
define("_ENTERJSCODE","输入需要添加的JavaScript代码.");
define("_ENTERWIKICODE","输入需要添加的wiki代码.");
define("_ENTERLANGCONTENT","在 %s 中输入需要添加的文字.");
define('_LANGNAME', '简体中文');
define('_ENTERYOUTUBEURL', '输入视频URL:');
define('_ENTERHEIGHT', '定义多媒体框高度');
define('_ENTERWIDTH', '定义多媒体框宽度');
define('_ENTERMEDIAURL', '输入多媒体URL:');
// !!IMPORTANT!! insert '\' before any char among reserved chars: "a", "A", "B", "c", "d", "D", "F", "g", "G", "h", "H", "i", "I", "j", "l", "L", "m", "M", "n", "O", "r", "s", "S", "t", "T", "U", "w", "W", "Y", "y", "z", "Z"
// insert double '\' before 't', 'r', 'n'
define("_TODAY", "\T\o\d\a\y G:i");
define("_YESTERDAY", "\Y\e\s\\t\e\\r\d\a\y G:i");
define("_MONTHDAY", "n/j G:i");
define("_YEARMONTHDAY", "Y/n/j G:i");
define("_ELAPSE", "%s 天前");
define('_VISIBLE', '可见');
define('_UP', '上');
define('_DOWN', '下');
define('_CONFIGURE', '配置');

// Added in 1.2.2
define('_CSSTIDY_VULN', '警告: 文件 %s 已存在于服务器中. <br />请手动删除');
define('_FILE_DELETED', '文件 %s 已被删除');

// added in 1.3
define('_CHECKALL', '全部检查');
define('_COPYRIGHT', '版权所有');
define("_LONGDATESTRING", "F jS Y, h:iA");
define('_AUTHOR', '作者');
define("_CREDITS", "创建");
define("_LICENSE", "许可证");
define("_LOCAL_FOOTER",'基于ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"http://www.impresscms.org/\" rel=\"external\">ImpressCMS项目</a>');

