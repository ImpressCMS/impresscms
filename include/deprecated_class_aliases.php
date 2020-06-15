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
	'icms_core_ObjectHandler' => \ImpressCMS\Core\ObjectHandler::class,
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
	'icms_form_Base' => \ImpressCMS\Core\Form\AbstractForm::class,
	'icms_form_Element' => \ImpressCMS\Core\Form\AbstractFormElement::class,
	'icms_form_Groupperm' => \ImpressCMS\Core\Form\GroupPermissionForm::class,
	'icms_form_Table' => \ImpressCMS\Core\Form\TableForm::class,
	'icms_form_Theme' => \ImpressCMS\Core\Form\ThemeForm::class,
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
	'icms_ipf_export_Handler' => \ImpressCMS\Core\IPF\Export\Exporter::class,
	'icms_ipf_export_Renderer' => \ImpressCMS\Core\IPF\Export\ExportRenderer::class,
	'icms_ipf_form_elements_Autocomplete' => \ImpressCMS\Core\IPF\Form\Elements\AutocompleteElement::class,
	'icms_ipf_form_elements_Blockoptions' => \ImpressCMS\Core\IPF\Form\Elements\BlockOptionsElement::class,
	'icms_ipf_form_elements_Checkbox' => \ImpressCMS\Core\IPF\Form\Elements\CheckboxElement::class,
	'icms_ipf_form_elements_Date' => \ImpressCMS\Core\IPF\Form\Elements\DateElement::class,
	'icms_ipf_form_elements_Datetime' => \ImpressCMS\Core\IPF\Form\Elements\DateTimeElement::class,
	'icms_ipf_form_elements_File' => \ImpressCMS\Core\IPF\Form\Elements\FileElement::class,
	'icms_ipf_form_elements_Fileupload' => \ImpressCMS\Core\IPF\Form\Elements\FileUploadElement::class,
	'icms_ipf_form_elements_Image' => \ImpressCMS\Core\IPF\Form\Elements\ImageElement::class,
	'icms_ipf_form_elements_Imageupload' => \ImpressCMS\Core\IPF\Form\Elements\ImageUploadElement::class,
	'icms_ipf_form_elements_Language' => \ImpressCMS\Core\IPF\Form\Elements\LanguageElement::class,
	'icms_ipf_form_elements_Page' => \ImpressCMS\Core\IPF\Form\Elements\PageElement::class,
	'icms_ipf_form_elements_Parentcategory' => \ImpressCMS\Core\IPF\Form\Elements\ParentCategoryElement::class,
	'icms_ipf_form_elements_Passwordtray' => \ImpressCMS\Core\IPF\Form\Elements\PasswordTrayElement::class,
	'icms_ipf_form_elements_Radio' => \ImpressCMS\Core\IPF\Form\Elements\RadioElement::class,
	'icms_ipf_form_elements_Richfile' => \ImpressCMS\Core\IPF\Form\Elements\RichfileElement::class,
	'icms_ipf_form_elements_Section' => \ImpressCMS\Core\IPF\Form\Elements\FormSectionElement::class,
	'icms_ipf_form_elements_Select' => \ImpressCMS\Core\IPF\Form\Elements\SelectElement::class,
	'icms_ipf_form_elements_Selectmulti' => \ImpressCMS\Core\IPF\Form\Elements\SelectMultiElement::class,
	'icms_ipf_form_elements_Signature'=> \ImpressCMS\Core\IPF\Form\Elements\SignatureElement::class,
	'icms_ipf_form_elements_Source' => \ImpressCMS\Core\IPF\Form\Elements\SourceElement::class,
	'icms_ipf_form_elements_Text' => \ImpressCMS\Core\IPF\Form\Elements\TextElement::class,
	'icms_ipf_form_elements_Time' => \ImpressCMS\Core\IPF\Form\Elements\TimeElement::class,
	'icms_ipf_form_elements_Upload' => \ImpressCMS\Core\IPF\Form\Elements\UploadElement::class,
	'icms_ipf_form_elements_Urllink' => \ImpressCMS\Core\IPF\Form\Elements\URLLinkElement::class,
	'icms_ipf_form_elements_User' => \ImpressCMS\Core\IPF\Form\Elements\UserElement::class,
	'icms_ipf_form_elements_Yesno' => \ImpressCMS\Core\IPF\Form\Elements\YesNoElement::class,
	'icms_ipf_form_Base' => \ImpressCMS\Core\IPF\Form\Form::class,
	'icms_ipf_form_Secure' => \ImpressCMS\Core\IPF\Form\SecureForm::class,
	'icms_ipf_member_Handler',
	'icms_ipf_permission_Handler' => \ImpressCMS\Core\IPF\PermissionsDecorator::class,
	'icms_ipf_registry_Handler' => \ImpressCMS\Core\IPF\ObjectRegistry::class,
	'icms_ipf_seo_Object' => \ImpressCMS\Core\IPF\Seo\AbstractSEOModel::class,
	'icms_ipf_view_Column',
	'icms_ipf_view_Row',
	'icms_ipf_view_Single',
	'icms_ipf_view_Table',
	'icms_ipf_view_Tree',
	'icms_ipf_About' => \ImpressCMS\Core\IPF\About::class,
	'icms_ipf_Controller' => \ImpressCMS\Core\IPF\Controller::class,
	'icms_ipf_Handler' => \ImpressCMS\Core\IPF\Handler::class,
	'icms_ipf_Highlighter' => \ImpressCMS\Core\IPF\Highlighter::class,
	'icms_ipf_Metagen' => \ImpressCMS\Core\IPF\Metagen::class,
	'icms_ipf_Object' => \ImpressCMS\Core\IPF\AbstractModel::class,
	'icms_ipf_Tree' => \ImpressCMS\Core\IPF\ObjectTree::class,
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
	'icms_messaging_EmailHandler' => \ImpressCMS\Core\Messaging\Mailer::class,
	'icms_messaging_Handler' => \ImpressCMS\Core\Messaging\MailHandler::class,
	'icms_module_Handler',
	'icms_module_Object',
	'icms_plugins_EditorHandler',
	'icms\\plugins\\EditorInterface' => \ImpressCMS\Core\Plugins\EditorInterface::class,
	'icms_plugins_Handler',
	'icms_plugins_Object',
	'icms_preload_Handler',
	'icms_preload_Item',
	'icms_preload_LibrariesHandler',
	'icms_properties_Handler' => \ImpressCMS\Core\Properties\AbstractProperties::class,
	'icms_sys_autotasks_ISystem' => \ImpressCMS\Core\Sys\Autotasks\AutotaskSystemInterface::class,
	'icms_sys_autotasks_System' => \ImpressCMS\Core\Sys\Autotasks\AbstractAutotaskSystem::class,
	'icms_view_block_position_Handler',
	'icms_view_block_position_Object',
	'icms_view_block_Handler',
	'icms_view_block_Object',
	'icms_view_template_file_Handler',
	'icms_view_template_file_Object',
	'icms_view_template_set_Handler',
	'icms_view_template_set_Object',
	'icms_view_theme_Factory' => \ImpressCMS\Core\View\Theme\ThemeFactory::class,
	'icms_view_theme_Object' => \ImpressCMS\Core\View\Theme\ThemeComponent::class,
	'icms_view_Breadcrumb' => \ImpressCMS\Core\View\BreadcrumbView::class,
	'icms_view_PageBuilder' => \ImpressCMS\Core\View\PageBuilder::class,
	'icms_view_PageNav' => \ImpressCMS\Core\View\PageNav::class,
	'icms_view_Printerfriendly' => \ImpressCMS\Core\View\PrinterFriendlyView::class,
	'icms_view_Tpl' => \ImpressCMS\Core\View\Template::class,
	'icms_view_Tree',
	'icms_Autoloader',
	'icms_Event',
	'icms_Utils'
];