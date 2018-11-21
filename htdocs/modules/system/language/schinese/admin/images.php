<?php
// $Id: blauer-fisch $
//%%%%%% Image Manager %%%%%

define('_MD_IMGMAIN','图像管理');

define('_MD_ADDIMGCAT','新建分类:');
define('_MD_EDITIMGCAT','编辑分类:');
define('_MD_IMGCATNAME','分类名称:');
define('_MD_IMGCATRGRP','选择可使用图像管理的群组:<br /><br /><span style="font-weight: normal;">这些组可对选定的图像使用图像管理器，但不能上传。网站管理员自动访问。</span>');
define('_MD_IMGCATWGRP','选择允许上传图像的群组:<br /><br /><span style="font-weight: normal;">通常给管理员使用</span>');
define('_MD_IMGCATWEIGHT','图像管理器的显示次序:');
define('_MD_IMGCATDISPLAY','要显示此分类?');
define('_MD_IMGCATSTRTYPE','图片上传至:');
define('_MD_STRTYOPENG','此设定以后不能更改!');
define('_MD_INDB',' 储存在数据库(以二进制"blob"数据格式)');
define('_MD_ASFILE',' 以文件方式储存(在目录 %s)<br />');
define('_MD_RUDELIMGCAT','确认要删除此分类以及分类下的图像文件吗?');
define('_MD_RUDELIMG','确认要删除这个图像文件吗?');

define('_MD_FAILDEL', '从数据库删除图像 %s 失败');
define('_MD_FAILDELCAT', '从数据库删除图像分类 %s 失败');
define('_MD_FAILUNLINK', '从服务器目录中删除图像 %s 失败');

######################## Added in 1.2 ###################################
define('_MD_FAILADDCAT', '添加图像分类失败');
define('_MD_FAILEDIT', '上传图像失败');
define('_MD_FAILEDITCAT', '分类更新失败');
define('_MD_IMGCATPARENT','上级分类:');
define('_MD_DELETEIMGCAT','删除图像分类');

define('_MD_ADDIMGCATBTN','新建分类');
define('_MD_ADDIMGBTN','新加图像');

define('_MD_IMAGESIN', '图像 %s');
define('_MD_IMAGESTOT', '<b>全部图像:</b> %s');

define('_MD_IMAGECATID', 'ID');
define('_MD_IMAGECATNAME', '标题');
define('_MD_IMGCATFOLDERNAME', '文件夹Folder Name');
define('_MD_IMGCATFOLDERNAME_DESC', '不要使用空格或特殊字符!');
define('_MD_IMAGECATMSIZE', '最大尺寸');
define('_MD_IMAGECATMWIDTH', '最大宽度');
define('_MD_IMAGECATMHEIGHT', '最大高度');
define('_MD_IMAGECATDISP', '显示');
define('_MD_IMAGECATSTYPE', '储存类型');
define('_MD_IMAGECATATUORESIZE', '自动调整尺寸');
define('_MD_IMAGECATWEIGHT', '宽度');
define('_MD_IMAGECATOPTIONS', '选项');
define('_MD_IMAGECATQTDE', '# 图像');
define('_IMAGEFILTERS', '选择滤镜:');
define('_IMAGEAPPLYFILTERS', '将该滤镜应用在图片上');
define('_IMAGEFILTERSSAVE', '要覆盖原始图像吗?');
define('_IMGCROP', '剪切工具');
define('_IMGFILTER', '滤镜工具');

define('_MD_IMAGECATSUBS', '子分类');

define('_WIDTH', '宽度');
define('_HEIGHT', '高度');
define('_DIMENSION', '尺寸');
define('_CROPTOOL', '剪切工具');
define('_IMGDETAILS', '图像详细信息');
define('_INSTRUCTIONS', '图像说明');
define('_INSTRUCTIONS_DSC', '选择要剪切的区域，拖动方框或是直接输入数值');

define('_MD_IMAGE_EDITORTITLE', 'DHTML图像编辑器');
define('_MD_IMAGE_VIEWSUBS', '查看子分类');
define('_MD_IMAGE_COPYOF', '复制Copy of ');

define('IMANAGER_FILE', '文件');
define('IMANAGER_WIDTH', '宽度');
define('IMANAGER_HEIGHT', '高度');
define('IMANAGER_SIZE', '尺寸');
define('IMANAGER_ORIGINAL', '原始图像');
define('IMANAGER_EDITED', '编辑图像');
define('IMANAGER_FOLDER_NOT_WRITABLE', '服务器中的文件夹不可写入');

// added in 1.3
define('IMANAGER_NOPERM', '没有访问权限!');