AddToBookmarks - ReadMe file
========================================================

LICENSE:
	Script: Add To Bookmarks	
	Version: 1.2
	Homepage: http://www.AddToBookmarks.com
	Author:	Gideon Marken
	Author Blog: http://www.gideonmarken.com/
	Author Work: http://www.markenmedia.com/
	Author Work: http://www.webandaudio.com/
	Date: July 18, 2007
	License: Mozilla Public License 1.1	http://www.mozilla.org/MPL/MPL-1.1.html

ABOUT:
	AddToBookmarks is a small JavaScript you can add to your blog or website which assists site visitors in 
	adding your site to their 'Social Bookmarking' service.

VERSIONS:
	Single Version - Adding the single version to your site is easy. It's designed to be used where you only need 
	one AddToBookmarks displayed per page. For example, if you want to add this to your Website, the Single Standard 
	Version is what you want to use.

	Multi Version - If you are adding the AddToBookmarks script to your blog, you may want to set it up so you 
	output a version of AddToBookmarks per each blog posting. This would allow visitors to add each post of yours 
	to their Social Bookmarking site. This version will require you to do more work, but if you are considering this 
	version, it should be a snap to setup. All you need to know is how to output the URL and TITLE of your blog posts 
	in the language you use (CFML, PHP, ASP, JSP, etc.).

DIRECTIONS: use the demo files, or follow these steps.
	Single Version:
		1. Upload the '/addto' directory to your server along with all the files in the /addto directory.
		 
			If you need to place the files in some other directory, I suggest opening the .js file, the example code, etc. 
			and doing a search/replace on "addto/".
			
		2. Add the following Style Sheet code to the "HEAD" of your file:
		
			<!-- AddToBookmarks.com CSS - Place this in the head of your page -->
			<style type="text/css">
				@import "addto/addto.css";
			</style>
			<!-- End AddToBookmarks.com CSS -->
			
		3. Add the following code where you wish to display your copy of AddToBookmarks:
		
			<!-- AddToBookmarks.com JS Settings - Place this in the body, at the top -->
			<script language="JavaScript" type="text/javascript">
				var addtoLayout=0;						// 0=Horizontal 1 row, 1=Horizontal 2 rows, 2=Vertical, 3=Vertical text only 
				var addtoMethod=1;						// 0=direct link, 1=popup window
				var AddURL = encodeURIComponent(document.location.href);	// this is the page's URL
				var AddTitle = escape(document.title);	// this is the page title
			</script>
			<!-- End AddToBookmarks.com JS Settings -->
			
			<!-- AddToBookmarks.com JS - Place this where you want it to display -->
			<script language="JavaScript" src="addto/addto.js" type="text/javascript"></script>
			<!-- End AddToBookmarks.com JS -->

		4. Configure the exposed variables:
			var addtoLayout=0; 
					0=Horizonal 1 row
					1=Horizonal 2 rows
					2=Vertical
					3=Vertical text only
			var addtoMethod=1;
					0=direct link
					1=popup
		5. Edit the addto/addto.css Stylesheet if you want
		6. Test it!
		
DEMO FILES:
	addtodemoH.htm - Contains both horizontal demos, feel free to copy the source from the demo
	
	addtodemoV.htm - Contains both vertical demos, feel free to copy the source from the demo
	
	addtodemo-customize.htm - contains an altered version of the script which allows you allows you to either 
		customize the layout or take it all apart and make customize the whole thing.
	
	addtodemo-multi.htm - contains the 'multi' version of the script which is designed for people who want to display
		more than one AddToBookmarks per page. This version is also for people who want to set the URL and TITLE variables
		with values from their database. For example, anyone with a blog system or application built into their site/service,
		could benefit by integrating this code into their own (you must keep the license that comes with AddToBookmarks with 
		the code at all times.) You definately can integrate this into your own software, just please keep the original 
		license and info from the comments with the code.
		
ABOUT:
	User clicks on a link or icon, this passes the link's ID to the scipt. There, the URL  page's TITLE are extracted 
	dynamically by Javascript, then built into the Social Bookmarking site request. Depending on how you set the method,
	either a popup window will open with the site, or the user will be sent off to the Social Bookmarking site. Once the 
	bookmark is added, the user is sent back to your site. If you used the popup window method, the script would then notice 
	that the popup is displaying your site, then close it, so the user can continue browsing from where they were. If you 
	used the direct method, the user will be sent back to the originating page in the same browser.
	
CHANGE LOG:
	12-05-05	v1		- initial release
	07-18-07	v1.1	- fixed CSS issue in horizontal layout
						- updated Google and Furl bookmark links
						- safari popup/timer issue fixed - added intervalMgr function
	09-12-07	v1.2	- added encodeURIComponent() function around document.location.href so name/value pairs can be passed
