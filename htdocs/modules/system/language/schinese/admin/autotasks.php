<?php
// $Id: blauer-fisch $
define("_CO_ICMS_AUTOTASKS_NAME", "任务名称");
define("_CO_ICMS_AUTOTASKS_NAME_DSC", "输入任务名称.");
define("_CO_ICMS_AUTOTASKS_CODE", "源代码");
define("_CO_ICMS_AUTOTASKS_CODE_DSC", "可输入PHP源代码以执行任务<p style='color:red'>没有&lt;?php 以及 ?&gt;</p><br /><br />mainfile.php文件已包含.<br />使用 <i>icms::\$xoopsDB</i> 使用数据库对象.");
define("_CO_ICMS_AUTOTASKS_REPEAT", "重复");
define("_CO_ICMS_AUTOTASKS_REPEAT_DSC", "设置任务的重复次数，输入'0'则设为一直在执行的任务。");
define("_CO_ICMS_AUTOTASKS_INTERVAL", "间隔时间");
define("_CO_ICMS_AUTOTASKS_INTERVAL_DSC", "任务执行的间隔时间，以分钟计.<br /><br />60: 一小时执行一次<br />1440: 一天执行一次");
define("_CO_ICMS_AUTOTASKS_ONFINISH", "自动删除");
define("_CO_ICMS_AUTOTASKS_ONFINISH_DSC", "任务在执行完设定的次数后，会自动删除，设为'Yes'则任务会自动从任务列表中删除，'No'则任务会进入暂定模式<br />只有重复次数大于'0'此选项启用");
define("_CO_ICMS_AUTOTASKS_ENABLED", "启用");
define("_CO_ICMS_AUTOTASKS_ENABLED_DSC", "选'Yes'启用启用任务");
define("_CO_ICMS_AUTOTASKS_TYPE", "类型");
define("_CO_ICMS_AUTOTASKS_LASTRUNTIME", "上次执行时间");

define("_CO_ICMS_AUTOTASKS_CREATE", "新建任务");
define("_CO_ICMS_AUTOTASKS_EDIT", "编辑任务");

define("_CO_ICMS_AUTOTASKS_CREATED", "任务已添加");
define("_CO_ICMS_AUTOTASKS_MODIFIED", "任务已修改");

define("_CO_ICMS_AUTOTASKS_NOTYETRUNNED", "还未执行");

define("_CO_ICMS_AUTOTASKS_TYPE_CUSTOM", "用户");
define("_CO_ICMS_AUTOTASKS_TYPE_ADDON", "系统");

define("_CO_ICMS_AUTOTASKS_FOREVER", "永久");

define("_CO_ICMS_AUTOTASKS_INIT_ERROR", "出错: 初始化所选任务子系统失败");

define("_CO_ICMS_AUTOTASKS_SOURCECODE_ERROR", "出错：自动任务源代码不能执行");
