<?php

namespace ImpressCMS\Core\Extensions\ExtensionDescriber;

use ArrayAccess;

/**
 * Class that describes a module
 */
class ModuleInfo implements DescribedItemInfoInterface
{
	use ArrayAccessTrait;

	public string $name;
	public string $version;
	public string $description;
	public string $teammembers;
	public bool $official;
	public string $dirname;
	public string $modname;
	public string $iconsmall;
	public string $iconbig;
	public ?string $license;
	public string $image;
	public ?string $author_word;
	public ?string $date;
	public ?string $status;
	public ?string $warning;
	public ?string $status_version;
	public ?string $developer_website_url;
	public ?string $developer_website_name;
	public ?string $developer_email;
	public array $assets = [];
	public bool $hasAdmin;
	public ?string $adminindex;
	public ?string $adminmenu;
	public array $object_items = [];
	public array $tables = [];
	public bool $hasSearch;
	public array $search = [
		'file' => null,
		'func' => null,
	];
	public bool $hasComments;
	public ?string $demo_site_url;
	public ?string $demo_site_name;
	public ?string $author_email;
	public ?string $author_realname;
	public ?string $author_website_url;
	public ?string $author_website_name;
	public ?string $support_site_name;
	public ?string $support_site_url;
	public ?string $submit_bug;
	public ?string $submit_feature;
	public bool $hasNotification;
	public ?string $author_credits;
	public bool $hasMain;
	public array $templates = [];
	public ?string $maillist_announcements;
	public ?string $maillist_bugs;
	public ?string $maillist_features;
	public array $comments = [
		'itemName' => null,
		'pageName' => null,
		'extraParams' => null,
		'callbackFile' => null,
		'callback' => [
			'approve' => '',
			'update' => '',
		]
	];
	public array $config = [];
	public array $notification = [
		'lookup_file' => '',
		'lookup_func' => '',
		'category' => [
			//[ name
			// title
			// description
			// subscribe_from
			// item_name
			// allow_bookmark ]
		],
		'event' => [
			// [ mail_template
			// name
			// category
			// admin_only
			// title
			// caption
			// description
			// mail_subject ]
		],
	];
	public ?string $help;
	public ?string $credits;
	public ?string $author;
	public array $blocks = [
		// [ file
		// name
		// description
		// show_func
		// edit_func
		// options
		// template ]
	];
	public array $sub = [
		// [ name
		// url ]
	];
	public ?string $onUpdate = null;
	public ?string $onUninstall = null;
	public ?string $onInstall = null;
	public array $manual = [];
	public array $autotasks = [];
	public array $people;
}