<?php

use Imponeer\Database\Criteria\CriteriaCompo;
use Imponeer\Database\Criteria\CriteriaElement;
use Imponeer\Database\Criteria\CriteriaItem;
use ImpressCMS\Core\Autoloader;
use ImpressCMS\Core\Data\Export\Exporter;
use ImpressCMS\Core\Data\Export\ExportRenderer;
use ImpressCMS\Core\Data\Feeds\Rss;
use ImpressCMS\Core\Data\Feeds\Simplerss;
use ImpressCMS\Core\Database\DatabaseConnection;
use ImpressCMS\Core\Database\DatabaseConnectionFactory;
use ImpressCMS\Core\Database\DatabaseConnectionInterface;
use ImpressCMS\Core\Database\DatabaseUtilityInterface;
use ImpressCMS\Core\Database\Legacy\Updater\DatabaseUpdater;
use ImpressCMS\Core\Database\Legacy\Updater\TableUpdater;
use ImpressCMS\Core\Database\LegacyDatabaseConnectionInterface;
use ImpressCMS\Core\Database\Mysql\DatabaseUtility;
use ImpressCMS\Core\DataFilter;
use ImpressCMS\Core\Debug;
use ImpressCMS\Core\Event;
use ImpressCMS\Core\Extensions\Autotasks\AbstractAutotaskSystem;
use ImpressCMS\Core\Extensions\Autotasks\AutotaskSystemInterface;
use ImpressCMS\Core\Extensions\Editors\EditorInterface;
use ImpressCMS\Core\Extensions\Editors\EditorsRegistry;
use ImpressCMS\Core\Extensions\Plugins\Plugin;
use ImpressCMS\Core\Extensions\Plugins\PluginRegistry;
use ImpressCMS\Core\Extensions\Preload\AbstractPreloadItem;
use ImpressCMS\Core\Extensions\Preload\EventsPreloader;
use ImpressCMS\Core\Extensions\Preload\LibrariesPreloader;
use ImpressCMS\Core\Facades\Config;
use ImpressCMS\Core\Facades\Member;
use ImpressCMS\Core\File\Downloader;
use ImpressCMS\Core\File\Filesystem;
use ImpressCMS\Core\File\MediaUploader;
use ImpressCMS\Core\File\TarDownloader;
use ImpressCMS\Core\File\ZipDownloader;
use ImpressCMS\Core\HTMLFilter;
use ImpressCMS\Core\Logger;
use ImpressCMS\Core\Message;
use ImpressCMS\Core\Messaging\Mailer;
use ImpressCMS\Core\Messaging\MessageSender;
use ImpressCMS\Core\Metagen;
use ImpressCMS\Core\ModelController;
use ImpressCMS\Core\Models\AbstractExtendedHandler;
use ImpressCMS\Core\Models\AbstractExtendedModel;
use ImpressCMS\Core\Models\AbstractHandler;
use ImpressCMS\Core\Models\AbstractModel;
use ImpressCMS\Core\Models\AbstractSEOExtendedModel;
use ImpressCMS\Core\Models\Avatar;
use ImpressCMS\Core\Models\AvatarHandler;
use ImpressCMS\Core\Models\Block;
use ImpressCMS\Core\Models\BlockHandler;
use ImpressCMS\Core\Models\BlockPosition;
use ImpressCMS\Core\Models\BlockPositionHandler;
use ImpressCMS\Core\Models\CategoryHandler;
use ImpressCMS\Core\Models\Comment;
use ImpressCMS\Core\Models\CommentHandler;
use ImpressCMS\Core\Models\ConfigCategory;
use ImpressCMS\Core\Models\ConfigCategoryHandler;
use ImpressCMS\Core\Models\ConfigItem;
use ImpressCMS\Core\Models\ConfigItemHandler;
use ImpressCMS\Core\Models\ConfigOption;
use ImpressCMS\Core\Models\ConfigOptionHandler;
use ImpressCMS\Core\Models\File;
use ImpressCMS\Core\Models\FileHandler;
use ImpressCMS\Core\Models\Group;
use ImpressCMS\Core\Models\GroupHandler;
use ImpressCMS\Core\Models\GroupMembership;
use ImpressCMS\Core\Models\GroupMembershipHandler;
use ImpressCMS\Core\Models\GroupPerm;
use ImpressCMS\Core\Models\GroupPermHandler;
use ImpressCMS\Core\Models\Image;
use ImpressCMS\Core\Models\ImageBody;
use ImpressCMS\Core\Models\ImageBodyHandler;
use ImpressCMS\Core\Models\ImageCategory;
use ImpressCMS\Core\Models\ImageCategoryHandler;
use ImpressCMS\Core\Models\ImageHandler;
use ImpressCMS\Core\Models\ImageSet;
use ImpressCMS\Core\Models\ImageSetHandler;
use ImpressCMS\Core\Models\Module;
use ImpressCMS\Core\Models\ModuleHandler;
use ImpressCMS\Core\Models\Notification;
use ImpressCMS\Core\Models\NotificationHandler;
use ImpressCMS\Core\Models\OnlineHandler;
use ImpressCMS\Core\Models\Page;
use ImpressCMS\Core\Models\PageHandler;
use ImpressCMS\Core\Models\PrivateMessage;
use ImpressCMS\Core\Models\PrivateMessageHandler;
use ImpressCMS\Core\Models\TemplateFile;
use ImpressCMS\Core\Models\TemplateFileHandler;
use ImpressCMS\Core\Models\TemplateSet;
use ImpressCMS\Core\Models\TemplateSetHandler;
use ImpressCMS\Core\Models\UrlLink;
use ImpressCMS\Core\Models\UrlLinkHandler;
use ImpressCMS\Core\Models\UserHandler;
use ImpressCMS\Core\Models\UserRank;
use ImpressCMS\Core\Models\UserRankHandler;
use ImpressCMS\Core\ObjectRegistry;
use ImpressCMS\Core\ObjectTree;
use ImpressCMS\Core\Properties\AbstractProperties;
use ImpressCMS\Core\Security\Password;
use ImpressCMS\Core\Security\PermissionsManager;
use ImpressCMS\Core\Security\RequestSecurity;
use ImpressCMS\Core\Security\StopSpammer;
use ImpressCMS\Core\Utils;
use ImpressCMS\Core\VersionChecker;
use ImpressCMS\Core\View\Form\AbstractForm;
use ImpressCMS\Core\View\Form\AbstractFormElement;
use ImpressCMS\Core\View\Form\Elements\ButtonElement;
use ImpressCMS\Core\View\Form\Elements\Captcha\ImageMode;
use ImpressCMS\Core\View\Form\Elements\Captcha\ImageRenderer;
use ImpressCMS\Core\View\Form\Elements\Captcha\TextMode;
use ImpressCMS\Core\View\Form\Elements\CaptchaElement;
use ImpressCMS\Core\View\Form\Elements\ColorPickerElement;
use ImpressCMS\Core\View\Form\Elements\DHTMLTextAreaElement;
use ImpressCMS\Core\View\Form\Elements\EditorElement;
use ImpressCMS\Core\View\Form\Elements\GroupPermissionElement;
use ImpressCMS\Core\View\Form\Elements\HiddenElement;
use ImpressCMS\Core\View\Form\Elements\HiddenTokenElement;
use ImpressCMS\Core\View\Form\Elements\LabelElement;
use ImpressCMS\Core\View\Form\Elements\PasswordElement;
use ImpressCMS\Core\View\Form\Elements\RadioYesNoElement;
use ImpressCMS\Core\View\Form\Elements\Select\CountryElement;
use ImpressCMS\Core\View\Form\Elements\Select\GroupElement;
use ImpressCMS\Core\View\Form\Elements\Select\LangElement;
use ImpressCMS\Core\View\Form\Elements\Select\MatchOptionElement;
use ImpressCMS\Core\View\Form\Elements\Select\ThemeElement;
use ImpressCMS\Core\View\Form\Elements\Select\TimeZoneElement;
use ImpressCMS\Core\View\Form\Elements\TextAreaElement;
use ImpressCMS\Core\View\Form\Elements\TrayElement;
use ImpressCMS\Core\View\Form\GroupPermissionForm;
use ImpressCMS\Core\View\Form\TableForm;
use ImpressCMS\Core\View\Form\ThemeForm;
use ImpressCMS\Core\View\Highlighter;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\AutocompleteElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\BlockOptionsElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\CheckboxElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\DateElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\DateTimeElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\FileElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\FileUploadElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\FormSectionElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\ImageElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\ImageUploadElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\LanguageElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\PageElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\ParentCategoryElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\PasswordTrayElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\RadioElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\RichfileElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\SelectElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\SelectMultiElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\SignatureElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\SourceElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\TextElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\TimeElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\UploadElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\URLLinkElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\UserElement;
use ImpressCMS\Core\View\ModelLinkedForm\Elements\YesNoElement;
use ImpressCMS\Core\View\ModelLinkedForm\Form;
use ImpressCMS\Core\View\ModelLinkedForm\SecureForm;
use ImpressCMS\Core\View\PageBuilder;
use ImpressCMS\Core\View\PageNav;
use ImpressCMS\Core\View\Renderer\BreadcrumbRenderer;
use ImpressCMS\Core\View\Renderer\CommentRenderer;
use ImpressCMS\Core\View\Renderer\ModelViewRenderer;
use ImpressCMS\Core\View\Renderer\ModuleAboutRenderer;
use ImpressCMS\Core\View\Renderer\PrinterFriendlyViewRenderer;
use ImpressCMS\Core\View\Table\Column;
use ImpressCMS\Core\View\Table\Row;
use ImpressCMS\Core\View\Table\Table;
use ImpressCMS\Core\View\Table\TableTree;
use ImpressCMS\Core\View\Template;
use ImpressCMS\Core\View\Theme\ThemeComponent;
use ImpressCMS\Core\View\Theme\ThemeFactory;
use ImpressCMS\Core\View\ViewTree;

return [
	'icms_config_category_Handler' => ConfigCategoryHandler::class,
	'icms_config_category_Object' => ConfigCategory::class,
	'icms_config_item_Handler' => ConfigItemHandler::class,
	'icms_config_item_Object' => ConfigItem::class,
	'icms_config_option_Handler' => ConfigOptionHandler::class,
	'icms_config_option_Object' => ConfigOption::class,
	'icms_config_Handler' => Config::class,
	'icms_core_DataFilter' => DataFilter::class,
	'icms_core_Debug' => Debug::class,
	'icms_core_Filesystem' => Filesystem::class,
	'icms_core_HTMLFilter' => HTMLFilter::class,
	'icms_core_Logger' => Logger::class,
	'icms_core_Message' => Message::class,
	'icms_core_Object' => AbstractModel::class,
	'icms_core_ObjectHandler' => AbstractHandler::class,
	'icms_core_OnlineHandler' => OnlineHandler::class,
	'icms_core_Password' => Password::class,
	'icms_core_Security' => RequestSecurity::class,
	'icms_core_StopSpammer' => StopSpammer::class,
	'icms_core_Textsanitizer' => \ImpressCMS\Core\Textsanitizer::class,
	'icms_core_Versionchecker' => VersionChecker::class,
	'icms_data_avatar_Handler' => AvatarHandler::class,
	'icms_data_avatar_Object' => Avatar::class,
	'icms_data_comment_Handler' => CommentHandler::class,
	'icms_data_comment_Object' => Comment::class,
	'icms_data_comment_Renderer' => CommentRenderer::class,
	'icms_data_file_Handler' => FileHandler::class,
	'icms_data_file_Object' => File::class,
	'icms_data_notification_Handler' => NotificationHandler::class,
	'icms_data_notification_Object' => Notification::class,
	'icms_data_page_Handler' => PageHandler::class,
	'icms_data_page_Object' => Page::class,
	'icms_data_privmessage_Handler' => PrivateMessageHandler::class,
	'icms_data_privmessage_Object' => PrivateMessage::class,
	'icms_data_urllink_Handler' => UrlLinkHandler::class,
	'icms_data_urllink_Object' => UrlLink::class,
	'ImpressCMS\\Core\\Database\\Criteria\\CriteriaCompo' => CriteriaCompo::class,
	'ImpressCMS\\Core\\Database\\Criteria\\CriteriaElement' => CriteriaElement::class,
	'ImpressCMS\\Core\\Database\\Criteria\\CriteriaItem' =>  CriteriaItem::class,
	'icms_db_criteria_Compo' => CriteriaCompo::class,
	'icms_db_criteria_Element' =>  CriteriaElement::class,
	'icms_db_criteria_Item' =>  CriteriaItem::class,
	'icms_db_legacy_mysql_Utility' => \ImpressCMS\Core\Database\Legacy\Mysql\DatabaseUtility::class,
	'icms_db_legacy_updater_Handler' => DatabaseUpdater::class,
	'icms_db_legacy_updater_Table' => TableUpdater::class,
	'icms_db_legacy_Factory' => \ImpressCMS\Core\Database\Legacy\DatabaseConnectionFactory::class,
	'icms_db_mysql_Utility' => DatabaseUtility::class,
	'icms_db_Connection' => DatabaseConnection::class,
	'icms_db_Factory' => DatabaseConnectionFactory::class,
	'icms_db_IConnection' => DatabaseConnectionInterface::class,
	'icms_db_legacy_IDatabase' => LegacyDatabaseConnectionInterface::class,
	'icms_db_IUtility' => DatabaseUtilityInterface::class,
	'icms_feeds_Rss' => Rss::class,
	'icms_feeds_Simplerss' => Simplerss::class,
	'icms_file_DownloadHandler' => Downloader::class,
	'icms_file_MediaUploadHandler' => MediaUploader::class,
	'icms_file_TarDownloader' => TarDownloader::class,
	'icms_file_ZipDownloader' => ZipDownloader::class,
	'icms_form_elements_captcha_Image' => ImageMode::class,
	'icms_form_elements_captcha_ImageHandler' => ImageRenderer::class,
	'icms_form_elements_captcha_Object' => \ImpressCMS\Core\View\Form\Elements\Captcha\Image::class,
	'icms_form_elements_captcha_Text' => TextMode::class,
	'icms_form_elements_select_Country' => CountryElement::class,
	'icms_form_elements_select_Editor' => \ImpressCMS\Core\View\Form\Elements\Select\EditorElement::class,
	'icms_form_elements_select_Group' => GroupElement::class,
	'icms_form_elements_select_Image' => \ImpressCMS\Core\View\Form\Elements\Select\ImageElement::class,
	'icms_form_elements_select_Lang' => LangElement::class,
	'icms_form_elements_select_Matchoption' => MatchOptionElement::class,
	'icms_form_elements_select_Theme' => ThemeElement::class,
	'icms_form_elements_select_Timezone' => TimeZoneElement::class,
	'icms_form_elements_select_User' => \ImpressCMS\Core\View\Form\Elements\Select\UserElement::class,
	'icms_form_elements_Button' => ButtonElement::class,
	'icms_form_elements_Captcha' => CaptchaElement::class,
	'icms_form_elements_Checkbox' => \ImpressCMS\Core\View\Form\Elements\CheckboxElement::class,
	'icms_form_elements_Colorpicker' => ColorPickerElement::class,
	'icms_form_elements_Date' => \ImpressCMS\Core\View\Form\Elements\DateElement::class,
	'icms_form_elements_Datetime' => \ImpressCMS\Core\View\Form\Elements\DateTimeElement::class,
	'icms_form_elements_Dhtmltextarea' => DHTMLTextAreaElement::class,
	'icms_form_elements_Editor' => EditorElement::class,
	'icms_form_elements_File' => \ImpressCMS\Core\View\Form\Elements\FileElement::class,
	'icms_form_elements_Groupperm' => GroupPermissionElement::class,
	'icms_form_elements_Hidden' => HiddenElement::class,
	'icms_form_elements_Hiddentoken' => HiddenTokenElement::class,
	'icms_form_elements_Label' => LabelElement::class,
	'icms_form_elements_Password' => PasswordElement::class,
	'icms_form_elements_Radio' => \ImpressCMS\Core\View\Form\Elements\RadioElement::class,
	'icms_form_elements_Radioyn' => RadioYesNoElement::class,
	'icms_form_elements_Select' => \ImpressCMS\Core\View\Form\Elements\SelectElement::class,
	'icms_form_elements_Text' => \ImpressCMS\Core\View\Form\Elements\TextElement::class,
	'icms_form_elements_Textarea' => TextAreaElement::class,
	'icms_form_elements_Tray' => TrayElement::class,
	'icms_form_Base' => AbstractForm::class,
	'icms_form_Element' => AbstractFormElement::class,
	'icms_form_Groupperm' => GroupPermissionForm::class,
	'icms_form_Table' => TableForm::class,
	'icms_form_Theme' => ThemeForm::class,
	'icms_image_body_Handler' => ImageBodyHandler::class,
	'icms_image_body_Object' => ImageBody::class,
	'icms_image_category_Handler' => ImageCategoryHandler::class,
	'icms_image_category_Object' => ImageCategory::class,
	'icms_image_set_Handler' => ImageSetHandler::class,
	'icms_image_set_Object' => ImageSet::class,
	'icms_image_Handler' => ImageHandler::class,
	'icms_image_Object' => Image::class,
	'icms_ipf_category_Handler' => CategoryHandler::class,
	'icms_ipf_category_Object' => \ImpressCMS\Core\Models\Category::class,
	'icms_ipf_export_Handler' => Exporter::class,
	'icms_ipf_export_Renderer' => ExportRenderer::class,
	'icms_ipf_form_elements_Autocomplete' => AutocompleteElement::class,
	'icms_ipf_form_elements_Blockoptions' => BlockOptionsElement::class,
	'icms_ipf_form_elements_Checkbox' => CheckboxElement::class,
	'icms_ipf_form_elements_Date' => DateElement::class,
	'icms_ipf_form_elements_Datetime' => DateTimeElement::class,
	'icms_ipf_form_elements_File' => FileElement::class,
	'icms_ipf_form_elements_Fileupload' => FileUploadElement::class,
	'icms_ipf_form_elements_Image' => ImageElement::class,
	'icms_ipf_form_elements_Imageupload' => ImageUploadElement::class,
	'icms_ipf_form_elements_Language' => LanguageElement::class,
	'icms_ipf_form_elements_Page' => PageElement::class,
	'icms_ipf_form_elements_Parentcategory' => ParentCategoryElement::class,
	'icms_ipf_form_elements_Passwordtray' => PasswordTrayElement::class,
	'icms_ipf_form_elements_Radio' => RadioElement::class,
	'icms_ipf_form_elements_Richfile' => RichfileElement::class,
	'icms_ipf_form_elements_Section' => FormSectionElement::class,
	'icms_ipf_form_elements_Select' => SelectElement::class,
	'icms_ipf_form_elements_Selectmulti' => SelectMultiElement::class,
	'icms_ipf_form_elements_Signature'=> SignatureElement::class,
	'icms_ipf_form_elements_Source' => SourceElement::class,
	'icms_ipf_form_elements_Text' => TextElement::class,
	'icms_ipf_form_elements_Time' => TimeElement::class,
	'icms_ipf_form_elements_Upload' => UploadElement::class,
	'icms_ipf_form_elements_Urllink' => URLLinkElement::class,
	'icms_ipf_form_elements_User' => UserElement::class,
	'icms_ipf_form_elements_Yesno' => YesNoElement::class,
	'icms_ipf_form_Base' => Form::class,
	'icms_ipf_form_Secure' => SecureForm::class,
	'icms_ipf_member_Handler' => Member::class,
	'icms_ipf_permission_Handler' => PermissionsManager::class,
	'icms_ipf_registry_Handler' => ObjectRegistry::class,
	'icms_ipf_seo_Object' => AbstractSEOExtendedModel::class,
	'icms_ipf_view_Column' => Column::class,
	'icms_ipf_view_Row' => Row::class,
	'icms_ipf_view_Single' => ModelViewRenderer::class,
	'icms_ipf_view_Table' => Table::class,
	'icms_ipf_view_Tree' => TableTree::class,
	'icms_ipf_About' => ModuleAboutRenderer::class,
	'icms_ipf_Controller' => ModelController::class,
	'icms_ipf_Handler' => AbstractExtendedHandler::class,
	'icms_ipf_Highlighter' => Highlighter::class,
	'icms_ipf_Metagen' => Metagen::class,
	'icms_ipf_Object' => AbstractExtendedModel::class,
	'icms_ipf_Tree' => ObjectTree::class,
	'icms_member_group_membership_Handler' => GroupMembershipHandler::class,
	'icms_member_group_membership_Object' => GroupMembership::class,
	'icms_member_group_Handler' => GroupHandler::class,
	'icms_member_group_Object'=> Group::class,
	'icms_member_groupperm_Handler'=> GroupPermHandler::class,
	'icms_member_groupperm_Object'=> GroupPerm::class,
	'icms_member_rank_Handler' => UserRankHandler::class,
	'icms_member_rank_Object' => UserRank::class,
	'icms_member_user_Handler' => UserHandler::class,
	'icms_member_user_Object' => \ImpressCMS\Core\Models\User::class,
	'icms_member_Handler' => Member::class,
	'icms_messaging_EmailHandler' => Mailer::class,
	'icms_messaging_Handler' => MessageSender::class,
	'icms_module_Handler' => ModuleHandler::class,
	'icms_module_Object' => Module::class,
	'icms_plugins_EditorHandler' => EditorsRegistry::class,
	'icms\\plugins\\EditorInterface' => EditorInterface::class,
	'icms_plugins_Handler' => PluginRegistry::class,
	'icms_plugins_Object' => Plugin::class,
	'icms_preload_Handler' => EventsPreloader::class,
	'icms_preload_Item' => AbstractPreloadItem::class,
	'icms_preload_LibrariesHandler' => LibrariesPreloader::class,
	'icms_properties_Handler' => AbstractProperties::class,
	'icms_sys_autotasks_ISystem' => AutotaskSystemInterface::class,
	'icms_sys_autotasks_System' => AbstractAutotaskSystem::class,
	'icms_view_block_position_Handler' => BlockPositionHandler::class,
	'icms_view_block_position_Object' => BlockPosition::class,
	'icms_view_block_Handler' => BlockHandler::class,
	'icms_view_block_Object' => Block::class,
	'icms_view_template_file_Handler' => TemplateFileHandler::class,
	'icms_view_template_file_Object' => TemplateFile::class,
	'icms_view_template_set_Handler' => TemplateSetHandler::class,
	'icms_view_template_set_Object' => TemplateSet::class,
	'icms_view_theme_Factory' => ThemeFactory::class,
	'icms_view_theme_Object' => ThemeComponent::class,
	'icms_view_Breadcrumb' => BreadcrumbRenderer::class,
	'icms_view_PageBuilder' => PageBuilder::class,
	'icms_view_PageNav' => PageNav::class,
	'icms_view_Printerfriendly' => PrinterFriendlyViewRenderer::class,
	'icms_view_Tpl' => Template::class,
	'icms_view_Tree' => ViewTree::class,
	'icms_Autoloader' => Autoloader::class,
	'icms_Event' => Event::class,
	'icms_Utils' => Utils::class,
];