<?php
// $Id: blauer-fisch $
//%%%%%%	Admin Module Name  Blocks 	%%%%%
if (!defined('_AM_DBUPDATED')) {if (!defined('_AM_DBUPDATED')) {define("_AM_DBUPDATED","数据库更新成功!");}}

//%%%%%%	blocks.php 	%%%%%
define("_AM_BADMIN","区块管理");

# Adding dynamic block area/position system - TheRpLima - 2007-10-21
define('_AM_BPADMIN',"区块位置管理");

define("_AM_ADDBLOCK","添加新区块");
define("_AM_LISTBLOCK","列出所有区块");
define("_AM_SIDE","Side");
define("_AM_BLKDESC","区块描述");
define("_AM_TITLE","标题");
define("_AM_WEIGHT","权重");
define("_AM_ACTION","动作");
define("_AM_BLKTYPE","区块类型");
define("_AM_LEFT","左");
define("_AM_RIGHT","右");
define("_AM_CENTER","中间");
define("_AM_VISIBLE","可见性");
define("_AM_POSCONTT","附加内容位置");
define("_AM_ABOVEORG","位于原内容上方");
define("_AM_AFTERORG","位于原内容下方");
define("_AM_EDIT","编辑");
define("_AM_DELETE","删除");
define("_AM_SBLEFT","边栏区块-左");
define("_AM_SBRIGHT","边栏区块-右");
define("_AM_CBLEFT","中央区块-左");
define("_AM_CBRIGHT","中央区块-右");
define("_AM_CBCENTER","中央区块-中间");
define("_AM_CBBOTTOMLEFT","中央区块-底部左方");
define("_AM_CBBOTTOMRIGHT","中央区块-底部右方");
define("_AM_CBBOTTOM","中央区块-底部");
define("_AM_CONTENT","内容");
define("_AM_OPTIONS","选项");
define("_AM_CTYPE","内容类型");
define("_AM_HTML","HTML代码");
define("_AM_PHP","PHP脚本");
define("_AM_AFWSMILE","自动格式(表情符可用)");
define("_AM_AFNOSMILE","自动格式(表情符不可用)");
define("_AM_SUBMIT","提交");
define("_AM_CUSTOMHTML","自定义区块(HTML)");
define("_AM_CUSTOMPHP","自定义区块(PHP)");
define("_AM_CUSTOMSMILE","自定义区块(自动格式+表情符)");
define("_AM_CUSTOMNOSMILE","自定义区块(自动格式)");
define("_AM_DISPRIGHT","仅显示右边的区块");
define("_AM_SAVECHANGES","保存更改");
define("_AM_EDITBLOCK","编辑区块");
define("_AM_SYSTEMCANT","不能删除系统的区块!");
define("_AM_MODULECANT","这一区块不能直接被删除，如要禁用这一区块，可不激活此模块");
define("_AM_RUSUREDEL","确定要删除区块'%s'吗?");
define("_AM_NAME","名称");
define("_AM_USEFULTAGS","常用标签:");
define("_AM_BLOCKTAG1","%s 将显示为will print %s");
define('_AM_SVISIBLEIN', '显示在%s的区块');
define('_AM_TOPPAGE', '页面顶部');
define('_AM_VISIBLEIN', '可见于Visible in');
define('_AM_ALLPAGES', '全部页面');
define('_AM_TOPONLY', '仅页面顶部');
define('_AM_ADVANCED', '高级就设置');
define('_AM_BCACHETIME', '缓存时间Cache lifetime');
define('_AM_BALIAS', '别名');
define('_AM_CLONE', '复制');  // clone a block
define('_AM_CLONEBLK', '区块已复制'); // cloned block
define('_AM_CLONEBLOCK', '新建已复制的区块');
define('_AM_NOTSELNG', "'%s' 未被选中!"); // error message
define('_AM_EDITTPL', '编辑模板');
define('_AM_MODULE', '模块');
define('_AM_GROUP', '组');
define('_AM_UNASSIGNED', '未指定');

define('_AM_CHANGESTS', '更改区块的可见性');

######################## Added in 1.2 ###################################
define('_AM_BLOCKS_PERMGROUPS','可见此区块的群组');

/**
 * The next Language definitions are included since 2.0 of blockadmin module, because now is based on IPF.
 * TODO: Add the rest of the fields, are added only the ones which are shown.
 */
// Texts

// Actions
define("_AM_SYSTEM_BLOCKSADMIN_CREATE", "新建区块");
define("_AM_SYSTEM_BLOCKSADMIN_EDIT", "编辑区块");
define("_AM_SYSTEM_BLOCKSADMIN_MODIFIED", "区块已修改!");
define("_AM_SYSTEM_BLOCKSADMIN_CREATED", "区块已创建!");

// Fields
define("_CO_SYSTEM_BLOCKSADMIN_NAME", "栏位名称");
define("_CO_SYSTEM_BLOCKSADMIN_NAME_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_TITLE", "标题");
define("_CO_SYSTEM_BLOCKSADMIN_TITLE_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_MID", "模块");
define("_CO_SYSTEM_BLOCKSADMIN_MID_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_VISIBLE", "可见性");
define("_CO_SYSTEM_BLOCKSADMIN_VISIBLE_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_CONTENT", "内容");
define("_CO_SYSTEM_BLOCKSADMIN_CONTENT_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_SIDE", "边栏");
define("_CO_SYSTEM_BLOCKSADMIN_SIDE_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_WEIGHT", "权重");
define("_CO_SYSTEM_BLOCKSADMIN_WEIGHT_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_BLOCK_TYPE", "区块类型");
define("_CO_SYSTEM_BLOCKSADMIN_BLOCK_TYPE_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_C_TYPE", "内容类型");
define("_CO_SYSTEM_BLOCKSADMIN_C_TYPE_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_OPTIONS", "选项");
define("_CO_SYSTEM_BLOCKSADMIN_OPTIONS_DSC", "");
define("_CO_SYSTEM_BLOCKSADMIN_BCACHETIME", "区块缓存时间");
define("_CO_SYSTEM_BLOCKSADMIN_BCACHETIME_DSC", "");

define("_CO_SYSTEM_BLOCKSADMIN_BLOCKRIGHTS", "查看区块权限");
define("_CO_SYSTEM_BLOCKSADMIN_BLOCKRIGHTS_DSC", "");

define("_AM_SBLEFT_ADMIN","管理区边栏区块-左");
define("_AM_SBRIGHT_ADMIN","管理区边栏区块-右");
define("_AM_CBLEFT_ADMIN","管理区中央区块-左");
define("_AM_CBRIGHT_ADMIN","管理区中央区块-右");
define("_AM_CBCENTER_ADMIN","管理区中央区块-中间");
define("_AM_CBBOTTOMLEFT_ADMIN","管理区中央区块-左下");
define("_AM_CBBOTTOMRIGHT_ADMIN","管理区中央区块-右下");
define("_AM_CBBOTTOM_ADMIN","管理区中央区块-下方");
?>