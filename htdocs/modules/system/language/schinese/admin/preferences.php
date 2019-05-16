<?php
// $Id: blauer-fisch $
//%%%%%%	Admin Module Name  AdminGroup 	%%%%%
// dont change
if (!defined('_AM_DBUPDATED')) {define("_AM_DBUPDATED","数据库更新成功!");}

define("_MD_AM_SITEPREF","网站首选项");
define("_MD_AM_SITENAME","网站名称");
define("_MD_AM_SLOGAN","网站口号");
define("_MD_AM_ADMINML","管理员电子邮箱地址");
define('_MD_AM_ADMINMLDSC','全部信息将会发送到这个邮箱地址，我们建议使用网站的域名邮箱');
define("_MD_AM_LANGUAGE","默认语言");
define("_MD_AM_LANGUAGEDSC","选择主语言，如果激活了多语言模块，可以选择一种语言。如果是由浏览器侦测，则系统会忽略这一选项");
define("_MD_AM_STARTPAGE","启示页面的模块或页面");
define("_MD_AM_NONE","没有");
define("_MD_CONTENTMAN","文章管理器");
define("_MD_AM_SERVERTZ","服务器时区");
define("_MD_AM_DEFAULTTZ","默认时区");
define("_MD_AM_DTHEME","默认主题");
define("_MD_AM_THEMESET","主题已设置");
define("_MD_AM_ANONNAME","匿名用户的用户名");
define("_MD_AM_MINPASS","密码最短长度");
define("_MD_AM_NEWUNOTIFY","有新用户注册时发邮件通知?");
define("_MD_AM_SELFDELETE","允许用户删除自己的帐户?");
define("_MD_AM_SELFDELETEDSC","如果设为YES, 用户帐户便会新增一删除按钮");
define("_MD_AM_LOADINGIMG","显示“上传中......图像吗?");
Define("_Md_Am_Usegzip","启用gzip压缩?");
define("_MD_AM_UNAMELVL","选择用户名过滤等级");
define("_MD_AM_STRICT","严格(仅能使用字母和数字)");
define("_MD_AM_MEDIUM","中等");
define("_MD_AM_LIGHT","轻度(多字节字符时，推荐使用)");
define("_MD_AM_USERCOOKIE","用户cookies的名字");
define("_MD_AM_USERCOOKIEDSC","cookie仅包含用户名，并将在用户的电脑保存一年(如果用户愿意)，如果用户拥有这个cookie, 用户名会自动插入登录框中。");
define("_MD_AM_USEMYSESS","启用自定义对话");
define("_MD_AM_USEMYSESSDSC","选择yes自定义相关值得对话");
define("_MD_AM_SESSNAME","对话名称");
define("_MD_AM_SESSNAMEDSC","对话的名称(只有在启动了自定义对话后才有效)");
define("_MD_AM_SESSEXPIRE","对话过期");
define("_MD_AM_SESSEXPIREDSC","对话的最大期限，以分钟计算。(只有在启动了自定义对话后才有效，只有PHP4.2.0或更新版时才会工作)");
define("_MD_AM_MYIP","你的IP地址");
define("_MD_AM_MYIPDSC","这个IP将不会被广告条的点击计算在内");
define("_MD_AM_ALWDHTML","所有留言中都可用HTML标签");
define("_MD_AM_INVLDMINPASS","无效的密码最短长度值");
define("_MD_AM_INVLDUCOOK","无效的用户cookie名称值");
define("_MD_AM_INVLDSCOOK","无效的对话cookie名称值");
define("_MD_AM_INVLDSEXP","无效的对话过期时间值");
define("_MD_AM_ADMNOTSET","管理员邮箱未设置");
define("_MD_AM_YES","Yes");
define("_MD_AM_NO","No");
define("_MD_AM_DONTCHNG","不要更改!");
define("_MD_AM_REMEMBER","此文件权限更改为chmod 666，以便让系统能正常写入");
define("_MD_AM_IFUCANT","如果不能更改权限，那就只能手工编辑余下的文件");

define("_MD_AM_COMMODE","留言显示的默认模式");
define("_MD_AM_COMORDER","留言显示的默认次序");
define("_MD_AM_ALLOWHTML","在用户留言中允许使用HTML标签吗?");
define("_MD_AM_DEBUGMODE","开发者面板");
define("_MD_AM_DEBUGMODEDSC","一些调试选项，运行中的网站应将这些选项关闭");
define("_MD_AM_AVATARALLOW","允许上传自定义头像吗?");
define("_MD_AM_AVATARALLOWDSC","如果启用这一选项, 那就要为头像设置更多的参数(宽度, 高度, 大小).");
define('_MD_AM_AVATARMP','最少留言需求');
define('_MD_AM_AVATARMPDSC','输入可上传自定义头像所需的最少留言数量');
define("_MD_AM_AVATARW","头像最大宽度(像素)");
define("_MD_AM_AVATARH","头像最大高度(像素)");
define("_MD_AM_AVATARMAX","头像最大文件尺寸(byte)");
define("_MD_AM_AVATARCONF","自定义头像设置");
define("_MD_AM_CHNGUTHEME","更改所有用户的主题");
define("_MD_AM_NOTIFYTO","选择当哪组有新用户注册时发送通知");
define("_MD_AM_ALLOWTHEME","允许用户选择主题吗?");
define("_MD_AM_ALLOWIMAGE","允许用户在留言中显示图片吗?");

define("_MD_AM_USERACTV","用户帐户需手动激活(推荐)");
define("_MD_AM_AUTOACTV","自动激活");
define("_MD_AM_ADMINACTV","管理员激活");
define("_MD_AM_REGINVITE","邀请注册");
define("_MD_AM_ACTVTYPE","选择新用户注册的邀请类型");
define("_MD_AM_ACTVGROUP","选择需发送激活邮件的用户组");
define("_MD_AM_ACTVGROUPDSC","尽在选择了'管理员激活'时才有效");
define('_MD_AM_USESSL', '使用SSL登录吗?');
define('_MD_AM_USESSLDSC', '只有在拥有一分SSL许可时才能选择YES，如果需要SSL，请从ImpressCMS EXTRA文件夹复制授权文件到网站根目录。');
define('_MD_AM_SSLPOST', 'SSL Post变量名');
define('_MD_AM_SSLPOSTDSC', '变量名通过POST来转换对话值，如果不确定，请设一个很难被猜中的名字');
define('_MD_AM_DEBUGMODE0','Off');
define('_MD_AM_DEBUGMODE1','启用调试(inline模式)');
define('_MD_AM_DEBUGMODE2','启用调试(popup模式)');
define('_MD_AM_DEBUGMODE3','Smarty模板调试');
define('_MD_AM_MINUNAME', '用户名最短长度');
define('_MD_AM_MAXUNAME', '用户名最长长度');
define('_MD_AM_GENERAL', '常规设定');
define('_MD_AM_USERSETTINGS', '用户设定');
define('_MD_AM_ALLWCHGMAIL', '允许用户更改邮箱地址吗?');
define('_MD_AM_ALLWCHGMAILDSC', '');
define('_MD_AM_IPBAN', 'IP禁止');
define('_MD_AM_BADEMAILS', '请输入不能用在用户资料的电子邮箱地址');
define('_MD_AM_BADEMAILSDSC', '用 <b>|</b> 符号隔开每个地址，不区分大小写，可使用正则表达式');
define('_MD_AM_BADUNAMES', '请输入不允许用户使用的名字');
define('_MD_AM_BADUNAMESDSC', '用 <b>|</b> 符号隔开每个名字, 不区分大小写，可使用正则表达式');
define('_MD_AM_DOBADIPS', '启用IP地址禁止吗?');
define('_MD_AM_DOBADIPSDSC', '由指定IP地址来的用户将不能访问网站');
define('_MD_AM_BADIPS', '请输入要禁用的IP地址。<br />用 <b>|</b> 符号隔开每个地址, 不区分大小写，可使用正则表达式');
define('_MD_AM_BADIPSDSC', '^aaa.bbb.ccc 表示不允许IP地址由aaa.bbb.ccc开头的访客访问<br />aaa.bbb.ccc$ 表示不允许IP地址由 aaa.bbb.ccc 结尾的访客访问<br />aaa.bbb.ccc 表示不允许IP中包含 aaa.bbb.ccc 的访客访问');
define('_MD_AM_PREFMAIN', '首选项');
define('_MD_AM_METAKEY', 'Meta关键字');
define('_MD_AM_METAKEYDSC', 'Meta关键字标签。为一些描述你网站内容的字、词，请用英文逗号或空格隔开每个关键词。(如：ImpressCMS, PHP, mySQL, portal system)');
define('_MD_AM_METARATING', 'Meta排名');
define('_MD_AM_METARATINGDSC', 'Meta排名标签定义站点的使用年龄和排名');
define('_MD_AM_METAOGEN', '常规');
define('_MD_AM_METAO14YRS', '14年');
define('_MD_AM_METAOREST', '限制级的');
define('_MD_AM_METAOMAT', '成年的');
define('_MD_AM_METAROBOTS', 'Meta机器人');
define('_MD_AM_METAROBOTSDSC', '机器人标签向搜索引擎声明哪些内容需被索引');
define('_MD_AM_INDEXFOLLOW', '索引, 跟踪');
define('_MD_AM_NOINDEXFOLLOW', '不索引, 跟踪');
define('_MD_AM_INDEXNOFOLLOW', '索引, 不跟踪');
define('_MD_AM_NOINDEXNOFOLLOW', '不索引, 不跟踪');
define('_MD_AM_METAAUTHOR', 'Meta作者');
define('_MD_AM_METAAUTHORDSC', 'Meta作者标签定义正被阅读的文档的作者名，支持的数据格式有名称、网站管理员电子邮箱地址、公司名及网址');
define('_MD_AM_METACOPYR', 'Meta版权');
define('_MD_AM_METACOPYRDSC', 'Meta版权标签定义网站页面文章的版权声明');
define('_MD_AM_METADESC', 'Meta描述');
define('_MD_AM_METADESCDSC', 'Meta描述标签为网站内容的概述');
define('_MD_AM_METAFOOTER', 'Meta + 页脚');
define('_MD_AM_FOOTER', '页脚');
define('_MD_AM_FOOTERDSC', '请输入完整的链接包括http://, 否则链接无法在模块页面中正常使用');
define('_MD_AM_CENSOR', '文字审查');
define('_MD_AM_DOCENSOR', '启用审查敏感字词吗?');
define('_MD_AM_DOCENSORDSC', '启用此项，文字将会被审查。关闭此项会提升网站访问速度');
define('_MD_AM_CENSORWRD', '敏感字词');
define('_MD_AM_CENSORWRDDSC', '输入用户留言中的敏感字词。<br />用 <b>|</b> 符号隔开，不区分大小写');
define('_MD_AM_CENSORRPLC', '敏感字词的替代显示:');
define('_MD_AM_CENSORRPLCDSC', '敏感字词将由下面文本框中的字符替代');

define('_MD_AM_SEARCH', '搜索选项');
define('_MD_AM_DOSEARCH', '启用全局搜索吗?');
define('_MD_AM_DOSEARCHDSC', '允许在留言及项目中进行搜索');
define('_MD_AM_MINSEARCH', '关键字最短长度');
define('_MD_AM_MINSEARCHDSC', '输入用户搜索时需要输入的关键字的最短长度');
define('_MD_AM_MODCONFIG', '模块设置选项');
define('_MD_AM_DSPDSCLMR', '显示免责声明吗?');
define('_MD_AM_DSPDSCLMRDSC', '选择yes则在注册页面中显示免责声明');
define('_MD_AM_REGDSCLMR', '注册免责声明');
define('_MD_AM_REGDSCLMRDSC', '输入注册时的免责声明');
define('_MD_AM_ALLOWREG', '允许新用户注册吗?');
define('_MD_AM_ALLOWREGDSC', '选择yes允许新用户注册');
define('_MD_AM_THEMEFILE', '检查需要修改的模板?');
define('_MD_AM_THEMEFILEDSC', '如启用此选项，则修改过的模板会在显示时重新编译，在投入运营的网站中，这一选项必须关闭');
define('_MD_AM_CLOSESITE', '关闭网站?');
define('_MD_AM_CLOSESITEDSC', '选择yes关闭网站，只有特定群组的用户可以访问');
define('_MD_AM_CLOSESITEOK', '选择在网站关闭时仍可以访问的组');
define('_MD_AM_CLOSESITEOKDSC', '默认管理员组织中的用户任何时候都有权访问');
define('_MD_AM_CLOSESITETXT', '关闭网站的原因');
define('_MD_AM_CLOSESITETXTDSC', '这些文字将在网站关闭时显示');
define('_MD_AM_SITECACHE', '站点缓存');
define('_MD_AM_SITECACHEDSC', '缓存站点的全部内容可在一段特定的时间内增强网站性能表现。设置了站点缓存会覆盖模块级别、区块级别、及模块项目级别的缓存');
define('_MD_AM_MODCACHE', '模块缓存');
define('_MD_AM_MODCACHEDSC', '缓存模块的内容可在一段特定的时间内增强该模块的性能表现。设置了模块缓存会覆盖模块项目级别的缓存');
define('_MD_AM_NOMODULE', '没有模块可被缓存');
define('_MD_AM_DTPLSET', '默认模板设置');
define('_MD_AM_DTPLSETDSC', '如果希望选择另外的模板设置为默认。则必先在系统内创建一个备份，再将那备份设置为默认的。');
define('_MD_AM_SSLLINK', 'URL地址在使用SSL登录的页面会被锁定');

// added for mailer
define("_MD_AM_MAILER","邮件设置");
define("_MD_AM_MAILER_MAIL","");
define("_MD_AM_MAILER_SENDMAIL","");
define("_MD_AM_MAILER_","");
define("_MD_AM_MAILFROM","来自");
define("_MD_AM_MAILFROMDESC","");
define("_MD_AM_MAILFROMNAME","发件人");
define("_MD_AM_MAILFROMNAMEDESC","");
// RMV-NOTIFY
define("_MD_AM_MAILFROMUID","来自用户");
define("_MD_AM_MAILFROMUIDDESC","当系统发送私人信息时，需显示怎样的用户名?");
define("_MD_AM_MAILERMETHOD","邮件传输方式");
define("_MD_AM_MAILERMETHODDESC","邮件传输的方式。默认为\"mail\", 只有在默认的出了故障时才使用其它方式。");
define("_MD_AM_SMTPHOST","SMTP主机");
define("_MD_AM_SMTPHOSTDESC","要连接到的SMTP服务器列表。");
define("_MD_AM_SMTPUSER","SMTP授权用户名");
define("_MD_AM_SMTPUSERDESC","拥有授权的SMTP主机用户名");
define("_MD_AM_SMTPPASS","SMTP授权密码");
define("_MD_AM_SMTPPASSDESC","拥有授权的SMTP主机密码");
define("_MD_AM_SENDMAILPATH","sendmail路径");
define("_MD_AM_SENDMAILPATHDESC","网站服务器中sendmail程序的路径(或替代路径)");
define("_MD_AM_THEMEOK","可选主题");
define("_MD_AM_THEMEOKDSC","可供用户选择的主题");

// Xoops Authentication constants
define("_MD_AM_AUTH_CONFOPTION_XOOPS", "ImpressCMS数据库");
define("_MD_AM_AUTH_CONFOPTION_LDAP", "标准LDAP目录");
define("_MD_AM_AUTH_CONFOPTION_AD", "微软Active目录 &copy");
define("_MD_AM_AUTHENTICATION", "认证方式管理");
define("_MD_AM_AUTHMETHOD", "认证方式");
define("_MD_AM_AUTHMETHODDESC", "选择对用户身份进行去人的方式");
define("_MD_AM_LDAP_MAIL_ATTR", "LDAP - 邮件网域名称");
define("_MD_AM_LDAP_MAIL_ATTR_DESC","LDAP树状目录中邮件的网域名称");
define("_MD_AM_LDAP_NAME_ATTR","LDAP - 通用名称（CN）的网域名");
define("_MD_AM_LDAP_NAME_ATTR_DESC","LDAP 目录中通用名称（Common Name）的网域名称");
define("_MD_AM_LDAP_SURNAME_ATTR","LDAP - 姓的网域名称");
define("_MD_AM_LDAP_SURNAME_ATTR_DESC","LDAP 目录中姓（Surname）的网域名称");
define("_MD_AM_LDAP_GIVENNAME_ATTR","LDAP - 名的网域名称");
define("_MD_AM_LDAP_GIVENNAME_ATTR_DSC","LDAP 目录中名（Given Name）的网域名称");
define("_MD_AM_LDAP_BASE_DN", "LDAP - 基本识别名");
define("_MD_AM_LDAP_BASE_DN_DESC", "LDAP 树状目录的基本识别名（Distinguished Name）");
define("_MD_AM_LDAP_PORT","LDAP - 端口");
define("_MD_AM_LDAP_PORT_DESC","登录LDAP目录服务器的端口号");
define("_MD_AM_LDAP_SERVER","LDAP - 服务器名称");
define("_MD_AM_LDAP_SERVER_DESC","LDAP目录服务器的名称");

define("_MD_AM_LDAP_MANAGER_DN", "LDAP管理员识别名");
define("_MD_AM_LDAP_MANAGER_DN_DESC", "允许搜索的用户的识别名 (如 管理员)");
define("_MD_AM_LDAP_MANAGER_PASS", "LDAP 管理员密码");
define("_MD_AM_LDAP_MANAGER_PASS_DESC", "允许搜索的用户的密码");
define("_MD_AM_LDAP_VERSION", "LDAP 版本协议");
define("_MD_AM_LDAP_VERSION_DESC", "LDAP 版本协定: 2 或 3");
define("_MD_AM_LDAP_USERS_BYPASS", " 用户可以绕过LDAP认证");
define("_MD_AM_LDAP_USERS_BYPASS_DESC", "以ImpressCMS方式验证的用户<br />以“ |”符号将每个用户隔开);

define("_MD_AM_LDAP_USETLS", " 使用TLS连接");
define("_MD_AM_LDAP_USETLS_DESC", "使用 TLS (Transport Layer Security-传输层安全) 连接. TLS 使用标准的 389 端口<br />且LDAP版本为3");

define("_MD_AM_LDAP_LOGINLDAP_ATTR","用于查找用户的 LDAP 属性");
define("_MD_AM_LDAP_LOGINLDAP_ATTR_D","当在识别名（DN）选项中设定登录名为“YES”时，必须与ImpressCMS中的登录名一致");
define("_MD_AM_LDAP_LOGINNAME_ASDN", "在识别名中使用登录名");
define("_MD_AM_LDAP_LOGINNAME_ASDN_D", "LDAO 的识别名（DN）中使用 ImpressCMS 登录名（如：uid=<loginname>,dc=impresscms,dc=org) <br/>该执行程序不用查找，直接在目录服务器中读取");

define("_MD_AM_LDAP_FILTER_PERSON", "LDAP 查找用户的筛选");
define("_MD_AM_LDAP_FILTER_PERSON_DESC", "用户查找的特殊 LDAP 筛选器. @@loginname@@ 被取代成为用户的登录名<br />如果不清楚，请留空' !" .
		"<br />例如: (&(objectclass=person)(samaccountname=@@loginname@@)) for AD" .
		"<br />例如: (&(objectclass=inetOrgPerson)(uid=@@loginname@@)) for LDAP");

define("_MD_AM_LDAP_DOMAIN_NAME", "域名");
define("_MD_AM_LDAP_DOMAIN_NAME_DESC", "Windows 域名（只有活动的目录服务器（ADS）和（NT）服务器");

define("_MD_AM_LDAP_PROVIS", "自动建立用户帐户");
define("_MD_AM_LDAP_PROVIS_DESC", "如果没有ImpressCMS用户数据库，则自动建立");

define("_MD_AM_LDAP_PROVIS_GROUP", "默认新用户群组");
define("_MD_AM_LDAP_PROVIS_GROUP_DSC", "新用户将会被归入该组");

define("_MD_AM_LDAP_FIELD_MAPPING_ATTR", "ImpressCMS认证服务器栏位映射");
define("_MD_AM_LDAP_FIELD_MAPPING_DESC", "在此输入ImpressCMS数据库栏位与LDAP认证系统栏位的映射" .
		"<br /><br />格式 [ImpressCMS数据库栏位]=[LDAP属性]" .
		"<br />例如: email=mail" .
		"<br />用“|”符号将每项隔开" .
		"<br /><br />!! 仅适用于有经验的使用者!!");

define("_MD_AM_LDAP_PROVIS_UPD", "从认证服务器来维护ImpressCMS用户帐号");
define("_MD_AM_LDAP_PROVIS_UPD_DESC", "ImpressCMS用户帐号总是与认证服务器进行同步");

//lang constants for secure password
define("_MD_AM_PASSLEVEL","最低安全级别");
define("_MD_AM_PASSLEVEL_DESC","定义用户密码的安全级别。通常不要设得太低或太高，合理就行。");
define("_MD_AM_PASSLEVEL1","无(不安全)");
define("_MD_AM_PASSLEVEL2","弱");
define("_MD_AM_PASSLEVEL3","合理");
define("_MD_AM_PASSLEVEL4","强");
define("_MD_AM_PASSLEVEL5","安全");
define("_MD_AM_PASSLEVEL6","未分类");

define("_MD_AM_RANKW","等级图像最大宽度(像素)");
define("_MD_AM_RANKH","等级图像最大高度(像素)");
define("_MD_AM_RANKMAX","等级图像最大文件尺寸(byte)");

define("_MD_AM_MULTILANGUAGE","多语言");
define("_MD_AM_ML_ENABLE","启用多语言");
define("_MD_AM_ML_ENABLEDSC","设为Yes则在全站范围内启用多语言");
define("_MD_AM_ML_TAGS","语言标记");
define("_MD_AM_ML_TAGSDSC","输入每种语言的标记，用英文逗号“,”将不同的标记隔开。如英语和法语，则标记为:en,fr");
define("_MD_AM_ML_NAMES","语言名称");
define("_MD_AM_ML_NAMESDSC","输入每种语言的名称,用英文逗号“,”隔开");
define("_MD_AM_ML_CAPTIONS","语言样例");
define("_MD_AM_ML_CAPTIONSDSC","输入每种语言的样例，即用那种语言来表述自己，如：简体中文,English,Deutsch等");
define("_MD_AM_ML_CHARSET","字符编码");
define("_MD_AM_ML_CHARSETDSC","输入每种语言的字符编码");

define("_MD_AM_REMEMBERME","登录时启用'记住我'功能");
define("_MD_AM_REMEMBERMEDSC","'记住我'功能可能会带来用户安全隐患，使用时请务必清楚其中的风险");

define("_MD_AM_PRIVDPOLICY","启用站点的'隐私条款'");
define("_MD_AM_PRIVDPOLICYDSC","'隐私条款' 将会在用户注册时显示");
define("_MD_AM_PRIVPOLICY","输入网站的'隐私条款'.");
define("_MD_AM_PRIVPOLICYDSC","");

define("_MD_AM_WELCOMEMSG","给新注册用户发送欢迎信息");
define("_MD_AM_WELCOMEMSGDSC","当新用户帐户被激活时发送欢迎信息，信息的内容可在下面的选项中调整");
define("_MD_AM_WELCOMEMSG_CONTENT","欢迎信息内容");
define("_MD_AM_WELCOMEMSG_CONTENTDSC","可编辑发给新用户的信息。可使用如下标签: <br /><br />- {UNAME} = 用户的用户名<br />- {X_UEMAIL} = 用户的电子邮箱地址<br />- {X_ADMINMAIL} = 管理员邮箱地址<br />- {X_SITENAME} = 网站名称<br />- {X_SITEURL} = 网站网址");

define("_MD_AM_SEARCH_USERDATE","在查找结果中显示用户和日期");
define("_MD_AM_SEARCH_USERDATEDSC","");
define("_MD_AM_SEARCH_NO_RES_MOD","在查找结果中显示未匹配的模块");
define("_MD_AM_SEARCH_NO_RES_MODDSC","");
define("_MD_AM_SEARCH_PER_PAGE","每页显示的搜索结果");
define("_MD_AM_SEARCH_PER_PAGEDSC","");

define("_MD_AM_EXT_DATE","要使用其它的/本地的日期功能吗?");
define("_MD_AM_EXT_DATEDSC","注意: 如激活此选项, ImpressCMS将使用其它的日历脚本<b>只有</b>当在运行网站有此脚本时有效<br />请访问<a href='http://wiki.impresscms.org/index.php?title=Extended_date_function'>其它日历功能</a>获得更多信息");

define("_MD_AM_EDITOR_DEFAULT","默认编辑器");
define("_MD_AM_EDITOR_DEFAULT_DESC","选择全站的默认编辑器");

define("_MD_AM_EDITOR_ENABLED_LIST","激活编辑器");
define("_MD_AM_EDITOR_ENABLED_LIST_DESC","选择可选的编辑器模块(如果模块选择编辑器的配置的话)");

define("_MD_AM_ML_AUTOSELECT_ENABLED","根据浏览器的设置自动选择语言");

define("_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE","匿名访客可查看用户资料");
define("_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE_DESC","如果选择YES, 所有访客都可查看用户的资料，对于社区类网站来说可能不错，但却不利于隐私保护");

define("_MD_AM_ENC_TYPE","更改密码密匙(默认为SHA256)");
define("_MD_AM_ENC_TYPEDSC","更改加密用户密码的算法<br />更改此项将会是全部密码无效! 全部用户都需再设置密码。");
define("_MD_AM_ENC_MD5","MD5 (不推荐)");
define("_MD_AM_ENC_SHA256","SHA 256 (推荐)");
define("_MD_AM_ENC_SHA384","SHA 384");
define("_MD_AM_ENC_SHA512","SHA 512");
define("_MD_AM_ENC_RIPEMD128","RIPEMD 128");
define("_MD_AM_ENC_RIPEMD160","RIPEMD 160");
define("_MD_AM_ENC_WHIRLPOOL","WHIRLPOOL");
define("_MD_AM_ENC_HAVAL1284","HAVAL 128,4");
define("_MD_AM_ENC_HAVAL1604","HAVAL 160,4");
define("_MD_AM_ENC_HAVAL1924","HAVAL 192,4");
define("_MD_AM_ENC_HAVAL2244","HAVAL 224,4");
define("_MD_AM_ENC_HAVAL2564","HAVAL 256,4");
define("_MD_AM_ENC_HAVAL1285","HAVAL 128,5");
define("_MD_AM_ENC_HAVAL1605","HAVAL 160,5");
define("_MD_AM_ENC_HAVAL1925","HAVAL 192,5");
define("_MD_AM_ENC_HAVAL2245","HAVAL 224,5");
define("_MD_AM_ENC_HAVAL2565","HAVAL 256,5");

//Content Manager
define("_MD_AM_CONTMANAGER","文章管理");
define("_MD_AM_DEFAULT_CONTPAGE","默认页面");
define("_MD_AM_DEFAULT_CONTPAGEDSC","选择文章管理器中显示给用户的默认页面。不指定则显示最近创建的页面。");
define("_MD_AM_CONT_SHOWNAV","对用户显示导航菜单吗?");
define("_MD_AM_CONT_SHOWNAVDSC","选择yes显示文章管理导航菜单");
define("_MD_AM_CONT_SHOWSUBS","显示相关页面吗?");
define("_MD_AM_CONT_SHOWSUBSDSC","选择yes显示链接到文章管理页面的相关页面");
define("_MD_AM_CONT_SHOWPINFO","显示留言者及发布信息吗?");
define("_MD_AM_CONT_SHOWPINFODSC","选择yes显示留言者及发布信息");
define("_MD_AM_CONT_ACTSEO","在url中显示菜单标题来代替id吗?(有助于seo)");
define("_MD_AM_CONT_ACTSEODSC","选择yes则在页面的url中显示菜单标题来代替id");
//Captcha (Security image)
define('_MD_AM_USECAPTCHA', '需要在用户注册时使用验证码验证吗?');
define('_MD_AM_USECAPTCHADSC', '选择yes则会在用户注册时要求填写验证码（防止滥发广告）');
define('_MD_AM_USECAPTCHAFORM', '需要在留言中使用验证码吗?');
define('_MD_AM_USECAPTCHAFORMDSC', '选择yes则会在用户留言时要求填写验证码，以防止滥发广告');
define('_MD_AM_ALLWHTSIG', '允许在签名中显示外链图像及使用HTML标签吗?');
define('_MD_AM_ALLWHTSIGDSC', '如果有攻击者使用[img]标签显示外链图片, 他会知道IP数及网站的用户访问次数<br />允许HTML标签可能会导致恶意脚本在更改签名时被插入');
define('_MD_AM_ALLWSHOWSIG', '允许在用户的个人资料及留言中使用签名吗?');
define('_MD_AM_ALLWSHOWSIGDSC', '启用此选项，则用户可以留言后显示其个性签名');
// < personalizações > fabio - Sat Apr 28 11:55:00 BRT 2007 11:55:00
define("_MD_AM_PERSON","用户分析");
define("_MD_AM_GOOGLE_ANA","Google分析");
define("_MD_AM_GOOGLE_ANA_DESC","输入Google分析编号, 如: <small>_uacct = \"UA-<font color=#FF0000><b>xxxxxx-x</b></font>\"</small><br />或者<small><br />var pageTracker = _gat._getTracker( UA-\"<font color=#FF0000><b>xxxxxx-x</b></font>\");</small> (在红色黑体字内填写分析编号).");
define("_MD_AM_LLOGOADM","后台左边logo");
define("_MD_AM_LLOGOADM_DESC"," 选择图片作为管理员后台左上角logo <br /><i>要选择图片，则系统中至少要有一个图片目录> 图片</i> ");
define("_MD_AM_LLOGOADM_URL","后台左边logo URL链接");
define("_MD_AM_LLOGOADM_ALT","后台左边logo链接标题");
define("_MD_AM_RLOGOADM","后台右边logo");
define("_MD_AM_RLOGOADM_DESC"," 选择图片作为管理员后台右上角logo <br /><i>要选择图片，则系统中至少要有一个图片目录> 图片</i> ");
define("_MD_AM_RLOGOADM_URL","后台右边logo URL链接");
define("_MD_AM_RLOGOADM_ALT","后台右边logo链接标题");
define("_MD_AM_METAGOOGLE","Google Meta 标签");
define("_MD_AM_METAGOOGLE_DESC",'由Google生成的代码，用于确认网站的所有者，以便查看全部页面的错误状态。写下id-代码, 如: <small>meta name="verify-v1" content="<font color=#FF0000><b>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</b></font>" </small><br />(在红色黑体字内填写)<br />
Further information at <a href="http://www.google.com/webmasters/" target="_blank">http://www.google.com/webmasters</a>.');
define("_MD_AM_RSSLOCAL","后台新闻信息Feed URL地址");
define("_MD_AM_RSSLOCAL_DESC","RSS feed的URL地址，显示来自ImpressCMS项目的新闻 > 新闻");
define("_MD_AM_FOOTADM","后台页脚");
define("_MD_AM_FOOTADM_DESC","后台页面页脚显示的内容");
define("_MD_AM_EMAILTTF","保护电子邮箱地址的字体");
define("_MD_AM_EMAILTTF_DESC","选择一种字体，用于生成电子邮箱地址保护<br /><i>要启用此选项，必须先启用“保护邮箱免于广告邮件的滥发”选项</i>");
define("_MD_AM_EMAILLEN","保护邮箱地址的字体的大小");
define("_MD_AM_EMAILLEN_DESC","<i>要启用此选项，必须先启用“保护邮箱免于广告邮件的滥发”选项</i>");
define("_MD_AM_EMAILCOLOR","保护邮箱地址的字体颜色");
define("_MD_AM_EMAILCOLOR_DESC","<i>要启用此选项，必须先启用“保护邮箱免于广告邮件的滥发”选项</i>");
define("_MD_AM_EMAILSHADOW","保护邮箱地址的字体阴影颜色");
define("_MD_AM_EMAILSHADOW_DESC","选择保护邮箱地址的字体的阴影的颜色。留空白的话，则无阴影<br /><i>要启用此选项，必须先启用“保护邮箱免于广告邮件的滥发”选项</i>");
define("_MD_AM_SHADOWX","阴影在X方向的偏移");
define("_MD_AM_SHADOWX_DESC","输入像素值，阴影将会在邮箱地址的水平方向偏移<br /><i>要启用此选项，则“选择保护邮箱地址的字体的阴影的颜色”一项不能留空</i>");
define("_MD_AM_SHADOWY","阴影在Y方向的偏移");
define("_MD_AM_SHADOWY_DESC","输入像素值，阴影将会在邮箱地址的竖直方向偏移<br /><i>要启用此选项，则“选择保护邮箱地址的字体的阴影的颜色”一项不能留空</i>");
define("_MD_AM_EDITREMOVEBLOCK","编辑、删除用户方的区块");
define("_MD_AM_EDITREMOVEBLOCKDSC","启用此选项，则会在区块标题中会显示编辑及删除两个图标");

define("_MD_AM_EMAILPROTECT","保护邮箱免于广告邮件的滥发");
define("_MD_AM_EMAILPROTECTDSC","启用此项，可保护站点中显示的邮箱地址免于遭受广告邮件的滥发<br /><i>要使用reCAPTCHA Mailhide功能，则必须先安装 mcrypt php 模块</i>");
define("_MD_AM_MULTLOGINPREVENT","预防同一用户重复登陆?");
define("_MD_AM_MULTLOGINPREVENTDSC","启用此项，如果一用户已经登陆，则必须先登出才能再次登陆");
define("_MD_AM_MULTLOGINMSG","重复登陆导向页面显示信息:");
define("_MD_AM_MULTLOGINMSG_DESC","当用户有重复登陆动作时，显示的信息。<br><i>此选项仅在“预防同一用户重复登陆?'设为Yes时才能启用</i>");
define("_MD_AM_GRAVATARALLOW","允许使用GRAVATAR头像吗?");
define("_MD_AM_GRAVATARALWDSC","显示用户帐户头像来自<a href='http://www.gravatar.com/' target='_blank'>Gravatar</a>，提供免费的头像服务。ImpressCMS 将会自动显示存放在 Gravatar 主机的图像，使用用户的邮箱地址连接");

define("_MD_AM_SHOW_ICMSMENU","要显示 ImpressCMS 项目下拉菜单吗?");
define("_MD_AM_SHOW_ICMSMENU_DESC","选择 NO 不显示下拉菜单，选择 YES 显示");

define("_MD_AM_SHORTURL","长URL地址要去尾吗?");
define("_MD_AM_SHORTURLDSC","此选项设为Yes，则站点网页的URL地址会依设定的字符长度，自动去尾。注意：在论坛中的地址常常会因此设置而破坏");
define("_MD_AM_URLLEN","URL 最大长度");
define("_MD_AM_URLLEN_DESC","URL 最大字符长度。多出的字符将会被隐去<br /><i>此选项仅在“长URL地址要去尾吗?”一项设为Yes时才有效</i>");
define("_MD_AM_PRECHARS","起始字符总数");
define("_MD_AM_PRECHARS_DESC","URL地址起始显示的字符数<br /><i>此选项仅在“长URL地址要去尾吗?”一项设为Yes时才有效</i>");
define("_MD_AM_LASTCHARS","结尾字符总数");
define("_MD_AM_LASTCHARS_DESC","URL地址结尾显示的字符数<br /><i>此选项仅在“长URL地址要去尾吗?”一项设为Yes时才有效</i>");
define("_MD_AM_SIGMAXLENGTH","用户签名的最大字符数");
define("_MD_AM_SIGMAXLENGTHDSC","设置用户的签名最大字符数<br /> 超过此长度的字符将会被忽略<br /><i>注意：长签名常常会因此设置而破坏</i>");

define("_MD_AM_AUTHOPENID","启用 OpenID 授权");
define("_MD_AM_AUTHOPENIDDSC","选择 Yes 启用 OpenID 授权。用户可以使用他们的 OpenID 帐户登陆网站。关于ImpressCMS与OpenID集成的详细信息，可访问<a href='http://wiki.impresscms.org/index.php?title=ImpressCMS_OpenID'>我们的wiki</a>.");
define("_MD_AM_USE_GOOGLE_ANA"," 启用Google分析吗?");
define("_MD_AM_USE_GOOGLE_ANA_DESC","");

// added in 1.1.2
define("_MD_AM_UNABLEENCCLOSED","数据库更新失败，不能在网站关闭时更改密码密匙");

######################## Added in 1.2 ###################################
define("_MD_AM_CAPTCHA","验证码设置");
define("_MD_AM_CAPTCHA_MODE","验证码模式");
define("_MD_AM_CAPTCHA_MODEDSC","请选择站点的验证码模式");
define("_MD_AM_CAPTCHA_SKIPMEMBER","无需验证码的群组");
define("_MD_AM_CAPTCHA_SKIPMEMBERDSC","选择无需验证码的群组。这些群组将不会看到任何验证码。");
define("_MD_AM_CAPTCHA_CASESENS","验证码区分大小写");
define("_MD_AM_CAPTCHA_CASESENSDSC","验证码显示图片区分大小写");
define("_MD_AM_CAPTCHA_MAXATTEMP","最大尝试次数");
define("_MD_AM_CAPTCHA_MAXATTEMPDSC","每个进程的最大尝试次数");
define("_MD_AM_CAPTCHA_NUMCHARS","最大字符数");
define("_MD_AM_CAPTCHA_NUMCHARSDSC","最大生成字符数");
define("_MD_AM_CAPTCHA_FONTMIN","最小字体");
define("_MD_AM_CAPTCHA_FONTMINDSC","");
define("_MD_AM_CAPTCHA_FONTMAX","最大字体");
define("_MD_AM_CAPTCHA_FONTMAXDSC","");
define("_MD_AM_CAPTCHA_BGTYPE","背景类型");
define("_MD_AM_CAPTCHA_BGTYPEDSC","图像模式背景类型");
define("_MD_AM_CAPTCHA_BGNUM","背景图像");
define("_MD_AM_CAPTCHA_BGNUMDSC","背景图像生成数量");
define("_MD_AM_CAPTCHA_POLPNT","多边形点数");
define("_MD_AM_CAPTCHA_POLPNTDSC","需生成的多边形的点数");
define("_MD_AM_BAR","条状"); 
define("_MD_AM_CIRCLE","圆圈");
define("_MD_AM_LINE","直线");
define("_MD_AM_RECTANGLE","矩形");
define("_MD_AM_ELLIPSE","月牙形");
define("_MD_AM_POLYGON","多边形");
define("_MD_AM_RANDOM","随机");
define("_MD_AM_CAPTCHA_IMG","图像");
define("_MD_AM_CAPTCHA_TXT","文字");
define("_MD_AM_CAPTCHA_OFF","不启用");
define("_MD_AM_CAPTCHA_SKIPCHAR","跳过字符");
define("_MD_AM_CAPTCHA_SKIPCHARDSC","此选项将跳过生成验证码时输入的文字");
define('_MD_AM_PAGISTYLE','翻页链接风格:');
define('_MD_AM_PAGISTYLE_DESC','选择翻页链接的风格');
define('_MD_AM_ALLWCHGUNAME', '允许用户更改显示昵称吗?');
define('_MD_AM_ALLWCHGUNAMEDSC', '');
define("_MD_AM_JALALICAL","使用Jalali日历吗?");
define("_MD_AM_JALALICALDSC","选择此项，将可以使用拓展的日历格式<br />请注意，此日历在有些浏览器中可能不能正常显示");
define("_MD_AM_NOMAILPROTECT","无");
define("_MD_AM_GDMAILPROTECT","GD 防护");
define("_MD_AM_REMAILPROTECT","re-Captcha");
define("_MD_AM_RECPRVKEY","reCaptcha 隐私 api 代码");
define("_MD_AM_RECPRVKEY_DESC","");
define("_MD_AM_RECPUBKEY","reCaptcha 公共 api 代码");
define("_MD_AM_RECPUBKEY_DESC","");
define("_MD_AM_CONT_NUMPAGES","标签模式列表页数");
define("_MD_AM_CONT_NUMPAGESDSC","定义标签模式用户方列表页数");
define("_MD_AM_CONT_TEASERLENGTH","Teaser Length");
define("_MD_AM_CONT_TEASERLENGTHDSC","Number of characters of the page teaser in list by tag mode.<br />Set to 0 to not limit.");
define("_MD_AM_STARTPAGEDSC","选择显示给各群组起始页面的模块或页面");
define("_MD_AM_DELUSRES","移除未激活用户");
define("_MD_AM_DELUSRESDSC","此选项将会移除注册但X天未激活的用户<br />请输入天数");
define("_MD_AM_PLUGINS","插件管理");
define("_MD_AM_SELECTSPLUGINS","选择允许使用的插件");
define("_MD_AM_SELECTSPLUGINS_DESC","选择哪些插件用于文字过滤 used to sanitize your texts.");
define("_MD_AM_GESHI_DEFAULT","选择用于语法高亮的插件");
define("_MD_AM_GESHI_DEFAULT_DESC","用于代码的语法高亮");
define("_MD_AM_SELECTSHIGHLIGHT","选择代码高亮类型");
define("_MD_AM_SELECTSHIGHLIGHT_DESC","选择代码的高亮类型");
define("_MD_AM_HIGHLIGHTER_GESHI","GeSHi 语法高亮");
define("_MD_AM_HIGHLIGHTER_PHP","php 语法高亮");
define("_MD_AM_HIGHLIGHTER_OFF","不启用");
define('_MD_AM_DODEEPSEARCH', "启用'深度搜索'吗?");
define('_MD_AM_DODEEPSEARCHDSC', "可以初始化搜索结果显示每个模块的点击数。注意: 启用此项将会降低页面读取速度!");
define('_MD_AM_NUMINITSRCHRSLTS', "搜索结果数: (指 '浅搜索')");
define('_MD_AM_NUMINITSRCHRSLTSDSC', "'浅搜索' 可更快地显示在每个模块的搜索结果.");
define('_MD_AM_NUMMDLSRCHRESULTS', "每页显示的搜索结果:");
define('_MD_AM_NUMMDLSRCHRESULTSDSC', "此项将统计出下翻至特定模块的搜索结果，其每页的点击数。");
define('_MD_AM_ADMIN_DTHEME', '后台主题');
define('_MD_AM_ADMIN_DTHEME_DESC', '');
define('_MD_AM_CUSTOMRED', '要使用Ajax重定位方式吗?');
define('_MD_AM_CUSTOMREDDSC', '');
define('_MD_AM_DTHEMEDSC','选择站点主题');

// Added in 1.2

// HTML Purifier preferences
define("_MD_AM_PURIFIER","HTMLPurifier设定");
define("_MD_AM_PURIFIER_ENABLE","启用 HTML Purifier");
define("_MD_AM_PURIFIER_ENABLEDSC","选择“是”启用HTML Purifier过滤器, 不启用则可能会在启用HTML代码的情况下导致网站遭攻击");
//HTML section
define("_MD_AM_PURIFIER_HTML_TIDYLEVEL","HTML整理等级");
define("_MD_AM_PURIFIER_HTML_TIDYLEVELDSC","一般等级的整理模块将会被启用<br /><br />
None = No extra tidying should be done,<br />轻度 = 仅禁用特定的元素，或是不支持的文件类型<br />
中度 = 最常用的设置<br />重度 = 常规之外的全部不良元素及属性");
define("_MD_AM_PURIFIER_NONE","无");
define("_MD_AM_PURIFIER_LIGHT","轻度");
define("_MD_AM_PURIFIER_MEDIUM","中度(推荐)");
define("_MD_AM_PURIFIER_HEAVY","重度");
define("_MD_AM_PURIFIER_HTML_DEFID","HTML定义ID");
define("_MD_AM_PURIFIER_HTML_DEFIDDSC","为过滤设定设置默认的ID号(用默认的即可, 除非你要创建自定义的设置，而且你知道你在干什么");
define("_MD_AM_PURIFIER_HTML_DEFREV","HTML定义版本号");
define("_MD_AM_PURIFIER_HTML_DEFREVDSC","比如: 版本3就比版本2更新. 因此, 当有新版本时，系统缓存将会自动清除旧版本.<br />此选项默认即可。除非你知道你在干什么，或是直接编辑过滤器");
define("_MD_AM_PURIFIER_HTML_DOCTYPE","HTML文件类型");
define("_MD_AM_PURIFIER_HTML_DOCTYPEDSC","需过滤的文件类型. 严格来说，这并不是真正的文件类型(因为还未定义相关的文件类型), 但为方便起见，我们仍使用这一名称. 如果不留空，则会覆盖原先的旧代码，如 XHTML 或 HTML (严格).");
define("_MD_AM_PURIFIER_HTML_ALLOWELE","允许的标签");
define("_MD_AM_PURIFIER_HTML_ALLOWELEDSC","允许发布的HTML标签。在此输入的标签代码将不会被过滤。应当仅允许较为安全的标签代码。");
define("_MD_AM_PURIFIER_HTML_ALLOWATTR","允许的标签属性");
define("_MD_AM_PURIFIER_HTML_ALLOWATTRDSC","允许被使用的HTML标签属性。在此输入的标签属性将不会被过滤。应当仅允许较为安全的标签属性。<br /><br />按照如下格式定义标签属性:<br />标签.属性 (比如: div.class) 则允许使用div标签定义类属性. 也可使用大类定义: 如*.class 将会允许在任何属性下使用此类标签.");
define("_MD_AM_PURIFIER_HTML_FORBIDELE","禁止的标签");
define("_MD_AM_PURIFIER_HTML_FORBIDELEDSC","此项是 HTML.允许使用标签的逆向使用, 并会覆盖原有的属性设置.");    
define("_MD_AM_PURIFIER_HTML_FORBIDATTR","禁止的属性");
define("_MD_AM_PURIFIER_HTML_FORBIDATTRDSC"," 此项类似 HTML 允许属性, 为以后能兼容XML, 将使用不同的语法.<br />使用tag@attr.来代替tag.attr. 如不允许使用在标签中使用href属性, 可设置为 a@href.<br />也通过 attr 或 *@attr来禁止使用全局属性 (两者皆可; 后者可在HTML允许属性下使用).<br /><br />警告: 此指令作为对HTML禁止标签的补充, accordingly, 请在使用这些指令前清楚要做些什么.");
define("_MD_AM_PURIFIER_HTML_MAXIMGLENGTH","最大图片长度");
define("_MD_AM_PURIFIER_HTML_MAXIMGLENGTHDSC","此选项控制 img 标签的最大宽度及高度像素值。可防止图像超载攻击，设置为0则可能会面临很大风险。");
define("_MD_AM_PURIFIER_HTML_SAFEEMBED","启用安全嵌入");
define("_MD_AM_PURIFIER_HTML_SAFEEMBEDDSC","是否在文档内嵌入标签，指定额外安全特征代码以防止脚本的运行。这有点类似MySpace的嵌入标签。嵌入即是能使网站停止验证的元素。你可能很想启用这项HTML安全对象，但这还处在试验阶段.");
define("_MD_AM_PURIFIER_HTML_SAFEOBJECT","启用安全对象");
define("_MD_AM_PURIFIER_HTML_SAFEOBJECTDSC","是否在文档内嵌入标签，指定额外安全特征代码以防止脚本的运行。这有点类似MySpace的嵌入标签。你或许还希望为IE浏览器的最大互用性启用HTML安全嵌入，尽管内嵌标签会导致网站停止验证。注意：这项功能还处在试验阶段.");
define("_MD_AM_PURIFIER_HTML_ATTRNAMEUSECDATA","放宽DTD名称属性解析");
define("_MD_AM_PURIFIER_HTML_ATTRNAMEUSECDATADSC","W3C特别的DTD定义了名称属性需是CDATA，而不是ID，这是由于DTD的限制。在某些文档中，这类的放宽是需要的，以查验重名，或是定义名称中是否包含非法ID。例如：以数字打头的名。将此选项设置为“是”则放宽解析规则。");
// URI Section
define("_MD_AM_PURIFIER_URI_DEFID","URI 定义的ID");
define("_MD_AM_PURIFIER_URI_DEFIDDSC","为自建的URI定义作统一的识别。如果需要添加自定义URI过滤器，则需设定值。注意：最好别做修改，除非你知道你在做什么。)");
define("_MD_AM_PURIFIER_URI_DEFREV","URI 定义版本号");
define("_MD_AM_PURIFIER_URI_DEFREVDSC","例子: 版本3 就比版本2更新。因此，当版本号增加时，系统缓存会自动清除掉旧版本。<br />最好别做修改，除非你知道你在干嘛，或是直接编辑需清除的文件。");
define("_MD_AM_PURIFIER_URI_DISABLE","在用户帖子中禁用所有的URI");
define("_MD_AM_PURIFIER_URI_DISABLEDSC","禁止URI可防止用户在发贴中嵌入链接，通常并不建议这样做，除非是做测试用。<br />默认为'否'");
define("_MD_AM_PURIFIER_URI_BLACKLIST","URI 黑名单");
define("_MD_AM_PURIFIER_URI_BLACKLISTDSC","输入在用户发帖中需要过滤的域名");
define("_MD_AM_PURIFIER_URI_ALLOWSCHEME","允许的URI");
define("_MD_AM_PURIFIER_URI_ALLOWSCHEMEDSC","白名单则定义允许的URI。这刻防止使用伪计划如javascript或mocha带来的XSS攻击。<br />允许值 (http, https, ftp, mailto, nntp, news)");
define("_MD_AM_PURIFIER_URI_HOST","URI 主域名");
define("_MD_AM_PURIFIER_URI_HOSTDSC","输入URI 主域名. 留空则不启用!");
define("_MD_AM_PURIFIER_URI_BASE","URI 基本域名");
define("_MD_AM_PURIFIER_URI_BASEDSC","输入URI 基本域名. 留空则不启用!");
define("_MD_AM_PURIFIER_URI_DISABLEEXT","禁用外链接");
define("_MD_AM_PURIFIER_URI_DISABLEEXTDSC","禁止转到外部网站的链接。这可有效地防止广告及页面捕抓。但这也需要付出代价，除你网站域名外的链接及图像都不能被使用。<br />非链接性的URI也会被禁用。如果要启用链接至子域名或使用绝对URI，可启用网站的URI主域名。");
define("_MD_AM_PURIFIER_URI_DISABLEEXTRES","禁用外部资源");
define("_MD_AM_PURIFIER_URI_DISABLEEXTRESDSC","禁用嵌入的外部资源, 防止用户嵌入如外部网站的图片。这可防止被追踪(对查看邮件来说更安全)、带宽掠夺、跨站点请求、等攻击行为，但也会令终端用户损失部分功能(不能从Flickr等网站直接链图片了)。如果没有内容审查团队的话，可以考虑使用此功能。 ");
define("_MD_AM_PURIFIER_URI_DISABLERES","禁用资源");
define("_MD_AM_PURIFIER_URI_DISABLERESDSC","禁用嵌入资源, 特别是图片。但仍可链接它们。查看“URI 禁用外部资源”，就知道这是个好办法。");
define("_MD_AM_PURIFIER_URI_MAKEABS","URI 绝对");
define("_MD_AM_PURIFIER_URI_MAKEABSDSC","将所有URI转换成绝对格式。当HTML被特别的路径过滤时，这将十分有用。但仍可根据上下文意被看到。(设置为备用基本URI是不可的).<br /><br />要启用功能，则URI Base必须先启用");
// Filter Section
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEESC","Escape Dangerous Characters in StyleBlocks");
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEESCDSC","Whether or not to escape the dangerous characters <, > and &  as \3C, \3E and \26, respectively. This can be safely set to false if the contents of StyleBlocks will be placed in an external stylesheet, where there is no risk of it being interpreted as HTML.");
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLKSCOPE","Enter StyleBlocks Scope");
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLKSCOPEDSC","If you would like users to be able to define external stylesheets, but only allow them to specify CSS declarations for a specific node and prevent them from fiddling with other elements, use this directive.<br />It accepts any valid CSS selector, and will prepend this to any CSS declaration extracted from the document.<br /><br />For example, if this directive is set to #user-content and a user uses the selector a:hover, the final selector will be #user-content a:hover.<br /><br />The comma shorthand may be used; consider the above example, with #user-content, #user-content2, the final selector will be #user-content a:hover, #user-content2 a:hover.");
define("_MD_AM_PURIFIER_FILTER_ENABLEYOUTUBE","允许嵌入YouTube视频");
define("_MD_AM_PURIFIER_FILTER_ENABLEYOUTUBEDSC","此选项将允许在HTML过滤器中嵌入YouTube视频。点击 <a href='http://htmlpurifier.org/docs/enduser-youtube.html'>这里</a> 获取更多关于嵌入视频的信息.");
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLK","要抽取样式区块吗？");
define("_MD_AM_PURIFIER_FILTER_EXTRACTSTYLEBLKDSC","需安装CSSTidy插件).<br /><br />此选项控制样式区块的抽取过滤器，可从样式区块中移除HTML标记，需配合CSSTidy插件使用，放置到文义变量中，为了之后的使用，通常会链接到外部的样式表中，或是文档区块头部的样式设置。<br /><br />警告：可能会导致在使用样式表时，发生图像加载崩溃的意外。也很难使用计数器; 限制样式表长度范围并不是那么的简单(使用关联长度using relative lengths with many nesting levels allows for large values to be attained without actually specifying them in the stylesheet), and the flexible nature of selectors makes it difficult to selectively disable lengths on image tags (HTML Purifier, however, does disable CSS width and height in inline styling). There are probably two effective counter measures: an explicit width and height set to auto in all images in your document (unlikely) or the disabling of width and height (somewhat reasonable). Whether or not these measures should be used is left to the reader.");
// Core Section
define("_MD_AM_PURIFIER_CORE_ESCINVALIDTAGS","跳过无效的标签");
define("_MD_AM_PURIFIER_CORE_ESCINVALIDTAGSDSC","如启用此项，无效的标签将会被视作纯文本的输入，否则将会被静默处理。");
define("_MD_AM_PURIFIER_CORE_ESCNONASCIICHARS","跳过非ASCII字符");
define("_MD_AM_PURIFIER_CORE_ESCNONASCIICHARSDSC","此选项可以弥补 %Core 的不足。可在自然编码前将输入的所有非ASCII字符转换为十进制数字。这意味着非UTF-8编码的字符也可被识别，如编码成Big5。通常ASCII是常用的编码，至于其它情况，请使用UTF-8编码。");
define("_MD_AM_PURIFIER_CORE_HIDDENELE","启用HTML隐藏元素");
define("_MD_AM_PURIFIER_CORE_HIDDENELEDSC","此选项控制一组数值，它们不被HTML定义所允许的，所有的内容也将会被移除。例如，文章内容的脚本标签通常就不会再文档中显示，所以，如果脚本标签被移除，内容也会被移除。这有别于 a b 标签，a b标签可定义某些特性的更改，但又不隐藏内容。");
define("_MD_AM_PURIFIER_CORE_COLORKEYS","关键词着色");
define("_MD_AM_PURIFIER_CORE_COLORKEYSDSC","设置一组名称，用6位十六进制所对应的色彩，可对关键字进行着色识别。");
define("_MD_AM_PURIFIER_CORE_REMINVIMG","移除无效图片");
define("_MD_AM_PURIFIER_CORE_REMINVIMGDSC","此项可对img标签进行URI的预检查，当属性被验证为无效或权限不足时，则会将元素从文档中移除。默认为“是”。");
// AutoFormat Section
define("_MD_AM_PURIFIER_AUTO_AUTOPARA","启用自动段落格式");
define("_MD_AM_PURIFIER_AUTO_AUTOPARADSC","此选项控制自动分段，如果有两行空行，则自动识别并转换为段落。<br /> 自动分段:<br /><br />* 在行内元素或文字中应用,<br />* 在双行新行的行内元素或文字中允许分段标签,<br />* 在双行新行中允许分段标签.<br /></br>p 标签必须被允许，此选项才有效。br 标签因有时会不能正确断行。<br />为预防在输入时的不正确断行，使用双行新行来指明分段。(空格通常没有任何含义，除非是在如pre之类的标签中。) 为防止行内上下文分段影响到元素，可用div标签来断行(这与外行分段有些许不同。)");
define("_MD_AM_PURIFIER_AUTO_DISPLINKURI","启用链接显示");
define("_MD_AM_PURIFIER_AUTO_DISPLINKURIDSC","此选项控制使用<a>标签的链接文字的显示效果。如：<a href=\"http://example.com\">例子</a>链接(http://example.com).");
define("_MD_AM_PURIFIER_AUTO_LINKIFY","自用自动识别链接");
define("_MD_AM_PURIFIER_AUTO_LINKIFYDSC","此选项控制链接的自动识别，http、ftp及https，必须启用href标签属性。");
define("_MD_AM_PURIFIER_AUTO_PURILINKIFY","启用内链接识别Enable Purifier Internal Linkify");
define("_MD_AM_PURIFIER_AUTO_PURILINKIFYDSC","使用语法 %Namespace 识别内部链接。必须启用href标签属性(没别的需要就留默认的设置好了))");
define("_MD_AM_PURIFIER_AUTO_CUSTOM","允许自定义的自动格式");
define("_MD_AM_PURIFIER_AUTO_CUSTOMDSC","此选项控制添加自定义的自动格式注入器。定义需注入的名称数组(类名称减去前缀)或是专注于审核。注入器类必须存在。请查阅<a href='www.htmlpurifier.org'>HTML Purifier 主页</a> 获取更多信息.");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTY","移除空的元素");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTYDSC"," 如启用，HTML Purifier将会移除文档信息中不包含元素的标签。如:<br /><br />
 * 标签不包含任何属性及内容，都不算空元素 (移除 \<a\>\</a\> 但不移除 \<br /\>), 以及<br />
 * 标签不包含内容, 但<br />除外
   o colgroup 元素, 或<br />
   o 元素中有 id 或是名称属性, 当这些属性允许用到这些元素时。<br /><br />
在使用此功能时，请特别小心，有些看起来是空的元素可能包含有非常有用的信息，它们对文档的构成起着重要的作用。此选项在对于使用自动生成的HTML特别有用，但请不要在常规用户的HTML中使用。<br /><br />
仅包含空格的元素会被当作为空元素。连续的空格不被当作是空格。请查阅“移除空的元素”作另外的参考。");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSP","移除连续空格");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSPDSC","如启用此项，HTML Purifier会将任何连续空格及一般空格当作空元素，如果启用了“移除空的元素”功能，他们也会被删除。<br /><br />
查阅“移除空Nbsp覆盖”获取不被应用的元素的列表.");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSPEXCEPT","移除空Nbsp覆盖");
define("_MD_AM_PURIFIER_AUTO_REMOVEEMPTYNBSPEXCEPTDSC","如启用，则仅包含连续空格的HTML元素不会被移除.");
// Attribute Section
define("_MD_AM_PURIFIER_ATTR_ALLOWFRAMETARGET","许用的框架目标");
define("_MD_AM_PURIFIER_ATTR_ALLOWFRAMETARGETDSC","为许用的框架目标链接设置表格. Some commonly used link targets include _blank, _self, _parent and _top. Values should be lowercase, as validation will be done in a case-sensitive manner despite W3C's recommendation. XHTML 1.0 Strict does not permit the target attribute so this directive will have no effect in that doctype. XHTML 1.1 does not enable the Target module by default, you will have to manually enable it (see the module documentation for more details.)");
define("_MD_AM_PURIFIER_ATTR_ALLOWREL","允许文档间的关联");
define("_MD_AM_PURIFIER_ATTR_ALLOWRELDSC","创建使用 rel 特性的文档关联。常用值可能不会被follow或显示.<br /><br />Default = external, nofollow, external nofollow & lightbox.");
define("_MD_AM_PURIFIER_ATTR_ALLOWCLASSES","所允许的类值");
define("_MD_AM_PURIFIER_ATTR_ALLOWCLASSESDSC","设置类属性中类值所允许的表。如留空则允许任何值.");
define("_MD_AM_PURIFIER_ATTR_FORBIDDENCLASSES","被禁止的类值");
define("_MD_AM_PURIFIER_ATTR_FORBIDDENCLASSESDSC","设置类属性中被禁止的类值列表. 如留空则允许任何类属性.");
define("_MD_AM_PURIFIER_ATTR_DEFINVIMG","默认的无效图像");
define("_MD_AM_PURIFIER_ATTR_DEFINVIMGDSC","This is the default image an img tag will be pointed to if it does not have a valid src attribute. In future versions, we may allow the image tag to be removed completely, but due to design issues, this is not possible right now.");
define("_MD_AM_PURIFIER_ATTR_DEFINVIMGALT","默认的无效图像标签");
define("_MD_AM_PURIFIER_ATTR_DEFINVIMGALTDSC","This is the content of the alt tag of an invalid image if the user had not previously specified an alt attribute. It has no effect when the image is valid but there was no alt attribute present.");
define("_MD_AM_PURIFIER_ATTR_DEFIMGALT","默认的图像Alt标签");
define("_MD_AM_PURIFIER_ATTR_DEFIMGALTDSC","This is the content of the alt tag of an image if the user had not previously specified an alt attribute.<br />This applies to all images without a valid alt attribute, as opposed to Default Invalid Alt Tag, which only applies to invalid images, and overrides in the case of an invalid image.<br />Default behavior with null is to use the basename of the src tag for the alt.");
define("_MD_AM_PURIFIER_ATTR_CLASSUSECDATA","Use NMTokens or CDATA specifications");
define("_MD_AM_PURIFIER_ATTR_CLASSUSECDATADSC","If null, class will auto-detect the doctype and, if matching XHTML 1.1 or XHTML 2.0, will use the restrictive NMTOKENS specification of class. Otherwise, it will use a relaxed CDATA definition. If true, the relaxed CDATA definition is forced; if false, the NMTOKENS definition is forced. To get behavior of HTML Purifier prior to 4.0.0, set this directive to false. Some rational behind the auto-detection: in previous versions of HTML Purifier, it was assumed that the form of class was NMTOKENS, as specified by the XHTML Modularization (representing XHTML 1.1 and XHTML 2.0). The DTDs for HTML 4.01 and XHTML 1.0, however specify class as CDATA. HTML 5 effectively defines it as CDATA, but with the additional constraint that each name should be unique (this is not explicitly outlined in previous specifications).");
define("_MD_AM_PURIFIER_ATTR_ENABLEID","允许使用ID属性吗?");
define("_MD_AM_PURIFIER_ATTR_ENABLEIDDSC","允许为ID特性使用HTML。默认设置下是不允许的。我们强烈地建议你考虑黑名单ID前缀。");
define("_MD_AM_PURIFIER_ATTR_IDPREFIX","设置ID属性前缀");
define("_MD_AM_PURIFIER_ATTR_IDPREFIXDSC","ID前缀的字符串。如果 If you have no idea what IDs your pages may use, you may opt to simply add a prefix to all user-submitted ID attributes so that they are still usable, but will not conflict with core page IDs. Example: setting the directive to 'user_' will result in a user submitted 'foo' to become 'user_foo' Be sure to set 'Allow ID Attribute' to yes before using this.");
define("_MD_AM_PURIFIER_ATTR_IDPREFIXLOCAL","允许自定义自动格式吗?");
define("_MD_AM_PURIFIER_ATTR_IDPREFIXLOCALDSC","ID的临时前缀与ID特性前缀连接在一起. 如果需要为网页上的用户文章内容做多种设置，则需要分割重复的前缀。这样，用户在同一页面中提交的文章内容就不会发生冲突. 理想的值则是根据文章内容使用统一的标识 (如：数据行的id). 请最后添加分隔符(如下划线)。警告: 此选项只有在特性ID前缀设为非空值时才有效!");
define("_MD_AM_PURIFIER_ATTR_IDBLACKLIST","特性ID黑名单");
define("_MD_AM_PURIFIER_ATTR_IDBLACKLISTDSC","不能在此文档中使用ID数组.");
// CSS Section
define("_MD_AM_PURIFIER_CSS_ALLOWIMPORTANT","允许在CSS样式表中使用!important");
define("_MD_AM_PURIFIER_CSS_ALLOWIMPORTANTDSC","此参数将检测在用户CSS中是否可使用 !important 层叠修改器。如设为否，!important将会被跳过.");
define("_MD_AM_PURIFIER_CSS_ALLOWTRICKY","允许Tricky CSS样式");
define("_MD_AM_PURIFIER_CSS_ALLOWTRICKYDSC","此参数设定是否允许 \"tricky\" CSS 属性或值。Tricky CSS 属性或值可用于页面布局的大幅修改，或是用于避免风险的练习。例如：显示：无，这便是针对此选项的一个技巧。");
define("_MD_AM_PURIFIER_CSS_ALLOWPROP","允许CSS属性");
define("_MD_AM_PURIFIER_CSS_ALLOWPROPDSC","如果HTML Purifier的样式属性设置不符合你的要求, 则可以使用自定义的标签列表。注意这样做可能会带来负面的效果: 它是从HTML Purifier中脱出，所以不受HTML Purifier控制.<br /><br />警告: 如有其它设置有冲突，则以此设置为准.");
define("_MD_AM_PURIFIER_CSS_DEFREV","CSS 定义版本");
define("_MD_AM_PURIFIER_CSS_DEFREVDSC","为你的自定义设定版本号，查看HTML定义版本获取更多信息.");
define("_MD_AM_PURIFIER_CSS_MAXIMGLEN","CSS最大图像长度");
define("_MD_AM_PURIFIER_CSS_MAXIMGLENDSC","此参数设置img标签的最大长度，同时作用于长度及宽度属性。仅能测量绝对值(in, pt, pc, mm, cm) 以及像素值(px)。这可防止图像崩溃攻击，如设置为null则有风险。此选项有点类似HTML最大图像长度，两者都可被编辑，不过两者都有不同的输入格式(CSS的最大值需是数字加单位)");
define("_MD_AM_PURIFIER_CSS_PROPRIETARY","允许CSS安全专有值");
define("_MD_AM_PURIFIER_CSS_PROPRIETARYDSC","无论是否允许安全，都是CSS的专用值.");
// purifier config options
define("_MD_AM_PURIFIER_401T","HTML 4.01 Transitional");
define("_MD_AM_PURIFIER_401S","HTML 4.01 Strict");
define("_MD_AM_PURIFIER_X10T","XHTML 1.0 Transitional");
define("_MD_AM_PURIFIER_X10S","XHTML 1.0 Strict");
define("_MD_AM_PURIFIER_X11","XHTML 1.1");
define("_MD_AM_PURIFIER_WEGAME","WEGAME 视频");
define("_MD_AM_PURIFIER_VIMEO","Vimeo视频");
define("_MD_AM_PURIFIER_LOCALMOVIE","本地视频");
define("_MD_AM_PURIFIER_GOOGLEVID","Google Video");
define("_MD_AM_PURIFIER_LIVELEAK","LiveLeak视频");

define("_MD_AM_UNABLECSSTIDY", "未发现CSSTidy插件, 请确认CSSTidy已放置于插件文件夹下.");

// Autotasks
if (!defined('_MD_AM_AUTOTASKS')) {define('_MD_AM_AUTOTASKS', '自动任务');}
define("_MD_AM_AUTOTASKS_SYSTEM", "程序系统");
define("_MD_AM_AUTOTASKS_HELPER", "帮助程序");
define("_MD_AM_AUTOTASKS_HELPER_PATH", "帮助程序路径");

define("_MD_AM_AUTOTASKS_SYSTEMDSC", "哪个任务系统需用于执行任务?");
define("_MD_AM_AUTOTASKS_HELPERDSC", "对非内部的应用程序系统，请指定帮助程序。只有一个程序可被使用，请小心选择。");
define("_MD_AM_AUTOTASKS_HELPER_PATHDSC", "如果帮助程序不在系统的默认路径，则需手工指定.");
define("_MD_AM_AUTOTASKS_USER", "系统用户");
define("_MD_AM_AUTOTASKS_USERDSC", "行驶任务的系统用户.");

//source editedit
define("_MD_AM_SRCEDITOR_DEFAULT","默认的源代码编辑器");
define("_MD_AM_SRCEDITOR_DEFAULT_DESC","选择编辑源代码的默认编辑器.");

// added in 1.2.1
define("_MD_AM_SMTPSECURE","SMTP加密方式");
define("_MD_AM_SMTPSECUREDESC","SMTP授权模式(默认为SSL)");
define("_MD_AM_SMTPAUTHPORT","SMTP端口");
define("_MD_AM_SMTPAUTHPORTDESC","SMTP邮件服务器端口(默认为465)");

// added in 1.3
define("_MD_AM_PURIFIER_OUTPUT_FLASHCOMPAT","启用IE Flash 兼容");
define("_MD_AM_PURIFIER_OUTPUT_FLASHCOMPATDSC","如设置为是，HTML Purifier将会对所有的对象代码生成IE浏览器兼容代码。如要嵌入HTML.SafeObject，那这是十分推荐的.");
define("_MD_AM_PURIFIER_HTML_FLASHFULLSCRN","允许Flash对象使用全屏");
define("_MD_AM_PURIFIER_HTML_FLASHFULLSCRNDSC","如设置为是，则HTML Purifier会在使用HTML.SafeObject时，对内嵌的flash动画允许使用全屏 of 'allowFullScreen' in embedded flash content when using HTML.SafeObject.");
define("_MD_AM_PURIFIER_CORE_NORMALNEWLINES","换行符的正常化");
define("_MD_AM_PURIFIER_CORE_NORMALNEWLINESDSC","是否正常化系统默认的换行符。如果失败, HTML Purifier将会阻止换行符文件的最大化.");
define('_MD_AM_AUTHENTICATION_DSC', '管理访问的安全设置. 将会应用于如何处理用户帐户.');
define('_MD_AM_AUTOTASKS_PREF_DSC', '自动任务系统首选项.');
define('_MD_AM_CAPTCHA_DSC', '设置站点所使用验证码.');
define('_MD_AM_GENERAL_DSC', '主设置页面用于设置站点的基本信息.');
define('_MD_AM_PURIFIER_DSC', 'HTMLPurifier用于保护站点，免遭常见的攻击.');
define('_MD_AM_MAILER_DSC', '设置站点该如何处理邮件.');
define('_MD_AM_METAFOOTER_DSC', '管理站点的meta信息及底部footer以便网络爬虫抓取。');
define('_MD_AM_MULTILANGUAGE_DSC', '管理站点的多语言设置。如启用，则需设置需使用那些语言，如何切换等。');
define('_MD_AM_PERSON_DSC', '自定义logo及其它等对网站进行个性化设置.');
define('_MD_AM_PLUGINS_DSC', '选择需应用于整个网站的插件.');
define('_MD_AM_SEARCH_DSC', '管理用户能使用的搜索功能.');
define('_MD_AM_USERSETTINGS_DSC', '管理用户的注册。设置用户名长度、格式、密码强度等。');
define('_MD_AM_CENSOR_DSC', '管理站点中不允许使用的语言');
define("_MD_AM_PURIFIER_FILTER_ALLOWCUSTOM","允许自定义筛选器");
define("_MD_AM_PURIFIER_FILTER_ALLOWCUSTOMDSC","允许自定义筛选器?<br /><br />如果启用，则可使用自定义的筛选器。<br />在此路径下'libraries/htmlpurifier/standalone/HTMLPurifier/Filter'");

?>