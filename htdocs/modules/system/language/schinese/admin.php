<?php
// $Id: blauer-fisch $
//%%%%%%	File Name  admin.php 	%%%%%
//define('_MD_AM_CONFIG','系统设置');
define('_INVALID_ADMIN_FUNCTION', '无效管理命令');

// Admin Module Names
define('_MD_AM_ADGS', '群组');
define('_MD_AM_BANS', '广告条');
define('_MD_AM_BKAD', '区块');
define('_MD_AM_MDAD', '模块管理');
define('_MD_AM_SMLS', '表情');
define('_MD_AM_RANK', '用户等级');
define('_MD_AM_USER', '编辑用户');
define('_MD_AM_FINDUSER', '查找用户');
define('_MD_AM_PREF', '首选项');
define('_MD_AM_VRSN', '版本检查');
define('_MD_AM_MLUS', '通知用户');
define('_MD_AM_IMAGES', '图片管理');
define('_MD_AM_AVATARS', '头像');
define('_MD_AM_TPLSETS', '模板');
define('_MD_AM_COMMENTS', '留言');
define('_MD_AM_BKPOSAD', '区块位置');
define('_MD_AM_PAGES', '符号链接管理');
define('_MD_AM_CUSTOMTAGS', '自定义标签');

// Group permission phrases
define('_MD_AM_PERMADDNG', '不能给予群组 %s 添加 %s 的权限');
define('_MD_AM_PERMADDOK', '添加 %s 权限 %s 给群组 %s');
define('_MD_AM_PERMRESETNG', '不能为模块 %s 重设群组权限');
define('_MD_AM_PERMADDNGP', '所有上级项目都要选');

// added in 1.2
if (!defined('_MD_AM_AUTOTASKS')) {
    define('_MD_AM_AUTOTASKS', '自动任务');
}
define('_MD_AM_ADSENSES', 'Adsenses广告');
define('_MD_AM_RATINGS', '等级Ratings');
define('_MD_AM_MIMETYPES', 'Mime类型');

// added in 1.3
define("_MD_AM_GROUPS_ADVERTISING", "广告");
define("_MD_AM_GROUPS_CONTENT", "内容");
define("_MD_AM_GROUPS_LAYOUT", "布局");
define("_MD_AM_GROUPS_MEDIA", "媒体");
define("_MD_AM_GROUPS_SITECONFIGURATION", "站点配置");
define("_MD_AM_GROUPS_SYSTEMTOOLS", "系统工具");
define("_MD_AM_GROUPS_USERSANDGROUPS", "用户与群组");
define('_MD_AM_ADSENSES_DSC', 'Adsenses广告是可用于网站任何地方的标签');
define('_MD_AM_AUTOTASKS_DSC', '自动任务允许你为操作设置时间表，让系统自动执行');
define('_MD_AM_AVATARS_DSC', '头像管理');
define('_MD_AM_BANS_DSC', '广告管理');
define('_MD_AM_BKPOSAD_DSC', '管理、创建网站主题的区块和位置');
define('_MD_AM_BKAD_DSC', '管理、创建整个网站的区块');
define('_MD_AM_COMMENTS_DSC', '管理网站内用户的留言');
define('_MD_AM_CUSTOMTAGS_DSC', '自定义标签即可定义并可用户网站任何地方的标签');
define('_MD_AM_USER_DSC', '创建、修改、删除已注册用户');
define('_MD_AM_FINDUSER_DSC', '通过选择器查找已注册用户');
define('_MD_AM_ADGS_DSC', '管理权限设置、群组成员、可见性、群组成员的访问权限');
define('_MD_AM_IMAGES_DSC', '创建图片的组别并赋予各组不同的权限，如剪辑、尺寸调整、上传等');
define('_MD_AM_MLUS_DSC', '给组内全部成员发送信息，或是通过选择器选择特定的用户');
define('_MD_AM_MIMETYPES_DSC', '管理允许上传文件的类型，即后缀名');
define('_MD_AM_MDAD_DSC', '管理模块菜单的权重、状态、名称、模块更新等');
define('_MD_AM_RATINGS_DSC', '通过这一工具，可以为模块添加新的排名方法，控制这一部分的结果!');
define('_MD_AM_SMLS_DSC', '管理现有的表情图片，为每个图片设置关联的代码');
define('_MD_AM_PAGES_DSC', '符号管理允许为页面创建唯一的链接，可用在页面区块定义，也可用在模块内容的链接中');
define('_MD_AM_TPLSETS_DSC', '模板是一套html/css文件，用来渲染模块的布局');
define('_MD_AM_RANK_DSC', '用户等级，可谓不同用户设置不同的等级');
define('_MD_AM_VRSN_DSC', '检查系统是否需要更新');
define('_MD_AM_PREF_DSC', "ImpressCMS 网站参考");
