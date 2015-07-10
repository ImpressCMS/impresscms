array (
  0 => 
  array (
    'id' => 'cphome',
    'text' => 'Admin Control Panel',
    'link' => '#',
    'menu' => 
    array (
      0 => 
      array (
        'link' => 'http://localhost:8888/admin.php',
        'title' => 'Admin Control Panel',
        'absolute' => 1,
        'small' => 'http://localhost:8888/modules/system/images/mini_cp.png',
      ),
      1 => 
      array (
        'link' => 'http://localhost:8888',
        'title' => 'Home Page',
        'absolute' => 1,
        'small' => 'http://localhost:8888/modules/system/images/home.png',
      ),
      2 => 
      array (
        'link' => 'http://localhost:8888/user.php?op=logout',
        'title' => 'Logout',
        'absolute' => 1,
        'small' => 'http://localhost:8888/modules/system/images/logout.png',
      ),
    ),
  ),
  1 => 
  array (
    'id' => 'opsystem',
    'text' => 'System',
    'link' => 'http://localhost:8888/modules/system/admin.php',
    'menu' => 
    array (
      0 => 
      array (
        'title' => 'Advertising',
        'link' => '#',
        'absolute' => 1,
        'hassubs' => 1,
        'subs' => 
        array (
          0 => 
          array (
            'title' => 'Adsenses',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=adsense',
            'icon' => 'admin/adsense/images/adsense.png',
            'small' => 'admin/adsense/images/adsense_small.png',
            'id' => 21,
          ),
        ),
        'dir' => 'system',
      ),
      1 => 
      array (
        'title' => 'Content',
        'link' => '#',
        'absolute' => 1,
        'hassubs' => 1,
        'subs' => 
        array (
          0 => 
          array (
            'title' => 'Comments',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=comments',
            'icon' => 'admin/comments/images/comments.png',
            'small' => 'admin/comments/images/comments_small.png',
            'id' => 14,
          ),
          1 => 
          array (
            'title' => 'Custom Tags',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=customtag',
            'icon' => 'admin/customtag/images/customtag.png',
            'small' => 'admin/customtag/images/customtag_small.png',
            'id' => 20,
          ),
          2 => 
          array (
            'title' => 'Symlink Manager',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=pages',
            'icon' => 'admin/pages/images/pages.png',
            'small' => 'admin/pages/images/pages_small.png',
            'id' => 19,
          ),
          3 => 
          array (
            'title' => 'Ratings',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=rating',
            'icon' => 'admin/rating/images/rating.png',
            'small' => 'admin/rating/images/rating_small.png',
            'id' => 22,
          ),
          4 => 
          array (
            'title' => 'Smilies',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=smilies',
            'icon' => 'admin/smilies/images/smilies.png',
            'small' => 'admin/smilies/images/smilies_small.png',
            'id' => 12,
          ),
          5 => 
          array (
            'title' => 'Tags',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=tags',
            'icon' => 'admin/tags/images/tags.png',
            'small' => 'admin/tags/images/tags_small.png',
            'id' => 25,
          ),
        ),
        'dir' => 'system',
      ),
      2 => 
      array (
        'title' => 'Layout',
        'link' => '#',
        'absolute' => 1,
        'hassubs' => 1,
        'subs' => 
        array (
          0 => 
          array (
            'title' => 'Blocks',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=blocks',
            'icon' => 'admin/blocks/images/blocks.png',
            'small' => 'admin/blocks/images/blocks_small.png',
            'id' => 5,
          ),
          1 => 
          array (
            'title' => 'Block Positions',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=positions',
            'icon' => 'admin/positions/images/positions.png',
            'small' => 'admin/positions/images/positions_small.png',
            'id' => 18,
          ),
          2 => 
          array (
            'title' => 'Templates',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=tplsets',
            'icon' => 'admin/tplsets/images/tplsets.png',
            'small' => 'admin/tplsets/images/tplsets_small.png',
            'id' => 15,
          ),
        ),
        'dir' => 'system',
      ),
      3 => 
      array (
        'title' => 'Media',
        'link' => '#',
        'absolute' => 1,
        'hassubs' => 1,
        'subs' => 
        array (
          0 => 
          array (
            'title' => 'Image Manager',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=images',
            'icon' => 'admin/images/images/images.png',
            'small' => 'admin/images/images/images_small.png',
            'id' => 9,
          ),
          1 => 
          array (
            'title' => 'Mime Types',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=mimetype',
            'icon' => 'admin/mimetype/images/mimetype.png',
            'small' => 'admin/mimetype/images/mimetype_small.png',
            'id' => 23,
          ),
        ),
        'dir' => 'system',
      ),
      4 => 
      array (
        'title' => 'Site Configuration',
        'link' => '#',
        'absolute' => 1,
        'hassubs' => 1,
        'subs' => 
        array (
          0 => 
          array (
            'title' => 'Modules Admin',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=modules',
            'icon' => 'admin/modules/images/modules.png',
            'small' => 'admin/modules/images/modules_small.png',
            'id' => 4,
          ),
          1 => 
          array (
            'title' => 'Preferences',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=preferences',
            'icon' => 'admin/preferences/images/preferences.png',
            'small' => 'admin/preferences/images/preferences_small.png',
            'id' => 3,
            'hassubs' => 1,
            'subs' => 
            array (
              0 => 
              array (
                'title' => 'General Settings',
                'link' => 'http://localhost:8888/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=1',
              ),
              1 => 
              array (
                'title' => 'User Settings',
                'link' => 'http://localhost:8888/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=2',
              ),
              2 => 
              array (
                'title' => 'Meta + Footer',
                'link' => 'http://localhost:8888/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=3',
              ),
              3 => 
              array (
                'title' => 'Word Censoring',
                'link' => 'http://localhost:8888/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=4',
              ),
              4 => 
              array (
                'title' => 'Search Options',
                'link' => 'http://localhost:8888/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=5',
              ),
              5 => 
              array (
                'title' => 'Mail Setup',
                'link' => 'http://localhost:8888/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=6',
              ),
              6 => 
              array (
                'title' => 'Authentication',
                'link' => 'http://localhost:8888/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=7',
              ),
              7 => 
              array (
                'title' => 'Multilanguage',
                'link' => 'http://localhost:8888/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=8',
              ),
              8 => 
              array (
                'title' => 'Personalization',
                'link' => 'http://localhost:8888/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=10',
              ),
              9 => 
              array (
                'title' => 'Captcha Settings',
                'link' => 'http://localhost:8888/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=11',
              ),
              10 => 
              array (
                'title' => 'Plugins Manager',
                'link' => 'http://localhost:8888/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=12',
              ),
              11 => 
              array (
                'title' => 'Auto Tasks',
                'link' => 'http://localhost:8888/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=13',
              ),
              12 => 
              array (
                'title' => 'HTMLPurifier Settings',
                'link' => 'http://localhost:8888/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=14',
              ),
            ),
          ),
        ),
        'dir' => 'system',
      ),
      5 => 
      array (
        'title' => 'System Tools',
        'link' => '#',
        'absolute' => 1,
        'hassubs' => 1,
        'subs' => 
        array (
          0 => 
          array (
            'title' => 'Auto Tasks',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=autotasks',
            'icon' => 'admin/autotasks/images/autotasks.png',
            'small' => 'admin/autotasks/images/autotasks_small.png',
            'id' => 24,
          ),
          1 => 
          array (
            'title' => 'Version Checker',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=version',
            'icon' => 'admin/version/images/version.png',
            'small' => 'admin/version/images/version_small.png',
            'id' => 16,
          ),
        ),
        'dir' => 'system',
      ),
      6 => 
      array (
        'title' => 'Users and Groups',
        'link' => '#',
        'absolute' => 1,
        'hassubs' => 1,
        'subs' => 
        array (
          0 => 
          array (
            'title' => 'Avatars',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=avatars',
            'icon' => 'admin/avatars/images/avatars.png',
            'small' => 'admin/avatars/images/avatars_small.png',
            'id' => 10,
          ),
          1 => 
          array (
            'title' => 'Find Users',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=findusers',
            'icon' => 'admin/findusers/images/findusers.png',
            'small' => 'admin/findusers/images/findusers_small.png',
            'id' => 7,
          ),
          2 => 
          array (
            'title' => 'Groups',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=groups',
            'icon' => 'admin/groups/images/groups.png',
            'small' => 'admin/groups/images/groups_small.png',
            'id' => 1,
          ),
          3 => 
          array (
            'title' => 'Mail Users',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=mailusers',
            'icon' => 'admin/mailusers/images/mailusers.png',
            'small' => 'admin/mailusers/images/mailusers_small.png',
            'id' => 8,
          ),
          4 => 
          array (
            'title' => 'User Ranks',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=userrank',
            'icon' => 'admin/userrank/images/userrank.png',
            'small' => 'admin/userrank/images/userrank_small.png',
            'id' => 11,
          ),
          5 => 
          array (
            'title' => 'Edit Users',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=users',
            'icon' => 'admin/users/images/users.png',
            'small' => 'admin/users/images/users_small.png',
            'id' => 2,
          ),
        ),
        'dir' => 'system',
      ),
    ),
  ),
  2 => 
  array (
    'id' => 'modules',
    'text' => 'Modules',
    'link' => 'http://localhost:8888/modules/system/admin.php?fct=modules',
    'menu' => 
    array (
      0 => 
      array (
        'link' => 'http://localhost:8888/modules/protector/admin/index.php',
        'title' => 'Protector',
        'dir' => 'protector',
        'small' => 'http://localhost:8888/modules/protector/module_icon.php?file=iconsmall',
        'iconbig' => 'http://localhost:8888/modules/protector/module_icon.php?file=iconbig',
        'absolute' => 1,
        'subs' => 
        array (
          0 => 
          array (
            'title' => 'Protect Center',
            'link' => 'http://localhost:8888/modules/protector/admin/index.php',
          ),
          1 => 
          array (
            'title' => 'Security Advisory',
            'link' => 'http://localhost:8888/modules/protector/admin/index.php?page=advisory',
          ),
          2 => 
          array (
            'title' => 'Prefix Manager',
            'link' => 'http://localhost:8888/modules/protector/admin/index.php?page=prefix_manager',
          ),
          3 => 
          array (
            'title' => 'Preferences',
            'link' => 'http://localhost:8888/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=2',
          ),
        ),
        'hassubs' => 1,
      ),
      1 => 
      array (
        'link' => 'http://localhost:8888/modules/system/admin.php',
        'title' => 'System',
        'dir' => 'system',
        'small' => 'http://localhost:8888/modules/system/images/icon_small.png',
        'iconbig' => 'http://localhost:8888/modules/system/images/system_big.png',
        'absolute' => 1,
        'hassubs' => 0,
      ),
    ),
  ),
  3 => 
  array (
    'id' => 'news',
    'text' => 'ImpressCMS Project',
    'link' => '#',
    'menu' => 
    array (
      0 => 
      array (
        'link' => 'http://www.impresscms.org',
        'title' => 'Project Home',
        'absolute' => 1,
      ),
      1 => 
      array (
        'link' => 'http://community.impresscms.org',
        'title' => 'Community',
        'absolute' => 1,
      ),
      2 => 
      array (
        'link' => 'http://addons.impresscms.org',
        'title' => 'Addons',
        'absolute' => 1,
      ),
      3 => 
      array (
        'link' => 'http://wiki.impresscms.org',
        'title' => 'Wiki',
        'absolute' => 1,
      ),
      4 => 
      array (
        'link' => 'http://blog.impresscms.org',
        'title' => 'Blog',
        'absolute' => 1,
      ),
      5 => 
      array (
        'link' => 'https://impresscmsdev.assembla.com/spaces/impresscms/new_dashboard',
        'title' => 'Project Development',
        'absolute' => 1,
      ),
      6 => 
      array (
        'link' => 'http://www.impresscms.org/donations/',
        'title' => 'Donate!',
        'absolute' => 1,
      ),
      7 => 
      array (
        'link' => 'http://localhost:8888/admin.php?rssnews=1',
        'title' => 'News',
        'absolute' => 1,
      ),
    ),
  ),
)