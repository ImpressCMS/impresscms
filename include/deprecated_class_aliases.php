<?php

return [
	'icms_config_category_Handler' => \ImpressCMS\Core\Config\ConfigCategoryHandler::class,
	'icms_config_category_Object' => \ImpressCMS\Core\Config\ConfigCategoryModel::class,
	'icms_config_item_Handler' => \ImpressCMS\Core\Config\ConfigItemHandler::class,
	'icms_config_item_Object' => \ImpressCMS\Core\Config\ConfigItemModel::class,
	'icms_config_option_Handler' => \ImpressCMS\Core\Config\ConfigOptionHandler::class,
	'icms_config_option_Object' => \ImpressCMS\Core\Config\ConfigOptionModel::class,
	'icms_config_Handler' => \ImpressCMS\Core\Config\Config::class,
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
	'icms_data_avatar_Handler' => \ImpressCMS\Core\Data\Avatar\AvatarHandler::class,
	'icms_data_avatar_Object' => \ImpressCMS\Core\Data\Avatar\AvatarModel::class,
	'icms_data_comment_Handler' => \ImpressCMS\Core\Data\Comment\CommentHandler::class,
	'icms_data_comment_Object' => \ImpressCMS\Core\Data\Comment\CommentModel::class,
	'icms_data_comment_Renderer' => \ImpressCMS\Core\Data\Comment\CommentRenderer::class,
	'icms_data_file_Handler' => \ImpressCMS\Core\Data\File\FileHandler::class,
	'icms_data_file_Object' => \ImpressCMS\Core\Data\File\FileModel::class,
	'icms_data_notification_Handler' => ImpressCMS\Core\Data\Notification\NotificationHandler::class,
	'icms_data_notification_Object' => \ImpressCMS\Core\Data\Notification\NotificationModel::class,
	'icms_data_page_Handler' => \ImpressCMS\Core\Data\Page\PageHandler::class,
	'icms_data_page_Object' => \ImpressCMS\Core\Data\Page\PageModel::class,
	'icms_data_privmessage_Handler' => \ImpressCMS\Core\Data\PrivateMessage\PrivateMessageHandler::class,
	'icms_data_privmessage_Object' => \ImpressCMS\Core\Data\PrivateMessage\PrivateMessageModel::class,
	'icms_data_urllink_Handler' => \ImpressCMS\Core\Data\UrlLink\UrlLinkHandler::class,
	'icms_data_urllink_Object' => \ImpressCMS\Core\Data\UrlLink\UrlLinkModel::class,
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
	'icms_file_DownloadHandler' => \ImpressCMS\Core\File\DownloadHandler::class,
	'icms_file_MediaUploadHandler' => \ImpressCMS\Core\File\MediaUploadHandler::class,
	'icms_file_TarDownloader' => \ImpressCMS\Core\File\TarDownloader::class,
	'icms_file_ZipDownloader' => \ImpressCMS\Core\File\ZipDownloader::class,
	'icms_form_elements_captcha_Image' => \ImpressCMS\Core\Form\Elements\Captcha\ImageMode::class,
	'icms_form_elements_captcha_ImageHandler' => \ImpressCMS\Core\Form\Elements\Captcha\ImageHandler::class,
	'icms_form_elements_captcha_Object' => \ImpressCMS\Core\Form\Elements\Captcha\Image::class,
	'icms_form_elements_captcha_Text' => \ImpressCMS\Core\Form\Elements\Captcha\TextMode::class,
	'icms_form_elements_select_Country' => \ImpressCMS\Core\Form\Elements\Select\CountryElement::class,
	'icms_form_elements_select_Editor' => \ImpressCMS\Core\Form\Elements\Select\EditorElement::class,
	'icms_form_elements_select_Group' => \ImpressCMS\Core\Form\Elements\Select\GroupElement::class,
	'icms_form_elements_select_Image' => \ImpressCMS\Core\Form\Elements\Select\ImageElement::class,
	'icms_form_elements_select_Lang' => \ImpressCMS\Core\Form\Elements\Select\LangElement::class,
	'icms_form_elements_select_Matchoption' => \ImpressCMS\Core\Form\Elements\Select\MatchOptionElement::class,
	'icms_form_elements_select_Theme' => \ImpressCMS\Core\Form\Elements\Select\ThemeElement::class,
	'icms_form_elements_select_Timezone' => \ImpressCMS\Core\Form\Elements\Select\TimezoneElement::class,
	'icms_form_elements_select_User' => \ImpressCMS\Core\Form\Elements\Select\UserElement::class,
	'icms_form_elements_Button' => \ImpressCMS\Core\Form\Elements\ButtonElement::class,
	'icms_form_elements_Captcha' => \ImpressCMS\Core\Form\Elements\CaptchaElement::class,
	'icms_form_elements_Checkbox' => \ImpressCMS\Core\Form\Elements\CheckboxElement::class,
	'icms_form_elements_Colorpicker' => \ImpressCMS\Core\Form\Elements\ColorpickerElement::class,
	'icms_form_elements_Date' => \ImpressCMS\Core\Form\Elements\DateElement::class,
	'icms_form_elements_Datetime' => \ImpressCMS\Core\IPF\Form\Elements\DateTimeElement::class,
	'icms_form_elements_Dhtmltextarea' => \ImpressCMS\Core\Form\Elements\DhtmltextareaElement::class,
	'icms_form_elements_Editor' => \ImpressCMS\Core\Form\Elements\EditorElement::class,
	'icms_form_elements_File' => \ImpressCMS\Core\Form\Elements\FileElement::class,
	'icms_form_elements_Groupperm' => \ImpressCMS\Core\Form\Elements\GrouppermElement::class,
	'icms_form_elements_Hidden' => \ImpressCMS\Core\Form\Elements\HiddenElement::class,
	'icms_form_elements_Hiddentoken' => \ImpressCMS\Core\Form\Elements\HiddentokenElement::class,
	'icms_form_elements_Label' => \ImpressCMS\Core\Form\Elements\LabelElement::class,
	'icms_form_elements_Password' => \ImpressCMS\Core\Form\Elements\PasswordElement::class,
	'icms_form_elements_Radio' => \ImpressCMS\Core\Form\Elements\RadioElement::class,
	'icms_form_elements_Radioyn' => \ImpressCMS\Core\Form\Elements\RadioynElement::class,
	'icms_form_elements_Select' => \ImpressCMS\Core\Form\Elements\SelectElement::class,
	'icms_form_elements_Text' => \ImpressCMS\Core\Form\Elements\TextElement::class,
	'icms_form_elements_Textarea' => \ImpressCMS\Core\Form\Elements\TextareaElement::class,
	'icms_form_elements_Tray' => \ImpressCMS\Core\Form\Elements\TrayElement::class,
	'icms_form_Base' => \ImpressCMS\Core\Form\AbstractForm::class,
	'icms_form_Element' => \ImpressCMS\Core\Form\AbstractFormElement::class,
	'icms_form_Groupperm' => \ImpressCMS\Core\Form\GroupPermissionForm::class,
	'icms_form_Table' => \ImpressCMS\Core\Form\TableForm::class,
	'icms_form_Theme' => \ImpressCMS\Core\Form\ThemeForm::class,
	'icms_image_body_Handler' => \ImpressCMS\Core\Image\ImageBodyHandler::class,
	'icms_image_body_Object' => \ImpressCMS\Core\Image\ImageBodyModel::class,
	'icms_image_category_Handler' => \ImpressCMS\Core\Image\ImageCategoryHandler::class,
	'icms_image_category_Object' => \ImpressCMS\Core\Image\ImageCategoryModel::class,
	'icms_image_set_Handler' => \ImpressCMS\Core\Image\ImageSetHandler::class,
	'icms_image_set_Object' => \ImpressCMS\Core\Image\ImageSetModel::class,
	'icms_image_Handler' => \ImpressCMS\Core\Image\ImageHandler::class,
	'icms_image_Object' => \ImpressCMS\Core\Image\ImageModel::class,
	'icms_ipf_category_Handler' => \ImpressCMS\Core\IPF\Category\CategoryHandler::class,
	'icms_ipf_category_Object' => \ImpressCMS\Core\IPF\Category\CategoryModel::class,
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
	'icms_ipf_member_Handler' => \ImpressCMS\Core\IPF\MemberHandler::class,
	'icms_ipf_permission_Handler' => \ImpressCMS\Core\IPF\PermissionsDecorator::class,
	'icms_ipf_registry_Handler' => \ImpressCMS\Core\IPF\ObjectRegistry::class,
	'icms_ipf_seo_Object' => \ImpressCMS\Core\IPF\Seo\AbstractSEOModel::class,
	'icms_ipf_view_Column' => \ImpressCMS\Core\IPF\View\ViewColumn::class,
	'icms_ipf_view_Row' => \ImpressCMS\Core\IPF\View\ViewRow::class,
	'icms_ipf_view_Single' => \ImpressCMS\Core\IPF\View\ViewSingleObject::class,
	'icms_ipf_view_Table' => \ImpressCMS\Core\IPF\View\ViewTable::class,
	'icms_ipf_view_Tree' => \ImpressCMS\Core\IPF\View\ViewTree::class,
	'icms_ipf_About' => \ImpressCMS\Core\IPF\About::class,
	'icms_ipf_Controller' => \ImpressCMS\Core\IPF\Controller::class,
	'icms_ipf_Handler' => \ImpressCMS\Core\IPF\Handler::class,
	'icms_ipf_Highlighter' => \ImpressCMS\Core\IPF\Highlighter::class,
	'icms_ipf_Metagen' => \ImpressCMS\Core\IPF\Metagen::class,
	'icms_ipf_Object' => \ImpressCMS\Core\IPF\AbstractModel::class,
	'icms_ipf_Tree' => \ImpressCMS\Core\IPF\ObjectTree::class,
	'icms_member_group_membership_Handler' => \ImpressCMS\Core\Member\Group\GroupMembershipHandler::class,
	'icms_member_group_membership_Object' => \ImpressCMS\Core\Member\Group\GroupMembershipModel::class,
	'icms_member_group_Handler' => \ImpressCMS\Core\Member\Group\GroupHandler::class,
	'icms_member_group_Object'=>\ImpressCMS\Core\Member\Group\GroupModel::class,
	'icms_member_groupperm_Handler'=>\ImpressCMS\Core\Member\Group\GroupPermHandler::class,
	'icms_member_groupperm_Object'=>\ImpressCMS\Core\Member\Group\GroupPermModel::class,
	'icms_member_rank_Handler' => \ImpressCMS\Core\Member\Rank\UserRankHandler::class,
	'icms_member_rank_Object' => \ImpressCMS\Core\Member\UserRankModel::class,
	'icms_member_user_Handler' => \ImpressCMS\Core\Member\UserHandler::class,
	'icms_member_user_Object' => \ImpressCMS\Core\Member\UserModel::class,
	'icms_member_Handler' => \ImpressCMS\Core\Member\Member::class,
	'icms_messaging_EmailHandler' => \ImpressCMS\Core\Messaging\Mailer::class,
	'icms_messaging_Handler' => \ImpressCMS\Core\Messaging\MailHandler::class,
	'icms_module_Handler' => \ImpressCMS\Core\Module\ModuleHandler::class,
	'icms_module_Object' => \ImpressCMS\Core\Module\ModuleModel::class,
	'icms_plugins_EditorHandler' => \ImpressCMS\Core\Plugins\EditorHandler::class,
	'icms\\plugins\\EditorInterface' => \ImpressCMS\Core\Plugins\EditorInterface::class,
	'icms_plugins_Handler' => \ImpressCMS\Core\Plugins\PluginHandler::class,
	'icms_plugins_Object' => \ImpressCMS\Core\Plugins\PluginModel::class,
	'icms_preload_Handler' => \ImpressCMS\Core\Preload\EventsPreloader::class,
	'icms_preload_Item' => \ImpressCMS\Core\Preload\AbstractPreloadItem::class,
	'icms_preload_LibrariesHandler' => \ImpressCMS\Core\Preload\LibrariesPreloader::class,
	'icms_properties_Handler' => \ImpressCMS\Core\Properties\AbstractProperties::class,
	'icms_sys_autotasks_ISystem' => \ImpressCMS\Core\Sys\Autotasks\AutotaskSystemInterface::class,
	'icms_sys_autotasks_System' => \ImpressCMS\Core\Sys\Autotasks\AbstractAutotaskSystem::class,
	'icms_view_block_position_Handler' => \ImpressCMS\Core\View\Block\ViewBlockPositionHandler::class,
	'icms_view_block_position_Object' => \ImpressCMS\Core\View\Block\ViewBlockPositionModel::class,
	'icms_view_block_Handler' => \ImpressCMS\Core\View\Block\ViewBlockHandler::class,
	'icms_view_block_Object' => \ImpressCMS\Core\View\Block\ViewBlockModel::class,
	'icms_view_template_file_Handler' => \ImpressCMS\Core\View\Template\TemplateFileHandler::class,
	'icms_view_template_file_Object' => \ImpressCMS\Core\View\Template\TemplateFileModel::class,
	'icms_view_template_set_Handler' => \ImpressCMS\Core\View\Template\TemplateSetHandler::class,
	'icms_view_template_set_Object' => \ImpressCMS\Core\View\Template\TemplateSetModel::class,
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