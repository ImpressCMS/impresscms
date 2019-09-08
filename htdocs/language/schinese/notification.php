<?php
// $Id: blauer-fisch $

// RMV-NOTIFY

// Text for various templates...

define('_NOT_NOTIFICATIONOPTIONS', '选项');
define('_NOT_UPDATENOW', '现在更新');
define('_NOT_UPDATEOPTIONS', '更新提示的选项');

define('_NOT_CLEAR', '清除');
define('_NOT_CHECKALL', '检查全部');
define('_NOT_MODULE', '模块');
define('_NOT_CATEGORY', '分类');
define('_NOT_ITEMID', 'ID');
define('_NOT_ITEMNAME', '名称');
define('_NOT_EVENT', '事件');
define('_NOT_EVENTS', '事件');
define('_NOT_ACTIVENOTIFICATIONS', '通知及收藏');
define('_NOT_NAMENOTAVAILABLE', '名称不存在');
// RMV-NEW : TODO: remove NAMENOTAVAILBLE above
define('_NOT_ITEMNAMENOTAVAILABLE', '项目名称不存在');
define('_NOT_ITEMTYPENOTAVAILABLE', '项目类型不存在');
define('_NOT_ITEMURLNOTAVAILABLE', '项目URL不存在');
define('_NOT_DELETINGNOTIFICATIONS', '删除通知');
define('_NOT_DELETESUCCESS', '通知已被删除');
define('_NOT_UPDATEOK', '通知的选项已更新');
define('_NOT_NOTIFICATIONMETHODIS', '通知模式');
define('_NOT_EMAIL', '电子邮件');
define('_NOT_PM', '私人信息');
define('_NOT_DISABLE', '不可用');
define('_NOT_CHANGE', '更改');

define('_NOT_NOACCESS', '权限不足，不能访问本页面');

// Text for module config options

define('_NOT_NOTIFICATION', '通知');

define('_NOT_CONFIG_ENABLED', '启用通知');
define('_NOT_CONFIG_ENABLEDDSC', '本模块允许用户针对特定事件收到通知，选择YES启用这一功能。');

define('_NOT_CONFIG_EVENTS', '启用特定的事件');
define('_NOT_CONFIG_EVENTSDSC', '为用户选择收到通知事件的类型');

define('_NOT_CONFIG_ENABLE', '启用通知');
define('_NOT_CONFIG_ENABLEDSC', '本模块允许用户针对特定事件接收相关通知。有“信息块”及“内联”两种模式供选择，两种模式可同时启用。“信息块”模式必须在模块中启用才能生效。');
define('_NOT_CONFIG_DISABLE', '不启用通知');
define('_NOT_CONFIG_ENABLEBLOCK', '只启用信息块模式');
define('_NOT_CONFIG_ENABLEINLINE', '只启用内联模式');
define('_NOT_CONFIG_ENABLEBOTH', '启用“信息块”及“内联”双模式');

// For notification about comment events

define('_NOT_COMMENT_NOTIFY', '留言已添加');
define('_NOT_COMMENT_NOTIFYCAP', '当有留言时通知我');
define('_NOT_COMMENT_NOTIFYDSC', '当有留言时通知我');
define('_NOT_COMMENT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} 自动通知: 有新的留言 {X_ITEM_TYPE}');

define('_NOT_COMMENTSUBMIT_NOTIFY', '留言已提交');
define('_NOT_COMMENTSUBMIT_NOTIFYCAP', '当有留言提交时通知我');
define('_NOT_COMMENTSUBMIT_NOTIFYDSC', '当有留言提交时通知我');
define('_NOT_COMMENTSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} 自动通知: 有新的留言提交 {X_ITEM_TYPE}');

// For notification bookmark feature
// (Not really notification, but easy to do with this module)

define('_NOT_BOOKMARK_NOTIFY', '收藏');
define('_NOT_BOOKMARK_NOTIFYCAP', '收藏这一主题');
define('_NOT_BOOKMARK_NOTIFYDSC', '跟踪这一主题但不接收任何通知');

// For user profile
// FIXME: These should be reworded a little...

define('_NOT_NOTIFYMETHOD', '通知方式: 例如你在关注一个论坛，你会希望收到有关更新的通知吗？');
define('_NOT_METHOD_EMAIL', '电子邮件 (使用我个人资料里的地址)');
define('_NOT_METHOD_PM', '私人信息');
define('_NOT_METHOD_DISABLE', '暂时不启用');

define('_NOT_NOTIFYMODE', '默认通知模式');
define('_NOT_MODE_SENDALWAYS', '当所选的有更新时通知我');
define('_NOT_MODE_SENDONCE', '只通知一遍');
define('_NOT_MODE_SENDONCEPERLOGIN', '只通知一遍就不启用，直到下次登录');

define('_NOT_NOTHINGTODELETE', '没有删除任何内容');
