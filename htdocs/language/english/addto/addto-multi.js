//	<license>
//	Script: Add To Bookmarks
//	Version: 1.2
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

//intervalMgr was added to make the popup and timer work in Safari
function intervalMgr(){
	if(/Safari/i.test(navigator.userAgent)){ //Test for Safari
		var addtoInterval=setInterval(function(){
  		if(/loaded|complete/.test(document.readyState)){
			closeAddTo() // call target function
  		}}, 1000)
	}
	else{var addtoInterval = setInterval("closeAddTo();",1000);}
}

function addtoWin(addtoFullURL)
{
	if (!popupWin.closed && popupWin.location){
		popupWin.location.href = addtoFullURL;
		intervalMgr();
	}
	else{
		popupWin = window.open(addtoFullURL,'addtoPopUp','width=770px,height=500px,status=0,location=0,resizable=1,scrollbars=1,left=0,top=100');
		if (!popupWin.opener) popupWin.opener = self;
		intervalMgr();
		//var addtoInterval = setInterval("closeAddTo();",1500);
	}
	if (window.focus) {popupWin.focus()}
	return false;
}
// closes the popupWin
function closeAddTo() {
	if (!popupWin.closed && popupWin.location){
		if (popupWin.location.href == AddURL)	//if it's the same url as what was bookmarked, close the win
		popupWin.close();
		clearInterval(addtoInterval)
	}
	else {	//if it's closed - clear the timer
		clearInterval(addtoInterval)
		return true
	}
}
//main addto function - sets the variables for each Social Bookmarking site
function addto(addsite,AddURL,AddTitle){
	var AddURL = AddURL;	
	var AddTitle = AddTitle;
	switch(addsite){
		case 0:	//	AddToBookmarks.com ID:0	- an educational page on what Social Bookmarking is
			var AddSite = "http://www.addtobookmarks.com/socialbookmarking.htm?";
			var AddUrlVar = "url";
			var AddTitleVar = "title";
			var AddNoteVar = "";
			var AddReturnVar = "";
			var AddOtherVars = "";	
			break	
		case 1:	//	Blink ID:1
			var AddSite = "http://www.blinklist.com/index.php?Action=Blink/addblink.php";
			var AddUrlVar = "url";
			var AddTitleVar = "title";
			var AddNoteVar = "description";
			var AddReturnVar = "";
			var AddOtherVars = "&Action=Blink/addblink.php";	
			break
		case 2:	//	Del.icio.us	ID:2 &v=3&noui=yes&jump=close
			var AddSite = "http://del.icio.us/post?";
			var AddUrlVar = "url";
			var AddTitleVar = "title";
			var AddNoteVar = "";
			var AddReturnVar = "";
			var AddOtherVars = "";		
			break
		case 3:	//	Digg ID:3
			var AddSite = "http://digg.com/submit?";
			var AddUrlVar = "url";
			var AddTitleVar =  "";
			var AddNoteVar =  "";
			var AddReturnVar =  "";
			var AddOtherVars = "&phase=2";
			break
		case 4:	//	Furl ID:4
			var AddSite = "http://www.furl.net/savedialog.jsp?";
			var AddUrlVar = "u";
			var AddTitleVar = "t";
			var AddNoteVar = "";
			var AddReturnVar = "";
			var AddOtherVars = "";	
			break
		case 5:	//	GOOGLE ID:5
			var AddSite = "http://www.google.com/bookmarks/mark?op=add&";
			var AddUrlVar = "bkmk";
			var AddTitleVar = "title";
			var AddNoteVar = "";
			var AddReturnVar = "";
			var AddOtherVars = "";
			break
		case 6:	//	Simpy ID:6
			var AddSite = "http://simpy.com/simpy/LinkAdd.do?";
			var AddUrlVar = "href";
			var AddTitleVar = "title";
			var AddNoteVar = "note";
			var AddReturnVar = "_doneURI";
			var AddOtherVars = "&v=6&src=bookmarklet";
			break
		case 7:	//	Yahoo ID: 7
			var AddSite = "http://myweb2.search.yahoo.com/myresults/bookmarklet?";
			var AddUrlVar = "u";
			var AddTitleVar = "t";
			var AddNoteVar = "";
			var AddReturnVar = "";
			var AddOtherVars = "&d=&ei=UTF-8";
			break
		case 8:	//	Spurl ID: 8 	d.selection?d.selection.createRange().text:d.getSelection()
			var AddSite = "http://www.spurl.net/spurl.php?";
			var AddUrlVar = "url";
			var AddTitleVar = "title";
			var AddNoteVar = "blocked";
			var AddReturnVar = "";
			var AddOtherVars = "&v=3";
			break			
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
			addtoWin(addtoFullURL);
			break
		default:	
		}
		return true;
}
//	checking across domains causes errors - this is to supress these
function handleError() {return true;}
window.onerror = handleError;