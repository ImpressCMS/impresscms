// This function enables the use of javascript to open new windows for link urls
// in replace of target="_blank" etc due to xhtml validation not recognising target anymore.
// typical values for rel in the <a href> tag are:
// rel="external" - opens  destination link in external window
// rel="nofollow" - instructs web crawlers & bots to not follow or score the destination link (SEO necessity)
// rel="nofollow external" a combination of both the above.
// example use: <a href="http://www.xoops.org" rel="nofollow external" />XOOPS CMS</a>

function xoopsExternalLinks() {  
	if (!document.getElementsByTagName) return;  

	var anchors = document.getElementsByTagName("a");  
	for (var i=0; i<anchors.length; i++) {  
		var anchor = anchors[i];  
		var relvalue = anchor.getAttribute("rel"); 

		if (anchor.getAttribute("href")) { 
			var external = /external/; 
			var relvalue = anchor.getAttribute("rel"); 
			if (external.test(relvalue)) {
				anchor.target = "_blank";
			} 
		}  
	} 
}
window.onload = xoopsExternalLinks;