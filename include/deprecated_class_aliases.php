<?php

return [
	'icms_config_category_Handler' => \ImpressCMS\Core\Models\ConfigCategoryHandler::class,
	'icms_config_category_Object' => \ImpressCMS\Core\Models\ConfigCategory::class,
	'icms_config_item_Handler' => \ImpressCMS\Core\Models\ConfigItemHandler::class,
	'icms_config_item_Object' => \ImpressCMS\Core\Models\ConfigItem::class,
	'icms_config_option_Handler' => \ImpressCMS\Core\Models\ConfigOptionHandler::class,
	'icms_config_option_Object' => \ImpressCMS\Core\Models\ConfigOption::class,
	'icms_config_Handler' => \ImpressCMS\Core\Facades\Config::class,
	'icms_core_DataFilter' => \ImpressCMS\Core\DataFilter::class,
	'icms_core_Debug' => \ImpressCMS\Core\Debug::class,
	'icms_core_Filesystem' => \ImpressCMS\Core\Filesystem::class,
	'icms_core_HTMLFilter' => \ImpressCMS\Core\HTMLFilter::class,
	'icms_core_Logger' => \ImpressCMS\Core\Logger::class,
	'icms_core_Message' => \ImpressCMS\Core\Message::class,
	'icms_core_Object' => \ImpressCMS\Core\AbstractModel::class,
	'icms_core_ObjectHandler' => \ImpressCMS\Core\ObjectHandler::class,
	'icms_core_OnlineHandler' => \ImpressCMS\Core\Models\OnlineHandler::class,
	'icms_core_Password' => \ImpressCMS\Core\Password::class,
	'icms_core_Security' => \ImpressCMS\Core\Security::class,
	'icms_core_StopSpammer' => \ImpressCMS\Core\StopSpammer::class,
	'icms_core_Textsanitizer' => \ImpressCMS\Core\Textsanitizer::class,
	'icms_core_Versionchecker' => \ImpressCMS\Core\VersionChecker::class,
	'icms_data_avatar_Handler' => \ImpressCMS\Core\Models\AvatarHandler::class,
	'icms_data_avatar_Object' => \ImpressCMS\Core\Models\Avatar::class,
	'icms_data_comment_Handler' => \ImpressCMS\Core\Models\CommentHandler::class,
	'icms_data_comment_Object' => \ImpressCMS\Core\Models\Comment::class,
	'icms_data_comment_Renderer' => \ImpressCMS\Core\View\CommentRenderer::class,
	'icms_data_file_Handler' => \ImpressCMS\Core\Models\FileHandler::class,
	'icms_data_file_Object' => \ImpressCMS\Core\Models\File::class,
	'icms_data_notification_Handler' => ImpressCMS\Core\Models\NotificationHandler::class,
	'icms_data_notification_Object' => \ImpressCMS\Core\Models\Notification::class,
	'icms_data_page_Handler' => \ImpressCMS\Core\Models\PageHandler::class,
	'icms_data_page_Object' => \ImpressCMS\Core\Models\Page::class,
	'icms_data_privmessage_Handler' => \ImpressCMS\Core\Models\PrivateMessageHandler::class,
	'icms_data_privmessage_Object' => \ImpressCMS\Core\Models\PrivateMessage::class,
	'icms_data_urllink_Handler' => \ImpressCMS\Core\Models\UrlLinkHandler::class,
	'icms_data_urllink_Object' => \ImpressCMS\Core\Models\UrlLink::class,
	'icms_db_criteria_Compo' => \ImpressCMS\Core\Database\Criteria\CriteriaCompo::class,
	'icms_db_criteria_Element' => \ImpressCMS\Core\Database\Criteria\CriteriaElement::class,
	'icms_db_criteria_Item' => \ImpressCMS\Core\Database\Criteria\CriteriaItem::class,
	'icms_db_legacy_mysql_Utility' => \ImpressCMS\Core\Database\Legacy\Mysql\DatabaseUtility::class,
	'icms_db_legacy_updater_Handler' => \ImpressCMS\Core\Database\Legacy\Updater\DatabaseUpdater::class,
	'icms_db_legacy_updater_Table' => \ImpressCMS\Core\Database\Legacy\Updater\TableUpdater::class,
	'icms_db_legacy_Factory' => \ImpressCMS\Core\Database\Legacy\DatabaseConnectionFactory::class,
	'icms_db_mysql_Utility' => \ImpressCMS\Core\Database\Mysql\DatabaseUtility::class,
	'icms_db_Connection' => \ImpressCMS\Core\Database\DatabaseConnection::class,
	'icms_db_Factory' => \ImpressCMS\Core\Database\DatabaseConnectionFactory::class,
	'icms_db_IConnection' => \ImpressCMS\Core\Database\DatabaseConnectionInterface::class,
	'icms_db_legacy_IDatabase' => \ImpressCMS\Core\Database\LegacyDatabaseConnectionInterface::class,
	'icms_db_IUtility' => \ImpressCMS\Core\Database\DatabaseUtilityInterface::class,
	'icms_feeds_Rss' => \ImpressCMS\Core\Feeds\Rss::class,
	'icms_feeds_Simplerss' => \ImpressCMS\Core\Feeds\Simplerss::class,
	'icms_file_DownloadHandler' => \ImpressCMS\Core\File\Downloader::class,
	'icms_file_MediaUploadHandler' => \ImpressCMS\Core\File\MediaUploader::class,
	'icms_file_TarDownloader' => \ImpressCMS\Core\File\TarDownloader::class,
	'icms_file_ZipDownloader' => \ImpressCMS\Core\File\ZipDownloader::class,
	'icms_form_elements_captcha_Image' => \ImpressCMS\Core\View\Form\Elements\Captcha\ImageMode::class,
	'icms_form_elements_captcha_ImageHandler' => \ImpressCMS\Core\View\Form\Elements\Captcha\ImageRenderer::class,
	'icms_form_elements_captcha_Object' => \ImpressCMS\Core\View\Form\Elements\Captcha\Image::class,
	'icms_form_elements_captcha_Text' => \ImpressCMS\Core\View\Form\Elements\Captcha\TextMode::class,
	'icms_form_elements_select_Country' => \ImpressCMS\Core\View\Form\Elements\Select\CountryElement::class,
	'icms_form_elements_select_Editor' => \ImpressCMS\Core\View\Form\Elements\Select\EditorElement::class,
	'icms_form_elements_select_Group' => \ImpressCMS\Core\View\Form\Elements\Select\GroupElement::class,
	'icms_form_elements_select_Image' => \ImpressCMS\Core\View\Form\Elements\Select\ImageElement::class,
	'icms_form_elements_select_Lang' => \ImpressCMS\Core\View\Form\Elements\Select\LangElement::class,
	'icms_form_elements_select_Matchoption' => \ImpressCMS\Core\View\Form\Elements\Select\MatchOptionElement::class,
	'icms_form_elements_select_Theme' => \ImpressCMS\Core\View\Form\Elements\Select\ThemeElement::class,
	'icms_form_elements_select_Timezone' => \ImpressCMS\Core\View\Form\Elements\Select\TimeZoneElement::class,
	'icms_form_elements_select_User' => \ImpressCMS\Core\View\Form\Elements\Select\UserElement::class,
	'icms_form_elements_Button' => \ImpressCMS\Core\View\Form\Elements\ButtonElement::class,
	'icms_form_elements_Captcha' => \ImpressCMS\Core\View\Form\Elements\CaptchaElement::class,
	'icms_form_elements_Checkbox' => \ImpressCMS\Core\View\Form\Elements\CheckboxElement::class,
	'icms_form_elements_Colorpicker' => \ImpressCMS\Core\View\Form\Elements\ColorPickerElement::class,
	'icms_form_elements_Date' => \ImpressCMS\Core\View\Form\Elements\DateElement::class,
	'icms_form_elements_Datetime' => \ImpressCMS\Core\View\Form\Elements\DateTimeElement::class,
	'icms_form_elements_Dhtmltextarea' => \ImpressCMS\Core\View\Form\Elements\DHTMLTextAreaElement::class,
	'icms_form_elements_Editor' => \ImpressCMS\Core\View\Form\Elements\EditorElement::class,
	'icms_form_elements_File' => \ImpressCMS\Core\View\Form\Elements\FileElement::class,
	'icms_form_elements_Groupperm' => \ImpressCMS\Core\View\Form\Elements\GroupPermissionElement::class,
	'icms_form_elements_Hidden' => \ImpressCMS\Core\View\Form\Elements\HiddenElement::class,
	'icms_form_elements_Hiddentoken' => \ImpressCMS\Core\View\Form\Elements\HiddenTokenElement::class,
	'icms_form_elements_Label' => \ImpressCMS\Core\View\Form\Elements\LabelElement::class,
	'icms_form_elements_Password' => \ImpressCMS\Core\View\Form\Elements\PasswordElement::class,
	'icms_form_elements_Radio' => \ImpressCMS\Core\View\Form\Elements\RadioElement::class,
	'icms_form_elements_Radioyn' => \ImpressCMS\Core\View\Form\Elements\RadioYesNoElement::class,
	'icms_form_elements_Select' => \ImpressCMS\Core\View\Form\Elements\SelectElement::class,
	'icms_form_elements_Text' => \ImpressCMS\Core\View\Form\Elements\TextElement::class,
	'icms_form_elements_Textarea' => \ImpressCMS\Core\View\Form\Elements\TextAreaElement::class,
	'icms_form_elements_Tray' => \ImpressCMS\Core\View\Form\Elements\TrayElement::class,
	'icms_form_Base' => \ImpressCMS\Core\View\Form\AbstractForm::class,
	'icms_form_Element' => \ImpressCMS\Core\View\Form\AbstractFormElement::class,
	'icms_form_Groupperm' => \ImpressCMS\Core\View\Form\GroupPermissionForm::class,
	'icms_form_Table' => \ImpressCMS\Core\View\Form\TableForm::class,
	'icms_form_Theme' => \ImpressCMS\Core\View\Form\ThemeForm::class,
	'icms_image_body_Handler' => \ImpressCMS\Core\Models\ImageBodyHandler::class,
	'icms_image_body_Object' => \ImpressCMS\Core\Models\ImageBody::class,
	'icms_image_category_Handler' => \ImpressCMS\Core\Models\ImageCategoryHandler::class,
	'icms_image_category_Object' => \ImpressCMS\Core\Models\ImageCategory::class,
	'icms_image_set_Handler' => \ImpressCMS\Core\Models\ImageSetHandler::class,
	'icms_image_set_Object' => \ImpressCMS\Core\Models\ImageSet::class,
	'icms_image_Handler' => \ImpressCMS\Core\Models\ImageHandler::class,
	'icms_image_Object' => \ImpressCMS\Core\Models\Image::class,
	'icms_ipf_category_Handler' => \ImpressCMS\Core\Models\CategoryHandler::class,
	'icms_ipf_category_Object' => \ImpressCMS\Core\Models\Category::class,
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
	'icms_ipf_member_Handler' => \ImpressCMS\Core\IPF\MemberDecorator::class,
	'icms_ipf_permission_Handler' => \ImpressCMS\Core\IPF\PermissionsDecorator::class,
	'icms_ipf_registry_Handler' => \ImpressCMS\Core\IPF\ObjectRegistry::class,
	'icms_ipf_seo_Object' => \ImpressCMS\Core\IPF\AbstractSEOModel::class,
	'icms_ipf_view_Column' => \ImpressCMS\Core\IPF\View\ViewColumn::class,
	'icms_ipf_view_Row' => \ImpressCMS\Core\IPF\View\ViewRow::class,
	'icms_ipf_view_Single' => \ImpressCMS\Core\IPF\View\ViewSingleObject::class,
	'icms_ipf_view_Table' => \ImpressCMS\Core\IPF\View\ViewTable::class,
	'icms_ipf_view_Tree' => \ImpressCMS\Core\IPF\View\ViewTree::class,
	'icms_ipf_About' => \ImpressCMS\Core\View\ModuleAboutRenderer::class,
	'icms_ipf_Controller' => \ImpressCMS\Core\IPF\Controller::class,
	'icms_ipf_Handler' => \ImpressCMS\Core\IPF\Handler::class,
	'icms_ipf_Highlighter' => \ImpressCMS\Core\View\Highlighter::class,
	'icms_ipf_Metagen' => \ImpressCMS\Core\IPF\Metagen::class,
	'icms_ipf_Object' => \ImpressCMS\Core\IPF\AbstractModel::class,
	'icms_ipf_Tree' => \ImpressCMS\Core\IPF\ObjectTree::class,
	'icms_member_group_membership_Handler' => \ImpressCMS\Core\Models\GroupMembershipHandler::class,
	'icms_member_group_membership_Object' => \ImpressCMS\Core\Models\GroupMembership::class,
	'icms_member_group_Handler' => \ImpressCMS\Core\Models\GroupHandler::class,
	'icms_member_group_Object'=> \ImpressCMS\Core\Models\Group::class,
	'icms_member_groupperm_Handler'=> \ImpressCMS\Core\Models\GroupPermHandler::class,
	'icms_member_groupperm_Object'=> \ImpressCMS\Core\Models\GroupPerm::class,
	'icms_member_rank_Handler' => \ImpressCMS\Core\Models\UserRankHandler::class,
	'icms_member_rank_Object' => \ImpressCMS\Core\Models\UserRank::class,
	'icms_member_user_Handler' => \ImpressCMS\Core\Models\UserHandler::class,
	'icms_member_user_Object' => \ImpressCMS\Core\Models\User::class,
	'icms_member_Handler' => \ImpressCMS\Core\Facades\Member::class,
	'icms_messaging_EmailHandler' => \ImpressCMS\Core\Messaging\Mailer::class,
	'icms_messaging_Handler' => \ImpressCMS\Core\Messaging\MailHandler::class,
	'icms_module_Handler' => \ImpressCMS\Core\Models\ModuleHandler::class,
	'icms_module_Object' => \ImpressCMS\Core\Models\Module::class,
	'icms_plugins_EditorHandler' => \ImpressCMS\Core\Plugins\EditorHandler::class,
	'icms\\plugins\\EditorInterface' => \ImpressCMS\Core\Plugins\EditorInterface::class,
	'icms_plugins_Handler' => \ImpressCMS\Core\Plugins\PluginHandler::class,
	'icms_plugins_Object' => \ImpressCMS\Core\Plugins\Plugin::class,
	'icms_preload_Handler' => \ImpressCMS\Core\Preload\EventsPreloader::class,
	'icms_preload_Item' => \ImpressCMS\Core\Preload\AbstractPreloadItem::class,
	'icms_preload_LibrariesHandler' => \ImpressCMS\Core\Preload\LibrariesPreloader::class,
	'icms_properties_Handler' => \ImpressCMS\Core\Properties\AbstractProperties::class,
	'icms_sys_autotasks_ISystem' => \ImpressCMS\Core\Sys\Autotasks\AutotaskSystemInterface::class,
	'icms_sys_autotasks_System' => \ImpressCMS\Core\Sys\Autotasks\AbstractAutotaskSystem::class,
	'icms_view_block_position_Handler' => \ImpressCMS\Core\Models\BlockPositionHandler::class,
	'icms_view_block_position_Object' => \ImpressCMS\Core\Models\BlockPosition::class,
	'icms_view_block_Handler' => \ImpressCMS\Core\Models\BlockHandler::class,
	'icms_view_block_Object' => \ImpressCMS\Core\Models\Block::class,
	'icms_view_template_file_Handler' => \ImpressCMS\Core\Models\TemplateFileHandler::class,
	'icms_view_template_file_Object' => \ImpressCMS\Core\Models\TemplateFile::class,
	'icms_view_template_set_Handler' => \ImpressCMS\Core\Models\TemplateSetHandler::class,
	'icms_view_template_set_Object' => \ImpressCMS\Core\Models\TemplateSet::class,
	'icms_view_theme_Factory' => \ImpressCMS\Core\View\Theme\ThemeFactory::class,
	'icms_view_theme_Object' => \ImpressCMS\Core\View\Theme\ThemeComponent::class,
	'icms_view_Breadcrumb' => \ImpressCMS\Core\View\BreadcrumbView::class,
	'icms_view_PageBuilder' => \ImpressCMS\Core\View\PageBuilder::class,
	'icms_view_PageNav' => \ImpressCMS\Core\View\PageNav::class,
	'icms_view_Printerfriendly' => \ImpressCMS\Core\View\PrinterFriendlyView::class,
	'icms_view_Tpl' => \ImpressCMS\Core\View\Template::class,
	'icms_view_Tree' => \ImpressCMS\Core\View\ViewTree::class,
	'icms_Autoloader' => \ImpressCMS\Core\Autoloader::class,
	'icms_Event' => \ImpressCMS\Core\Event::class,
	'icms_Utils' => \ImpressCMS\Core\Utils::class,
];