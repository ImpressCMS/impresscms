<?php
// $Id: users.php 12064 2012-10-10 14:37:53Z skenow $
define('_MD_AM_USER', 'Edit Users');
define('_MD_AM_USER_DSC', 'Create, Modify or Delete registered users.');

//%%%%%%	Admin Module Name  Users 	%%%%%
//if (!defined('_AM_DBUPDATED')) {define("_AM_DBUPDATED","Database Updated Successfully!");}

define("_AM_AYSYWTDU", "Are you sure you want to delete user %s?");
define("_AM_BYTHIS", "By doing this all the info for this user will be removed permanently.");
define("_AM_YMCACF", "You must complete all required fields");
define("_AM_CNRNU", "Could not register new user.");
define("_AM_EDEUSER", "Edit/Delete Users");
define("_AM_NICKNAME", "Displayname");
define("_AM_MODIFYUSER", "Modify User");
define("_AM_DELUSER", "Delete User");
define("_AM_GO", "Go!");
define("_AM_ADDUSER", "Add User");
//define("_AM_NAME","Name");
define("_AM_EMAIL", "Email");
define("_AM_OPENID", "OpenID");
define("_AM_OPTION", "Option");
define("_AM_AVATAR", "Avatar");
define("_AM_THEME", "Theme");
define("_AM_AOUTVTEAD", "Allow other users to view this email address");
define("_AM_AOUTVTOIAD", "Allow other users to view this OpenID address");
define("_AM_URL", "URL");
define("_AM_ICQ", "ICQ");
define("_AM_AIM", "AIM");
define("_AM_YIM", "YIM");
define("_AM_MSNM", "MSNM");
define("_AM_LOCATION", "Location");
define("_AM_OCCUPATION", "Occupation");
define("_AM_INTEREST", "Interest");
define("_AM_RANK", "Rank");
define("_AM_NSRA", "No Special Rank Assigned");
define("_AM_NSRID", "No Special Ranks in Database");
define("_AM_ACCESSLEV", "Access Level");
define("_AM_SIGNATURE", "Signature");
define("_AM_PASSWORD", "Password");
define("_AM_INDICATECOF", "* indicates required fields");
define("_AM_NOTACTIVE", "This user has not been activated. Do you wish to activate this user?");
define("_AM_UPDATEUSER", "Update User");
define("_AM_USERINFO", "User Info");
define("_AM_USERID", "User ID");
define("_AM_RETYPEPD", "Retype Password");
define("_AM_CHANGEONLY", "(for changes only)");
define("_AM_USERPOST", "User Posts");
define("_AM_STORIES", "Stories");
define("_AM_COMMENTS", "Comments");
define("_AM_PTBBTSDIYT", "Push the button below to synchronize data if you think the above user posts info does not seem to indicate the actual status");
define("_AM_SYNCHRONIZE", "Synchronize");
define("_AM_USERDONEXIT", "User doesn't exist!");
define("_AM_STNPDNM", "Sorry, the new passwords do not match. Click back and try again");
define("_AM_CNGTCOM", "Could not get total comments");
define("_AM_CNGTST", "Could not get total stories");
define("_AM_CNUUSER", "Could not update user");
define("_AM_CNGUSERID", "Could not get user IDS");
define("_AM_LIST", "List");
define("_AM_NOUSERS", "No users selected");

define("_AM_CNRNU2", "The new user could not be added to groups: %s.");

define('_AM_BADPWD', 'Bad Password, Password can not contain username.');

######################## Added in 1.2 ###################################
define("_AM_LOGINNAME", "Loginname");
define("_AM_REMOVED_USERS", "Removed Users");
define("_AM_ADMIN_CAN_NOT_BE_DELETEED", "Admin user cannot be deleted");
define("_AM_USERS_DELETEED", "deleted");
define("_AM_COULD_NOT_DELETE", "Could not delete");
define("_AM_A_USER_WITH_THIS_EMAIL_ADDRESS", "A user with this email address");
define("_AM_ALREADY_EXISTS", "already exists");

// $Id: users.php 12064 2012-10-10 14:37:53Z skenow $
//%%%%%%	File Name findusers.php 	%%%%%

define("_AM_FINDUS", "Find Users");
define("_AM_REALNAME", "Real Name");
define("_AM_REGDATE", "Joined Date");
define("_AM_PM", "PM");
define("_AM_PREVIOUS", "Previous");
define("_AM_NEXT", "Next");
define("_AM_USERSFOUND", "%s user(s) found");

define("_AM_ACTUS", "Active Users: %s");
define("_AM_INACTUS", "Inactive Users: %s");
define("_AM_NOFOUND", "No Users Found");
define("_AM_UNAME", "User Name");
define("_AM_URLC", "URL contains");
define("_AM_LASTLOGMORE", "Last login is more than <span style='color:#ff0000;'>X</span> days ago");
define("_AM_LASTLOGLESS", "Last login is less than <span style='color:#ff0000;'>X</span> days ago");
define("_AM_REGMORE", "Joined date is more than <span style='color:#ff0000;'>X</span> days ago");
define("_AM_REGLESS", "Joined date is less than <span style='color:#ff0000;'>X</span> days ago");
define("_AM_POSTSMORE", "Number of Posts is greater than <span style='color:#ff0000;'>X</span>");
define("_AM_POSTSLESS", "Number of Posts is less than <span style='color:#ff0000;'>X</span>");
define("_AM_SORT", "Sort by");
define("_AM_ORDER", "Order");
define("_AM_LASTLOGIN", "Last login");
define("_AM_POSTS", "Number of posts");
define("_AM_ASC", "Ascending order");
define("_AM_DESC", "Descending order");
define("_AM_LIMIT", "Number of users per page");
define("_AM_RESULTS", "Search results");
define("_AM_SHOWMAILOK", "Accept mail");
define("_AM_MAILOK", "Only users that accept mail");
define("_AM_MAILNG", "Only users that don't accept mail");
define("_AM_SHOWTYPE", "Active");
define("_AM_ACTIVE", "Only active users");
define("_AM_INACTIVE", "Only inactive users");
define("_AM_BOTH", "All users");
define("_AM_SENDMAIL", "Send mail");
define("_AM_ADD2GROUP", "Add users to %s group");
define("_AM_GROUPS", "Groups");

######################## Added in 1.3 ###################################
//define("_AM_ACTIONS","Actions");