<?php
// $Id$
define("_PROFILE_MI_NAME", "Extended Profiles");
define("_PROFILE_MI_DESC", "Module for managing custom user profile fields");

//Main menu links
define("_PROFILE_MI_EDITACCOUNT", "Edit Account");
define("_PROFILE_MI_CHANGEPASS", "Change Password");
define("_PROFILE_MI_CHANGEMAIL", "Change Email");

//Admin links
define("_PROFILE_MI_INDEX", "Index");
define("_PROFILE_MI_CATEGORIES", "Categories");
define("_PROFILE_MI_FIELDS", "Fields");
define("_PROFILE_MI_USERS", "Users");
define("_PROFILE_MI_STEPS", "Registration Steps");
define("_PROFILE_MI_PERMISSIONS", "Permissions");
define("_PROFILE_MI_FINDUSER", "Find Users");

//User Profile Category
define("_PROFILE_MI_CATEGORY_TITLE", "User Profile");
define("_PROFILE_MI_CATEGORY_DESC", "For those user fields");

//User Profile Fields
define("_PROFILE_MI_AIM_TITLE", "AIM");
define("_PROFILE_MI_AIM_DESCRIPTION", "America Online Instant Messenger Client ID");
define("_PROFILE_MI_ICQ_TITLE", "ICQ");
define("_PROFILE_MI_ICQ_DESCRIPTION", "ICQ Instant Messenger ID");
define("_PROFILE_MI_YIM_TITLE", "YIM");
define("_PROFILE_MI_YIM_DESCRIPTION", "Yahoo! Instant Messenger ID");
define("_PROFILE_MI_MSN_TITLE", "MSN");
define("_PROFILE_MI_MSN_DESCRIPTION", "Microsoft Messenger ID");
define("_PROFILE_MI_FROM_TITLE", "Location");
define("_PROFILE_MI_FROM_DESCRIPTION", "");
define("_PROFILE_MI_SIG_TITLE", "Signature");
define("_PROFILE_MI_SIG_DESCRIPTION", "Here, you can write a signature that can be displayed in your forum posts, comments etc.");
define("_PROFILE_MI_VIEWEMAIL_TITLE", "Allow other users to view my email address");
define("_PROFILE_MI_BIO_TITLE", "Extra Info");
define("_PROFILE_MI_BIO_DESCRIPTION", "");
define("_PROFILE_MI_INTEREST_TITLE", "Interests");
define("_PROFILE_MI_INTEREST_DESCRIPTION", "");
define("_PROFILE_MI_OCCUPATION_TITLE", "Occupation");
define("_PROFILE_MI_OCCUPATION_DESCRIPTION", "");
define("_PROFILE_MI_URL_TITLE", "Website");
define("_PROFILE_MI_URL_DESCRIPTION", "");
define("_PROFILE_MI_NEWEMAIL_TITLE", "New Email");
define("_PROFILE_MI_NEWEMAIL_DESCRIPTION", "Variable for storing a proposed new email address until confirmation comes from a mail sent to the old one. See modules/profile/changemail.php");

//Configuration categories
define("_PROFILE_MI_CAT_SETTINGS", "General Settings");
define("_PROFILE_MI_CAT_SETTINGS_DSC", "");
define("_PROFILE_MI_CAT_USER", "User Settings");
define("_PROFILE_MI_CAT_USER_DSC", "");

//Configuration items
define("_PROFILE_MI_PROFILE_SEARCH", "Show latest submissals by user on user profile");
define("_PROFILE_MI_MAX_UNAME", "Maximum Username Length");
define("_PROFILE_MI_MAX_UNAME_DESC", "This indicates the maximum number of characters, a username may have");
define("_PROFILE_MI_MIN_UNAME", "Minimum Username Length");
define("_PROFILE_MI_MIN_UNAME_DESC", "This indicates the minimum number of characters, a username must have");
define("_PROFILE_MI_DISPLAY_DISCLAIMER", "Display Disclaimer");
define("_PROFILE_MI_DISPLAY_DISCLAIMER_DESC", "If enabled, a disclaimer will be shown on the registration form");
define("_PROFILE_MI_DISCLAIMER", "Disclaimer Text");
define("_PROFILE_MI_DISCLAIMER_DESC", "This text will be shown on the registration form, if enabled above");
define("_PROFILE_MI_BAD_UNAMES", "Enter names that should not be selected as username or displayname");
define("_PROFILE_MI_BAD_UNAMES_DESC", "Separate each with a <b>|</b>, case insensitive, regex enabled.");
define("_PROFILE_MI_BAD_EMAILS", "Enter emails that should not be selected");
define("_PROFILE_MI_BAD_EMAILS_DESC", "Separate each with a <b>|</b>, case insensitive, regex enabled.");
define("_PROFILE_MI_MINPASS", "Minimum password length");
define("_PROFILE_MI_NEWUNOTIFY", "Notify by mail when a new user is registered?");
define("_PROFILE_MI_NOTIFYTO", "Select group to which new user notification mail will be sent");
define("_PROFILE_MI_ACTVTYPE", "Select activation type of newly registered users");
define("_PROFILE_MI_USERACTV","Requires activation by user (recommended)");
define("_PROFILE_MI_AUTOACTV","Activate automatically");
define("_PROFILE_MI_ADMINACTV","Activation by administrators");
define("_PROFILE_MI_ACTVGROUP", "Select group to which activation mail will be sent");
define("_PROFILE_MI_ACTVGROUP_DESC", "Valid only when 'Activation by administrators' is selected");
define("_PROFILE_MI_UNAMELVL","Select the level of strictness for username filtering");
define("_PROFILE_MI_STRICT","Strict (only alphabets and numbers)");
define("_PROFILE_MI_MEDIUM","Medium");
define("_PROFILE_MI_LIGHT","Light (recommended for multi-byte chars)");
define("_PROFILE_MI_ALLOWREG", "Allow new user registration?");
define("_PROFILE_MI_ALLOWREG_DESC", "Select yes to accept new user registration");
define("_PROFILE_MI_SELFDELETE", "Allow users to delete own account?");
define("_PROFILE_MI_SELFDELETE_DESC", "");
define("_PROFILE_MI_ALLOWCHGMAIL", "Allow users to change email address?");
define("_PROFILE_MI_ALLOWCHGMAIL_DESC", "");
define("_PROFILE_MI_SHOWEMPTY", "Show empty fields");
define("_PROFILE_MI_SHOWEMPTY_DESC", "If set to 'no', fields without a value will not show up on user profiles");

//Pages
define("_PROFILE_MI_PAGE_INFO", "User Info");
define("_PROFILE_MI_PAGE_EDIT", "Edit User");
define("_PROFILE_MI_PAGE_SEARCH", "Search");

//blocks
define("_MI_SPROFILE_BLOCK_NEW_MEMBERS", "New members");
define("_MI_SPROFILE_BLOCK_NEW_MEMBERS_DSC", "Recently subscribed members");

define("_MI_SPROFILE_PERPAGE", "Users Per Page");
define("_MI_SPROFILE_PERPAGE_DSC", "");
define("_MI_SPROFILE_ALL", "All");

define("_PROFILE_MI_DISPNAME", "Name to display on index page");
define("_PROFILE_MI_DISPNAME_DESC", "");
define("_PROFILE_MI_NICKNAME", "User name");
define("_PROFILE_MI_REALNAME", "Real name");
define("_PROFILE_MI_BOTH", "Both");

define("_PROFILE_MI_AVATAR_INDEX", "Display Avatar in users list");
define("_PROFILE_MI_AVATAR_INDEX_DESC", "");
define("_PROFILE_MI_AVATAR_HEIGHT", "Avatar height in users list");
define("_PROFILE_MI_AVATAR_HEIGHT_DESC", "");
define("_PROFILE_MI_AVATAR_WIDTH", "Avatar width in users list");
define("_PROFILE_MI_AVATAR_WIDTH_DESC", "");

define("_PROFILE_MI_GROUP_VIEW_3", "Anonymous users can view");
define("_PROFILE_MI_GROUP_VIEW_DSC", "");
define("_PROFILE_MI_GROUP_VIEW_2", "Registered users can view");

$member_handler = &xoops_gethandler('member');
$group_list = &$member_handler->getGroupList();
foreach ($group_list as $key=>$group) {
	if($key > 3){
		define("_PROFILE_MI_GROUP_VIEW_".$key, $group." users can view");
	}
}
?>