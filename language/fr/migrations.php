<?php

/**
 * Constants used for migrations
 */
define('_MIGRATION_RANK_1', 'Just popping in');
define('_MIGRATION_RANK_2', 'Not too shy to talk');
define('_MIGRATION_RANK_3', 'Quite a regular');
define('_MIGRATION_RANK_4', 'Just can not stay away');
define('_MIGRATION_RANK_5', 'Home away from home');
define('_MIGRATION_RANK_6', 'Moderator');
define('_MIGRATION_RANK_7', 'Webmaster');

define('_MIGRATION_SMILE_1', 'Very Happy');
define('_MIGRATION_SMILE_2', 'Smile');
define('_MIGRATION_SMILE_3', 'Sad');
define('_MIGRATION_SMILE_4', 'Surprised');
define('_MIGRATION_SMILE_5', 'Confused');
define('_MIGRATION_SMILE_6',  'Cool');
define('_MIGRATION_SMILE_7', 'Laughing');
define('_MIGRATION_SMILE_8', 'Mad');
define('_MIGRATION_SMILE_9', 'Razz');
define('_MIGRATION_SMILE_10', 'Embarrassed');
define('_MIGRATION_SMILE_11', 'Crying (very sad)');
define('_MIGRATION_SMILE_12', 'Evil or Very Mad');
define('_MIGRATION_SMILE_13', 'Rolling Eyes');
define('_MIGRATION_SMILE_14', 'Wink');
define('_MIGRATION_SMILE_15', 'Another pint of beer');
define('_MIGRATION_SMILE_16', 'ToolTimes at work');
define('_MIGRATION_SMILE_17', 'I have an idea');

define('_MIGRATION_PAGE_2', 'Administration');
define('_MIGRATION_PAGE_3', 'Avatars');
define('_MIGRATION_PAGE_4', 'Banners');
define('_MIGRATION_PAGE_5', 'Blocks Admin');
define('_MIGRATION_PAGE_6', 'Block Positions');
define('_MIGRATION_PAGE_7', 'Commentaires');
define('_MIGRATION_PAGE_9', 'Find Users');
define('_MIGRATION_PAGE_10', 'Custom Tag');
define('_MIGRATION_PAGE_11', 'Groups');
define('_MIGRATION_PAGE_12', 'Gestionnaire d\'images');
define('_MIGRATION_PAGE_13', 'Mail Users');
define('_MIGRATION_PAGE_14', 'Modules Admin');
define('_MIGRATION_PAGE_15', 'Symlink Manager');
define('_MIGRATION_PAGE_16', 'Pr&eacute;f&eacute;rences');
define('_MIGRATION_PAGE_17', 'Smilies');
define('_MIGRATION_PAGE_18', 'Templates');
define('_MIGRATION_PAGE_19',  'User Ranks');
define('_MIGRATION_PAGE_20', 'User Edit');
define('_MIGRATION_PAGE_21', 'Version Checker');

define('_MIGRATION_WELCOME_ANONYMOUS', <<<EOF
This is sample text for a block. If you are the administrator please log in to view more information.

Learn more about ImpressCMS:
<ul><li>[url=https://www.impresscms.org]Project Home[/url]</li><li>[url=https://www.impresscms.org/modules/iforum/]ImpressCMS Community[/url]</li><li>[url=https://www.impresscms.org/modules/downloads/index.php]ImpressCMS Addons[/url]</li><li>[url=https://www.impresscms.org/modules/simplywiki/]ImpressCMS Wiki[/url]</li><li>[url=https://www.impresscms.org/modules/news/]ImpressCMS Blog[/url]</li></ul>
EOF
);

define('_MIGRATION_WELCOME_WEBMASTER', <<<EOF
Welcome to your new ImpressCMS powered website. If you haven't already, please delete the [b]install[/b] folder from the server and ensure that [b].env[/b] is not writeable (chmod 444).

To begin administering your new ImpressCMS powered website you can click the [b]Administration[/b] Menu link located on the left of this page.

You may want to begin by editing your website [b]Preferences[/b]: In the admin panel, hover over the [b]System[/b] dropdown and select [b]Preferences.[/b]

Afterwards you can begin adding [b]Modules[/b] and [b]Themes[/b].
Many of the available modules and themes for ImpressCMS, are available at the [url=https://www.impresscms.org/modules/downloads/]Addons[/url] section of the [url=http://www.impresscms.org]projects website[/url].

You will also need to begin using [b]Blocks[/b]. You can begin by removing this block. You can do this by navigating to System Admin > Blocks, and the selecting "Webmasters" in the [b]Groups[/b] select box. You will then be able to see the blocks available for the Webmasters group, which this block is!

For more information about working with ImpressCMS, please use the links below.
<ul><li>[url=https://www.impresscms.org/modules/simplywiki/index.php?page=English#Using_ImpressCMS]Using ImpressCMS[/url]</li><li>[url=http://www.impresscms.org/modules/simplywiki/index.php?page=English#Customizing_ImpressCMS]Customizing ImpressCMS[/url]</li><li>[url=http://www.impresscms.org/modules/simplywiki/index.php?page=English#Developing_for_ImpressCMS]Developing for ImpressCMS[/url]</li></ul>
We warmly invite you to join [url=https://www.impresscms.org/modules/iforum/]The ImpressCMS Community[/url] - Where you can make contributions, get help, help others, etc...
EOF
);

define('_MIGRATIONS_LOCAOL_STNAME', 'ImpressCMS');
define('_MIGRATIONS_LOCAL_SLOCGAN', 'Make a lasting impression');
define('_MIGRATIONS_EXT_DATE_FUNC', '0');
define('_MIGRATIONS_INSTALL_ANON', 'Anonymous');
define('_MIGRATIONS_INSTALL_L165', 'The site is currently closed for maintenance. Please come back later.');
define('_MIGRATIONS_WELCOME_MSG_CONTENT', 'Welcome {UNAME},

Your account has been successfully activated on {X_SITENAME}. As a member of our site, you will benefit from all the features reserved to registered members !

Once again, welcome to our site. Visit us often !

If you did not registered to our site, please contact us at the following address {X_ADMINMAIL}, and we will fix the situation.

-----------
Yours truly,
{X_SITENAME}
{X_SITEURL}');
define('_MIGRATIONS_INSTALL_DISCLMR', 'While the administrators and moderators of this site will attempt to remove or edit any generally objectionable material as quickly as possible, it is impossible to review every message. Therefore you acknowledge that all posts made to this site express the views and opinions of the author and not the administrators, moderators or webmaster (except for posts by these people) and hence will not be held liable.

You agree not to post any abusive, obscene, vulgar, slanderous, hateful, threatening, sexually-orientated or any other material that may violate any applicable laws. Doing so may lead to you being immediately and permanently banned (and your service provider being informed). The IP address of all posts is recorded to aid in enforcing these conditions. Creating multiple accounts for a single user is not allowed. You agree that the webmaster, administrator and moderators of this site have the right to remove, edit, move or close any topic at any time should they see fit. As a user you agree to any information you have entered above being stored in a database. While this information will not be disclosed to any third party without your consent the webmaster, administrator and moderators cannot be held responsible for any hacking attempt that may lead to the data being compromised.

This site system uses cookies to store information on your local computer. These cookies do not contain any of the information you have entered above, they serve only to improve your viewing pleasure. The email address is used only for confirming your registration details and password (and for sending new passwords should you forget your current one).

By clicking Register below you agree to be bound by these conditions.');
define('_MIGRATIONS_INSTALL_PRIVPOLICY', <<<EOF
<p>This privacy policy sets out how {X_SITENAME} uses and protects any information that you provide when you use this website. {X_SITENAME} is committed to ensuring that your privacy is protected. Should we ask you to provide certain information by which you can be identified when using this website, then you can be assured that it will only be used in accordance with this privacy statement. {X_SITENAME} may change this policy from time to time by updating this page. You should check this page from time to time to ensure that you are happy with any changes.
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
</p>
EOF
);
define('_MIGRATIONS_LOCAL_FOOTER', 'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"https://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a><br />Hosting by <a href="http://www.siteground.com/impresscms-hosting.htm?afcode=7e9aa639d30265c079823a498f5b8f15">SiteGround</a>'); //footer Link to local support site
define('_MIGRATIONS_LOCAL_SENSORTXT', '#OOPS#'); //Add local translation
define('_MIGRATIONS_DEF_LANG_TAGS', 'en,de'); //Add local translation
define('_MIGRATIONS_DEF_LANG_NAMES', 'english,german'); //Add local translation
define('_MIGRATIONS_LOCAL_LANG_NAMES', 'English,Deutsch'); //Add local translation
define('_MIGRATIONS_AM_RSSLOCALLINK_DESC', 'https://www.impresscms.org/modules/news/rss.php'); //Link to the rrs of local support site
define('_MIGRATIONS_INSTALL_WEBMASTER', 'Webmasters');
define('_MIGRATIONS_INSTALL_WEBMASTERD', 'Webmasters of this site');
define('_MIGRATIONS_INSTALL_REGUSERS', 'Registered Users');
define('_MIGRATIONS_INSTALL_REGUSERSD', 'Registered Users Group');
define('_MIGRATIONS_INSTALL_ANONUSERS', 'Anonymous Users');
define('_MIGRATIONS_INSTALL_ANONUSERSD', 'Anonymous Users Group');
define('_MIGRATIONS_WELCOME_WEBMASTER', 'Welcome Webmaster !');
define('_MIGRATIONS_WELCOME_ANONYMOUS', 'Welcome to an ImpressCMS powered website !');