core v. 1

[CHANGES - USER SIDE]

- New design based in core theme by Will Hall
- Templates for system module moved to HTML5
- In small devices center columns looks before left column
- Header section redesigned for small and medium devices.
- Tababble login form and lost pass template
- Dropdown in Bookmarks block
- New design for Theme select block
- Go to top with image mode.
- Sticky header nav
- HTML5 templates for Content module
- Top users block redesigned (carousel)
- Top posters blocks redesigned.
- Badges in Who is on line block and Pending content.
- Edit content in User side uses Tooltip Bootstrap
- Edit block in User side with Bootstrap design.
- User info template redesigned.
- New Cooming soon template (site closed).
- Error template redesigned.
- New look and feel for Comments.
- Search template improves: accordion for results and new search.
- Friendly printing added.

[CHANGES - ADMIN SIDE]

- New Admin Theme based in https://almsaeedstudio.com/
- 
- Input type file design based on
- iCheck 







======
icmsbootstrap_v303

Changelog:

2013 Dec. 9th

[UPDATES]
- Update Bootstrap to version 3.0.3
- Pagination is presend, if you setup the default value
- new favicon's

[BUGFIXES]
- Convert and rewrite the code, since 3.x is not compatible with 2.x
- Site-closed template is responsive now and use bootstrap also
- IE8 support added (Please read the browser support here:
  http://getbootstrap.com/getting-started/#browsers

[UNSOLVED]
- Core-Bug: "Remember me" is presend if you open the user.php only

Have a lot of fun :-)

Rene Sato
--
ImpressCMS Deutschland

======
ImpressCMS - Bootstrap v. 1

Following the work by David (http://community.impresscms.org/modules/newbb/viewtopic.php?viewmode=flat&type=&topic_id=5158&forum=10
), I am happy introducing a port to ImpressCMS of Bootstrap. 

It uses a responsive design and the latest Bootstrap release. The custom CSS code is in /css/custom.css and the custom js code in /js/script.js and it is multilanguage.

Bonus: certain Bootstrap features are imported too and you can use them thanks to the Custom Block Position ImpressCMS feature. They are:

1.- Tabs (right block)
2.- Collapse (accordion) (right block)
3.- Carousel (slider) 
System/Layout/Blocks Positions and create them named tabs, accordion, menu and slider
Go to System/Layout/Blocks and create blocks using one of these positions (or assign them for Blocks modules). About the slider, look the example code in /templates/slider.html; create an HTML block, copy and paste or modify and as slider.html content just write: <{$block.content}>
More candy:
4.- Dropdown in Header Menu
5.- Login Box Dropdown based in Header Menu.
6.- Go to Top jQuery based.

Special designs:
1.- Custom Block Position: menu. Great for usermenu or main menu, etc. Look the code in /templates/menu.html as example and do not forget replace adding <{$block.content}>
2.- Special pagination style: bootstrap.
3.- Two site closed custom templates: system_siteclosed.html (the boxes for username and password open in a modal box) and system_siteclosed2.html. For use it just rename deleting "2": it is an more conventional template, design according the Bootstrap style without modal box.
4.- Icon glyphs used in user menu template,Login Box Dropdown and Head Menu when logged.
5.- Custom Block Position: prefoot. Just for blocks before footer section. You can modify the code according the number of blocks for your suites. span3 (four blocks), span 4 (three blocks) span6 (two blocks) or combine the values span4, span3, span5

About set up the class active un your Head Menu, just look the example code in theme.html:

<li class="<{if $icms_dirname=='system'}>active<{/if}> dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Link1<b class="caret"></b></a>
