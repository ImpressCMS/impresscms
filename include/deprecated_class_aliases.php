<?php

return [
	'icms_config_category_Handler',
	'icms_config_category_Object',
	'icms_config_item_Handler',
	'icms_config_item_Object',
	'icms_config_option_Handler',
	'icms_config_option_Object',
	'icms_config_Handler',
	'icms_core_DataFilter' => \ImpressCMS\Core\DataFilter::class,
	'icms_core_Debug' => \ImpressCMS\Core\Debug::class,
	'icms_core_Filesystem' => \ImpressCMS\Core\Filesystem::class,
	'icms_core_HTMLFilter' => \ImpressCMS\Core\HTMLFilter::class,
	'icms_core_Logger' => \ImpressCMS\Core\Logger::class,
	'icms_core_Message' => \ImpressCMS\Core\Message::class,
	'icms_core_Object' => \ImpressCMS\Core\AbstractModel::class,
	'icms_core_ObjectHandler',
	'icms_core_OnlineHandler' => \ImpressCMS\Core\Online::class,
	'icms_core_Password' => \ImpressCMS\Core\Password::class,
	'icms_core_Security' => \ImpressCMS\Core\Security::class,
	'icms_core_StopSpammer' => \ImpressCMS\Core\StopSpammer::class,
	'icms_core_Textsanitizer' => \ImpressCMS\Core\Textsanitizer::class,
	'icms_core_Versionchecker' => \ImpressCMS\Core\VersionChecker::class,
	'icms_data_avatar_Handler',
	'icms_data_avatar_Object',
	'icms_data_comment_Handler',
	'icms_data_comment_Object',
	'icms_data_comment_Renderer' => \ImpressCMS\Core\Data\Comment\CommentRenderer::class,
	'icms_data_file_Handler',
	'icms_data_file_Object',
	'icms_data_notification_Handler',
	'icms_data_notification_Object',
	'icms_data_page_Handler',
	'icms_data_page_Object',
	'icms_data_privmessage_Handler',
	'icms_data_privmessage_Object',
	'icms_data_urllink_Handler',
	'icms_data_urllink_Object',
	'icms_db_criteria_Compo' => \ImpressCMS\Core\Database\Criteria\CriteriaCompo::class,
	'icms_db_criteria_Element' => \ImpressCMS\Core\Database\Criteria\CriteriaElement::class,
	'icms_db_criteria_Item' => \ImpressCMS\Core\Database\Criteria\CriteriaItem::class,
	'icms_db_legacy_mysql_Utility',
	'icms_db_legacy_updater_Handler',
	'icms_db_legacy_updater_Table',
	'icms_db_legacy_Factory',
	'icms_db_mysql_Utility',
	'icms_db_Connection',
	'icms_db_Factory',
	'icms_db_IConnection',
	'icms_db_legacy_IDatabase',
	'icms_db_IUtility',
	'icms_feeds_Rss' => \ImpressCMS\Core\Feeds\Rss::class,
	'icms_feeds_Simplerss' => \ImpressCMS\Core\Feeds\Simplerss::class,
	'icms_file_DownloadHandler' => \ImpressCMS\Core\File\DownloadHandler::class,
	'icms_file_MediaUploadHandler' => \ImpressCMS\Core\File\MediaUploadHandler::class,
	'icms_file_TarDownloader' => \ImpressCMS\Core\File\TarDownloader::class,
	'icms_file_ZipDownloader' => \ImpressCMS\Core\File\ZipDownloader::class,
	'icms_form_elements_captcha_Image',
	'icms_form_elements_captcha_ImageHandler',
	'icms_form_elements_captcha_Object',
	'icms_form_elements_captcha_Text',
	'icms_form_elements_select_Country',
	'icms_form_elements_select_Editor',
	'icms_form_elements_select_Group',
	'icms_form_elements_select_Image',
	'icms_form_elements_select_Lang',
	'icms_form_elements_select_Matchoption',
	'icms_form_elements_select_Theme',
	'icms_form_elements_select_Timezone',
	'icms_form_elements_select_User',
	'icms_form_elements_Button',
	'icms_form_elements_Captcha',
	'icms_form_elements_Checkbox',
	'icms_form_elements_Colorpicker',
	'icms_form_elements_Date',
	'icms_form_elements_Datetime',
	'icms_form_elements_Dhtmltextarea',
	'icms_form_elements_Editor',
	'icms_form_elements_File',
	'icms_form_elements_Groupperm',
	'icms_form_elements_Hidden',
	'icms_form_elements_Hiddentoken',
	'icms_form_elements_Label',
	'icms_form_elements_Password',
	'icms_form_elements_Radio',
	'icms_form_elements_Radioyn',
	'icms_form_elements_Select',
	'icms_form_elements_Text',
	'icms_form_elements_Textarea',
	'icms_form_elements_Tray',
	'icms_form_Base',
	'icms_form_Element',
	'icms_form_Groupperm',
	'icms_form_Table',
	'icms_form_Theme',
	'icms_image_body_Handler',
	'icms_image_body_Object',
	'icms_image_category_Handler',
	'icms_image_category_Object',
	'icms_image_set_Handler',
	'icms_image_set_Object',
	'icms_image_Handler',
	'icms_image_Object',
	'icms_ipf_category_Handler',
	'icms_ipf_category_Object',
	'icms_ipf_export_Handler',
	'icms_ipf_export_Renderer',
	'icms_ipf_form_elements_Autocomplete',
	'icms_ipf_form_elements_Blockoptions',
	'icms_ipf_form_elements_Checkbox',
	'icms_ipf_form_elements_Date',
	'icms_ipf_form_elements_Datetime',
	'icms_ipf_form_elements_File',
	'icms_ipf_form_elements_Fileupload',
	'icms_ipf_form_elements_Image',
	'icms_ipf_form_elements_Imageupload',
	'icms_ipf_form_elements_Language',
	'icms_ipf_form_elements_Page',
	'icms_ipf_form_elements_Parentcategory',
	'icms_ipf_form_elements_Passwordtray',
	'icms_ipf_form_elements_Radio',
	'icms_ipf_form_elements_Richfile',
	'icms_ipf_form_elements_Section',
	'icms_ipf_form_elements_Select',
	'icms_ipf_form_elements_Selectmulti',
	'icms_ipf_form_elements_Signature',
	'icms_ipf_form_elements_Source',
	'icms_ipf_form_elements_Text',
	'icms_ipf_form_elements_Time',
	'icms_ipf_form_elements_Upload',
	'icms_ipf_form_elements_Urllink',
	'icms_ipf_form_elements_User',
	'icms_ipf_form_elements_Yesno',
	'icms_ipf_form_Base',
	'icms_ipf_form_Secure',
	'icms_ipf_member_Handler',
	'icms_ipf_permission_Handler',
	'icms_ipf_registry_Handler',
	'icms_ipf_seo_Object',
	'icms_ipf_view_Column',
	'icms_ipf_view_Row',
	'icms_ipf_view_Single',
	'icms_ipf_view_Table',
	'icms_ipf_view_Tree',
	'icms_ipf_About',
	'icms_ipf_Controller',
	'icms_ipf_Handler',
	'icms_ipf_Highlighter',
	'icms_ipf_Metagen',
	'icms_ipf_Object' => \ImpressCMS\Core\IPF\AbstractModel::class,
	'icms_ipf_Tree',
	'icms_member_group_membership_Handler',
	'icms_member_group_membership_Object',
	'icms_member_group_Handler',
	'icms_member_group_Object',
	'icms_member_groupperm_Handler',
	'icms_member_groupperm_Object',
	'icms_member_rank_Handler',
	'icms_member_rank_Object',
	'icms_member_user_Handler',
	'icms_member_user_Object',
	'icms_member_Handler',
	'icms_messaging_EmailHandler',
	'icms_messaging_Handler',
	'icms_module_Handler',
	'icms_module_Object',
	'icms_plugins_EditorHandler',
	'icms\\plugins\\EditorInterface',
	'icms_plugins_Handler',
	'icms_plugins_Object',
	'icms_preload_Handler',
	'icms_preload_Item',
	'icms_preload_LibrariesHandler',
	'icms_properties_Handler',
	'icms_sys_autotasks_ISystem',
	'icms_sys_autotasks_System',
	'icms_view_block_position_Handler',
	'icms_view_block_position_Object',
	'icms_view_block_Handler',
	'icms_view_block_Object',
	'icms_view_template_file_Handler',
	'icms_view_template_file_Object',
	'icms_view_template_set_Handler',
	'icms_view_template_set_Object',
	'icms_view_theme_Factory',
	'icms_view_theme_Object',
	'icms_view_Breadcrumb',
	'icms_view_PageBuilder',
	'icms_view_PageNav',
	'icms_view_Printerfriendly',
	'icms_view_Tpl',
	'icms_view_Tree',
	'icms_Autoloader',
	'icms_Event',
	'icms_Utils'
];