<?php
// $Id: upgrade.php 3740 2008-07-20 15:48:52Z pesian_stranger $

define( "_XOOPS_UPGRADE", "ImpressCMS Upgrade" );
define( "_CHECKING_APPLIED", "Checking applied upgrades:" );
define( "_SET_FILES_WRITABLE", "Make the following files writable before proceeding:" );
define( "_NO_NEED_UPGRADE", "No upgrade necessary. Please remove this folder from your server" );
define( "_SYS_NEED_UPGRADE", "In order to Complete the upgrade proccess, please click here, and upgrade system module." );
define( "_NEED_UPGRADE", "Upgrade needed" );
define( "_PROCEED_UPGRADE", "Proceed to upgrade" );
define( "_PERFORMING_UPGRADE", "Performing %s upgrade" );

define( "_USER_LOGIN", "User login" );

define( "_MANUAL_INSTRUCTIONS", "Manual installation instructions" );

// %s is filename
define( "_FAILED_PATCH", "Failed to patch %s" );
define( "_APPLY_NEXT", "Apply next upgrade (%s)" );
define( "_COPY_RED_LINES", "Copy the following red lines to %s" );

define( "_FINISH", "Finish" );
define( "_RELOAD", "Reload" );

define('_UPGRADE_CHARSET', _CHARSET);
define( "LEGEND_DATABASE", "Database Character Set" );
define( "DB_COLLATION_LABEL", "Database character set and collation" );
define( "DB_COLLATION_HELP",  "MySQL includes character set support that enables you to store data using a variety of character sets and perform comparisons according to a variety of collations.<br />We <b>STRONGLY</b> recommend you to use '<b>UTF-8</b>' as default.");

define("_UPGRADE_PRIVPOLICY", "<p>This privacy policy sets out how [website name] uses and protects any information that you give [website name] when you use this website. [website name] is committed to ensuring that your privacy is protected. Should we ask you to provide certain information by which you can be identified when using this website, then you can be assured that it will only be used in accordance with this privacy statement. [website name] may change this policy from time to time by updating this page. You should check this page from time to time to ensure that you are happy with any changes.
</p><p>
This policy is effective from [date].
</p>
<h2>What we collect</h2>
<p>
We may collect the following information:
<ul>
<li>name and job title</li>
<li>contact information including email address</li>
<li>demographic information such as postcode, preferences and interests</li>
<li>other information relevant to customer surveys and/or offers</li></ul>
</p>
<h2>What we do with the information we gather</h2>
<p>
We require this information to understand your needs and provide you with a better service, and in particular for the following reasons:
<ul>
<li>Internal record keeping.</li>
<li>We may use the information to improve our products and services.</li>
<li>We may periodically send promotional email about new products, special offers or other information which we think you may find interesting using the email address which you have provided.</li>
<li>From time to time, we may also use your information to contact you for market research purposes. We may contact you by email.</li>
<li>We may use the information to customise the website according to your interests.</li></ul>
</p>
<h2>Security</h2>
<p>
We are committed to ensuring that your information is secure. In order to prevent unauthorised access or disclosure we have put in place suitable physical, electronic and managerial procedures to safeguard and secure the information we collect online.
</p>
<h2>How we use cookies</h2>
<p>
A cookie is a small file which asks permission to be placed on your computer's hard drive. Once you agree, the file is added and the cookie helps analyse web traffic or lets you know when you visit a particular site. Cookies allow web applications to respond to you as an individual. The web application can tailor its operations to your needs, likes and dislikes by gathering and remembering information about your preferences.
</p><p>
We use traffic log cookies to identify which pages are being used & for authenticating you as a registered member. This helps us analyse data about web page traffic and improve our website in order to tailor it to customer needs. We only use this information for statistical analysis purposes and then the data is removed from the system. Overall, cookies help us provide you with a better website, by enabling us to monitor which pages you find useful and which you do not. A cookie in no way gives us access to your computer or any information about you, other than the data you choose to share with us.
</p><p>
You can choose to accept or decline cookies. Most web browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies if you prefer. This may prevent you from taking full advantage of the website including registration and logging in.
</p>
<h2>Links to other websites</h2>
<p>
Our website may contain links to enable you to visit other websites of interest easily. However, once you have used these links to leave our site, you should note that we do not have any control over that other website. Therefore, we cannot be responsible for the protection and privacy of any information which you provide whilst visiting such sites and such sites are not governed by this privacy statement. You should exercise caution and look at the privacy statement applicable to the website in question.
</p>
<h2>Controlling your personal information</h2>
<p>
You may choose to restrict the collection or use of your personal information in the following ways:
<ul>
<li>whenever you are asked to fill in a form on the website, look for the box that you can click to indicate that you do not want the information to be used by anybody for direct marketing purposes</li>
<li>if you have previously agreed to us using your personal information for direct marketing purposes, you may change your mind at any time by writing to or emailing us at [email address]</li></ul>
</p><p>
We will not sell, distribute or lease your personal information to third parties unless we have your permission or are required by law to do so. We may use your personal information to send you promotional information about third parties which we think you may find interesting if you tell us that you wish this to happen. You may request details of personal information which we hold about you under the Data Protection Act 1998. A small fee will be payable. If you would like a copy of the information held on you please write to [address].
</p><p>
If you believe that any information we are holding on you is incorrect or incomplete, please write to or email us as soon as possible, at the above address. We will promptly correct any information found to be incorrect.
</p>");
define( "ERR_COULD_NOT_WRITE_MAINFILE", "Error writing content to mainfile.php, write the content into mainfile.php manually." );
define( "DB_SALT_LABEL", "Password Salt Key" );	// L98
define( "DB_SALT_HELP",  "This salt key will be appended to passwords in function icms_encryptPass(), and is used to create a totally unique secure password.<BR />Do Not change this key once your site is live, doing so will render ALL passwords invalid. If you are unsure, just keep the default"); // L97
?>