
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
