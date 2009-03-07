//	<license>
//	Script: Add To Bookmarks
//	Version: 1.1
//	Homepage: http://www.AddToBookmarks.com/
//	Author:	Gideon Marken
//	Author Blog: http://www.gideonmarken.com/
//	Author Work: http://www.markenmedia.com/
//	Author Work: http://www.webandaudio.com/
//	Date: July 18, 2007
//  License: Mozilla Public License 1.1	http://www.mozilla.org/MPL/MPL-1.1.html
//	Custom Development: If you need this script modified, or other custom Web development - contact me!
//	</license>
//	** NOTES - ok to delete
//	AddSite= this will be the url to the social bookmarking site for adding bookmarks
//	AddUrlVar= variable for URL
//	AddTitleVar= variable for TITLE
//	AddNote= the notes or description of the page - we're using the title for this when it's used
//	AddReturn= so far, one site requires a return url to be passed
//	AddOtherVars= some social bookmarking sites require other variables and their values to be passed - if any exist, they'll be set to this var
//	AddToMethod	= [0=direct,1=popup]
//	**Release Log
//	v1 = [December 05, 2005] initial release
//	v1.1 = [July 18, 2007] CSS issue in horizontal layout // Google and Furl bookmark link change // safari popup/timer issue fixed
var txtVersion = "1.1";
var addtoInterval = null;
var popupWin = '';
// Add To Bookmarks Layout Style - If you plan to only use 1 layout, delete the others
switch(addtoLayout){
	case 0:		// horizontal, 1 row
document.write('<div class="addToContent"><dl class="addTo"><dd><span title="Learn about Social Bookmarking" class="addToAbout" onclick');
document.write('="addto(0)">ADD TO:</span></dd><dd><span title="Add this page to Blink"  onclick="addto(1)"><img src="'+addToScriptUrl+'AddTo_Blin');
document.write('k.gif" width="16" height="16" border="0" />Blink</span></dd><dd><span title="Add this page to Delicious" onclick="addto');
document.write('(2)"><img src="'+addToScriptUrl+'AddTo_Delicious.gif" width="16" height="16" border="0" />Del.icio.us</span></dd><dd><span title="');
document.write('Add this page to Digg" onclick="addto(3)"><img src="'+addToScriptUrl+'AddTo_Digg.gif" width="16" height="16" border="0" />Digg</spa');
document.write('n></dd><dd><span title="Add this page to Furl" onclick="addto(4)"><img src="'+addToScriptUrl+'AddTo_Furl.gif" width="16" height="1');
document.write('6" border="0" />Furl</span></dd><dd><span title="Add this page to Google" onclick="addto(5)"><img src="'+addToScriptUrl+'AddTo_Goo');
document.write('gle.gif" width="16" height="16" border="0" />Google</span></dd><dd><span title="Add this page to Simpy" onclick="addto(');
document.write('6)"><img src="'+addToScriptUrl+'AddTo_Simpy.gif" width="16" height="16" border="0" />Simpy</span></dd><dd><span title="Add this pa');
document.write('ge to Spurl" onclick="addto(8)"><img src="'+addToScriptUrl+'AddTo_Spurl.gif" width="16" height="16" border="0" />Spurl</span></dd>');
document.write('<dd><span title="Add this page to Yahoo! MyWeb" onclick="addto(7)"><img src="'+addToScriptUrl+'AddTo_Yahoo.gif" width="16" height="');
document.write('16" border="0" />Y! MyWeb</span></dd>');
document.write('<dd><span title="Add this page to Facebook!" onclick="addto(9)"><img src="'+addToScriptUrl+'AddTo_Facebook.gif" width="16" height="');
document.write('16" border="0" />Facebook</span></dd>');
document.write('</dl></div>');
	break
	case 1:		// horizontal, 2 rows
document.write('<div class="addToContent"><div class="addTo2Row"><div class="addToHeader" onclick="addto(0)">ADD THIS TO YOUR SOCIAL BO');
document.write('OKMARKS</div><div class="addToFloat"><span title="Add this page to Blink"  onclick="addto(1)"><img src="'+addToScriptUrl+'AddTo_Bl');
document.write('ink.gif" width="16" height="16" border="0" /> Blink</span><br /><span title="Add this page to Delicious" onclick="addto');
document.write('(2)"><img src="'+addToScriptUrl+'AddTo_Delicious.gif" width="16" height="16" border="0" /> Del.icio.us</span></div><div class="add');
document.write('ToFloat"><span title="Add this page to Digg" onclick="addto(3)"><img src="'+addToScriptUrl+'AddTo_Digg.gif" width="16" height="16" ');
document.write('border="0" /> Digg</span><br /><span title="Add this page to Furl" onclick="addto(4)"><img src="'+addToScriptUrl+'AddTo_Furl.gif" ');
document.write('width="16" height="16" border="0" /> Furl</span></div><div class="addToFloat"><span title="Add this page to Google" onc');
document.write('lick="addto(5)"><img src="'+addToScriptUrl+'AddTo_Google.gif" width="16" height="16" border="0" /> Google</span><br /><span title=');
document.write('"Add this page to Simpy" onclick="addto(6)"><img src="'+addToScriptUrl+'AddTo_Simpy.gif" width="16" height="16" border="0" />Simpy<');
document.write('/span></div><div class="addToFloat"><span title="Add this page to Spurl" onclick="addto(8)"><img src="'+addToScriptUrl+'AddTo_Spur');
document.write('l.gif" width="16" height="16" border="0" />Spurl</span><br /><span title="Add this page to Yahoo! MyWeb" onclick="addto');
document.write('(7)"><img src="'+addToScriptUrl+'AddTo_Yahoo.gif" width="16" height="16" border="0" /> Y! MyWeb</span><br /></div>');
document.write('<div class="addToFloat"><span title="Add this page to Facebook!" onclick="addto(9)"><img src="'+addToScriptUrl+'AddTo_Facebook.gif" border="0" height="16" width="16">Facebook</span></div>');
document.write('</div></div>');
	break
	case 2:		// vertical with icons
document.write('<div class="addToContent"><dl class="addToV"><dd><span title="Learn about Social Bookmarking" class="addToAbout" onclic');
document.write('k="addto(0)">ADD TO:</span></dd><dd><span title="Add this page to Blink"  onclick="addto(1)"><img src="'+addToScriptUrl+'AddTo_Bli');
document.write('nk.gif" width="16" height="16" border="0" />Blink</span></dd><dd><span title="Add this page to Delicious" onclick="addt');
document.write('o(2)"><img src="'+addToScriptUrl+'AddTo_Delicious.gif" width="16" height="16" border="0" />Del.icio.us</span></dd><dd><span title=');
document.write('"Add this page to Digg" onclick="addto(3)"><img src="'+addToScriptUrl+'AddTo_Digg.gif" width="16" height="16" border="0" />Digg</sp');
document.write('an></dd><dd><span title="Add this page to Furl" onclick="addto(4)"><img src="'+addToScriptUrl+'AddTo_Furl.gif" width="16" height="');
document.write('16" border="0" />Furl</span></dd><dd><span title="Add this page to Google" onclick="addto(5)"><img src="'+addToScriptUrl+'AddTo_Go');
document.write('ogle.gif" width="16" height="16" border="0" />Google</span></dd><dd><span title="Add this page to Simpy" onclick="addto');
document.write('(6)"><img src="'+addToScriptUrl+'AddTo_Simpy.gif" width="16" height="16" border="0" />Simpy</span></dd><dd><span title="Add this p');
document.write('age to Spurl" onclick="addto(8)"><img src="'+addToScriptUrl+'AddTo_Spurl.gif" width="16" height="16" border="0" />Spurl</span></dd>');
document.write('<dd><span title="Add this page to Yahoo! MyWeb" onclick="addto(7)"><img src="'+addToScriptUrl+'AddTo_Yahoo.gif" width="16" height=');
document.write('"16" border="0" />Y! MyWeb</span></dd><dd><span title="Add this to Faecbook" onclick="addto(9)"><img src="'+addToScriptUrl+'AddTo_');
document.write('Facebook.gif" width="16" height="16" border="0" />Facebook</span></dd></dl></div>');

	break
	case 3:		// vertical no icons
document.write('<div class="addToContent"><dl class="addToVNoImg"><dd><span title="Learn about Social Bookmarking" class="addToAbout" o');
document.write('nclick="addto(0)">ADD TO:</span></dd><dd><span title="Add this page to Blink" onclick="addto(1)">Blink</span></dd><dd>');
document.write('<span title="Add this page to Delicious" onclick="addto(2)">Del.icio.us</span></dd><dd><span title="Add this page to Di');
document.write('gg" onclick="addto(3)">Digg</span></dd><dd><span title="Add this page to Furl" onclick="addto(4)">Furl</span></dd><dd>');
document.write('<span title="Add this page to Google" onclick="addto(5)">Google</span></dd><dd><span title="Add this page to Simpy" onc');
document.write('lick="addto(6)">Simpy</span></dd><dd><span title="Add this page to Spurl" onclick="addto(8)">Spurl</span></dd><dd><spa');
document.write('n title="Add this page to Yahoo! MyWeb" onclick="addto(7)">Y! MyWeb</span></dd><dd><span title="Add this page to Facebo');
document.write('ok" onclick="addto(9)">Facebook</span></dd></dl></div>');
	break
	default:
}

//intervalMgr was added to make the popup and timer work in Safari
function intervalMgr(){
	if(/Safari/i.test(navigator.userAgent)){ //Test for Safari
		var addtoInterval=setInterval(function(){
  		if(/loaded|complete/.test(document.readyState)){
			clearInterval(addtoInterval)
			closeAddTo() // call target function
  		}}, 1000)
	}
	else{var addtoInterval = setInterval("closeAddTo();",1000);}
}
function addtoWin(addtoFullURL, width, height)
{
	if (!popupWin.closed && popupWin.location){
		popupWin.location.href = addtoFullURL;
		intervalMgr();
	}
	else{
		popupWin = window.open(addtoFullURL,'addtoPopUp','width='+width+'px,height='+height+'px,status=0,location=0,resizable=1,scrollbars=1,left=0,top=100');
		if (!popupWin.opener) popupWin.opener = self;
		intervalMgr();
	}
	if (window.focus) {popupWin.focus()}
	return false;
}
// closes the popupWin
function closeAddTo() {
	if (!popupWin.closed && popupWin.location){
		if (popupWin.location.href == AddURL)	//if it's the same url as what was bookmarked, close the win
		popupWin.close();
	}
	else {	//if it's closed - clear the timer
		clearInterval(addtoInterval)
		return true
	}
}
//main addto function - sets the variables for each Social Bookmarking site
function addto(addsite){
	switch(addsite){
		case 0:	//	AddToBookmarks.com ID:0	- an educational page on what Social Bookmarking is
			var AddSite = "http://www.addtobookmarks.com/socialbookmarking.htm?";
			var AddUrlVar = "url";
			var AddTitleVar = "title";
			var AddNoteVar = "";
			var AddReturnVar = "";
			var AddOtherVars = "";
			var AddPopupWidth = "770";
			var AddPopupHeight = "500";
			break
		case 1:	//	Blink ID:1
			var AddSite = "http://www.blinklist.com/index.php?Action=Blink/addblink.php";
			var AddUrlVar = "url";
			var AddTitleVar = "title";
			var AddNoteVar = "description";
			var AddReturnVar = "";
			var AddOtherVars = "&Action=Blink/addblink.php";
			var AddPopupWidth = "770";
			var AddPopupHeight = "500";
			break
		case 2:	//	Del.icio.us	ID:2 &v=3&noui=yes&jump=close
			var AddSite = "http://del.icio.us/post?";
			var AddUrlVar = "url";
			var AddTitleVar = "title";
			var AddNoteVar = "";
			var AddReturnVar = "";
			var AddOtherVars = "";
			var AddPopupWidth = "770";
			var AddPopupHeight = "500";
			break
		case 3:	//	Digg ID:3
			var AddSite = "http://digg.com/submit?";
			var AddUrlVar = "url";
			var AddTitleVar =  "";
			var AddNoteVar =  "";
			var AddReturnVar =  "";
			var AddOtherVars = "&phase=2";
			var AddPopupWidth = "770";
			var AddPopupHeight = "500";
			break
		case 4:	//	Furl ID:4
			var AddSite = "http://www.furl.net/savedialog.jsp?";
			var AddUrlVar = "u";
			var AddTitleVar = "t";
			var AddNoteVar = "";
			var AddReturnVar = "";
			var AddOtherVars = "";
			var AddPopupWidth = "770";
			var AddPopupHeight = "500";
			break
		case 5:	//	GOOGLE ID:5
			var AddSite = "http://www.google.com/bookmarks/mark?op=add&";
			var AddUrlVar = "bkmk";
			var AddTitleVar = "title";
			var AddNoteVar = "";
			var AddReturnVar = "";
			var AddOtherVars = "";
			var AddPopupWidth = "770";
			var AddPopupHeight = "500";
			break
		case 6:	//	Simpy ID:6
			var AddSite = "http://simpy.com/simpy/LinkAdd.do?";
			var AddUrlVar = "href";
			var AddTitleVar = "title";
			var AddNoteVar = "note";
			var AddReturnVar = "_doneURI";
			var AddOtherVars = "&v=6&src=bookmarklet";
			var AddPopupWidth = "770";
			var AddPopupHeight = "500";
			break
		case 7:	//	Yahoo ID: 7
			var AddSite = "http://myweb2.search.yahoo.com/myresults/bookmarklet?";
			var AddUrlVar = "u";
			var AddTitleVar = "t";
			var AddNoteVar = "";
			var AddReturnVar = "";
			var AddOtherVars = "&d=&ei=UTF-8";
			var AddPopupWidth = "770";
			var AddPopupHeight = "500";
			break
		case 8:	//	Spurl ID: 8 	d.selection?d.selection.createRange().text:d.getSelection()
			var AddSite = "http://www.spurl.net/spurl.php?";
			var AddUrlVar = "url";
			var AddTitleVar = "title";
			var AddNoteVar = "blocked";
			var AddReturnVar = "";
			var AddOtherVars = "&v=3";
			var AddPopupWidth = "770";
			var AddPopupHeight = "500";
			break
		case 9:	//	Facebook ID: 9
			var AddSite = "http://www.facebook.com/sharer.php?";
			var AddUrlVar = "u";
			var AddTitleVar = "t";
			var AddNoteVar = "";
			var AddReturnVar = "";
			var AddOtherVars = "";
			var AddPopupWidth = "626";
			var AddPopupHeight = "436";
			break
			//	To add more bookmarking sites, find the posting URL, identify the variable names, and create another case statement
		default:
	}
//	Build the URL
	var addtoFullURL = AddSite + AddUrlVar + "=" + AddURL + "&" + AddTitleVar + "=" + AddTitle + AddOtherVars ;
	if (AddNoteVar != "")
		{var addtoFullURL = addtoFullURL + "&" + AddNoteVar + "=" + AddTitle;}
	if (AddReturnVar != "")
		{var addtoFullURL = addtoFullURL + "&" + AddReturnVar + "=" + AddURL;}
//	Checking AddToMethod, to see if it opens in new window or not
	switch(addtoMethod){
		case 0:	// 0=direct link
			self.location = addtoFullURL
			break
		case 1:	// 1=popup
			addtoWin(addtoFullURL, AddPopupWidth, AddPopupHeight);
			break
		default:
		}
		return true;
}
//	checking across domains causes errors - this is to supress these
function handleError() {return true;}
window.onerror = handleError;