function appendSelectOption(selectMenuId, optionName, optionValue){
	var selectMenu = xoopsGetElementById(selectMenuId);
	var newoption = new Option(optionName, optionValue);
	newoption.selected = true;
	selectMenu.options[selectMenu.options.length] = newoption;
}

function xoopsInsertText(domobj, text)
{
	if(domobj.selectionEnd){
		var str1=domobj.value.substring(0, domobj.selectionStart);
		var str2=domobj.value.substring(domobj.selectionEnd, domobj.value.length);
		domobj.value = str1 + text + str2;
		domobj.selectionEnd = domobj.selectionStart;
		domobj.blur();	
	}else
	if (domobj.createTextRange && domobj.caretPos){
  		var caretPos = domobj.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;  
	} else if (domobj.getSelection && domobj.caretPos){
		var caretPos = domobj.caretPos;
		caretPos.text = caretPos.text.charat(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
	} else {
		domobj.value = domobj.value + text;
  	}
}

function showImgSelected(imgId, selectId, imgDir, extra, xoopsUrl) {
	if (xoopsUrl == null) {
		xoopsUrl = "./";
	}
	imgDom = xoopsGetElementById(imgId);
	selectDom = xoopsGetElementById(selectId);
	if (selectDom.options[selectDom.selectedIndex].value != "") {
		imgDom.src = xoopsUrl + "/"+ imgDir + "/" + selectDom.options[selectDom.selectedIndex].value + extra;
	} else {
        imgDom.src = xoopsUrl + "/images/blank.gif";
	}
}

function xoopsCodeUrl(id, enterUrlPhrase, enterWebsitePhrase){
	if (enterUrlPhrase == null) {
		enterUrlPhrase = "Enter the URL of the link you want to add:";
	}
	var text = prompt(enterUrlPhrase, "");
	var domobj = xoopsGetElementById(id);
	if ( text != null && text != "" ) {
		var selection = getSelect(id);
		if (selection.length > 0){
			var text2 = selection;
		}else {
			var text2 = prompt(enterWebsitePhrase, "");
		}
		if ( text2 != null ) {
			if ( text2 == "" ) {
				var result = "[url=" + text + "]" + text + "[/url]";
			} else {
				var pos = text2.indexOf(unescape('%00'));
				if(0 < pos){
					text2 = text2.substr(0,pos);
				}
				var result = "[url=" + text + "]" + text2 + "[/url]";
			}
			xoopsInsertText(domobj, result);
		}
	}
	domobj.focus();
}

function xoopsCodeImg(id, enterImgUrlPhrase, enterImgPosPhrase, imgPosRorLPhrase, errorImgPosPhrase, enterImgWidthPhrase){
	if (enterImgUrlPhrase == null) {
		enterImgUrlPhrase = "Enter the URL of the image you want to add:";
	}
	var selection = getSelect(id);
	if (selection.length > 0){
		var text = selection;
	}else {
		var text = prompt(enterImgUrlPhrase, "");
	}
	var domobj = xoopsGetElementById(id);
	if ( text != null && text != "" ) {
		if (enterImgPosPhrase == null) {
			enterImgPosPhrase = "Now, enter the position of the image.";
		}
		if (imgPosRorLPhrase == null) {
			imgPosRorLPhrase = "'R' or 'r' for right, 'L' or 'l' for left, or leave it blank.";
		}
		if (errorImgPosPhrase == null) {
			errorImgPosPhrase = "ERROR! Enter the position of the image:";
		}
		var text2 = prompt(enterImgPosPhrase + "\n" + imgPosRorLPhrase, "");
		while ( ( text2 != "" ) && ( text2 != "r" ) && ( text2 != "R" ) && ( text2 != "l" ) && ( text2 != "L" ) && ( text2 != null ) ) {
			text2 = prompt(errorImgPosPhrase + "\n" + imgPosRorLPhrase,"");
		}
		if ( text2 == "l" || text2 == "L" ) {
			text2 = " align=left";
		} else if ( text2 == "r" || text2 == "R" ) {
			text2 = " align=right";
		} else {
			text2 = "";
		}
		
		var text3 = prompt(enterImgWidthPhrase, "300");
		if ( text3.length>0 ) {
			text3 = " width="+text3;
		}else {
			text3 = "";
		}
		
		var result = "[img" + text2 + text3 + "]" + text + "[/img]";
		xoopsInsertText(domobj, result);
	}
	domobj.focus();
}

function xoopsCodeWiki(id, enterWikiPhrase){
	if (enterWikiPhrase == null) {
		enterWikiPhrase = "Enter the word to be linked to Wiki:";
	}
	var selection = getSelect(id);
	if (selection.length > 0){
		var text = selection;
	}else {
		var text = prompt(enterWikiPhrase, "");
	}
	var domobj = xoopsGetElementById(id);
	if ( text != null && text != "" ) {
		var result = "[[" + text + "]]";
		xoopsInsertText(domobj, result);
	}
	domobj.focus();
}

function xoopsCodeEmail(id, enterEmailPhrase){
	if (enterEmailPhrase == null) {
		enterEmailPhrase = "Enter the email address you want to add:";
	}
	var selection = getSelect(id);
	if (selection.length > 0){
		var text = selection;
	}else {
		var text = prompt(enterEmailPhrase, "");
	}
	var domobj = xoopsGetElementById(id);
	if ( text != null && text != "" ) {
		var result = "[email]" + text + "[/email]";
		xoopsInsertText(domobj, result);
	}
	domobj.focus();
}

function xoopsCodeQuote(id, enterQuotePhrase){
	if (enterQuotePhrase == null) {
		enterQuotePhrase = "Enter the text that you want to be quoted:";
	}
	var selection = getSelect(id);
	if (selection.length > 0){
		var text = selection;
	}else {
		var text = prompt(enterQuotePhrase, "");
	}
	var domobj = xoopsGetElementById(id);
	if ( text != null && text != "" ) {
		var pos = text.indexOf(unescape('%00'));
		if(0 < pos){
			text = text.substr(0,pos);
		}
		var result = "[quote]" + text + "[/quote]";
		xoopsInsertText(domobj, result);
	}
	domobj.focus();
}

function xoopsCodeCode(id, enterCodePhrase){
	if (enterCodePhrase == null) {
		enterCodePhrase = "Enter the codes that you want to add.";
	}
	var selection = getSelect(id);
	if (selection.length > 0){
		var text = selection;
	}else {
		var text = prompt(enterCodePhrase, "");
	}
	var domobj = xoopsGetElementById(id);
	if ( text != null && text != "" ) {
		var result = "[code]" + text + "[/code]";
		xoopsInsertText(domobj, result);
	}
	domobj.focus();
}

function xoopsCodeText(id, hiddentext, enterTextboxPhrase){
	var textareaDom = xoopsGetElementById(id);
	var textDom = xoopsGetElementById(id + "Addtext");
	var fontDom = xoopsGetElementById(id + "Font");
	var colorDom = xoopsGetElementById(id + "Color");
	var sizeDom = xoopsGetElementById(id + "Size");
	var xoopsHiddenTextDomStyle = xoopsGetElementById(hiddentext).style;
	var selection = getSelect(id);
	if (selection.length > 0){
		var textDomValue = selection;
	}else {
		var textDomValue = textDom.value;
	}	
	var fontDomValue = fontDom.options[fontDom.options.selectedIndex].value;
	var colorDomValue = colorDom.options[colorDom.options.selectedIndex].value;
	var sizeDomValue = sizeDom.options[sizeDom.options.selectedIndex].value;
	if ( textDomValue == "" ) {
		if (enterTextboxPhrase == null) {
			enterTextboxPhrase = "Please input text into the textbox.";
		}
		alert(enterTextboxPhrase);
		textDom.focus();
	} else {
		if ( fontDomValue != "FONT") {
			textDomValue = "[font=" + fontDomValue + "]" + textDomValue + "[/font]";
			fontDom.options[0].selected = true;
		}
		if ( colorDomValue != "COLOR") {
			textDomValue = "[color=" + colorDomValue + "]" + textDomValue + "[/color]";
			colorDom.options[0].selected = true;
		}
		if ( sizeDomValue != "SIZE") {
			textDomValue = "[size=" + sizeDomValue + "]" + textDomValue + "[/size]";
			sizeDom.options[0].selected = true;
		}
		if (xoopsHiddenTextDomStyle.fontWeight == "bold" || xoopsHiddenTextDomStyle.fontWeight == "700") {
			textDomValue = "[b]" + textDomValue + "[/b]";
			xoopsHiddenTextDomStyle.fontWeight = "normal";
		}
		if (xoopsHiddenTextDomStyle.fontStyle == "italic") {
			textDomValue = "[i]" + textDomValue + "[/i]";
			xoopsHiddenTextDomStyle.fontStyle = "normal";
		}
		if (xoopsHiddenTextDomStyle.textDecoration == "underline") {
			textDomValue = "[u]" + textDomValue + "[/u]";
			xoopsHiddenTextDomStyle.textDecoration = "none";
		}
		if (xoopsHiddenTextDomStyle.textDecoration == "line-through") {
			textDomValue = "[d]" + textDomValue + "[/d]";
			xoopsHiddenTextDomStyle.textDecoration = "none";
		}
		xoopsInsertText(textareaDom, textDomValue);
		textDom.value = "";
		xoopsHiddenTextDomStyle.color = "#000000";
		xoopsHiddenTextDomStyle.fontFamily = "";
		xoopsHiddenTextDomStyle.fontSize = "12px";
		xoopsHiddenTextDomStyle.visibility = "hidden";
		textareaDom.focus();
	}
}

function getSelect(id){
	if (window.getSelection){
		ele = document.getElementById(id);
		var selection = ele.value.substring(
			ele.selectionStart, ele.selectionEnd
		);
	}
	else if (document.getSelection){
		var selection = document.getSelection();
	}
	else if (document.selection){
		var selection = document.selection.createRange().text;
	}
	else{
		var selection = null;
	}
	return selection;
}


function _setElementAttribute(key, val, id, eid){
	var text = getSelect(id);
	if (text.length <= 0){
		setVisible("xoopsHiddenText");
		eval("setElement"+key.substr(0,1).toUpperCase() + key.substr(1,key.length)+"(eid, val)");
		return;
	}
	var domobj = xoopsGetElementById(id);
	xoopsInsertText(domobj, "["+key+"="+val+"]"+text+"[/"+key+"]");
	domobj.focus();
}

function _setElementColor(eid, val, id){
	_setElementAttribute("color", val, id, eid);
}

function _setElementFont(eid, val, id){
	_setElementAttribute("font", val, id, eid);
}

function _setElementSize(eid, val, id){
	_setElementAttribute("size", val, id, eid);
}

function _makeFontStyle(id, eid, val, func){
	var text = getSelect(id);
	if (text.length <= 0){
		setVisible(eid);
		eval(func+"(eid)");
		return;
	}
	var domobj = xoopsGetElementById(id);
	xoopsInsertText(domobj, "["+val+"]"+text+"[/"+val+"]");
	domobj.focus();
}

function _makeBold(eid, id){
	_makeFontStyle(id, eid, "b", "makeBold");
}

function _makeItalic(eid, id){
	_makeFontStyle(id, eid, "i", "makeItalic");
}

function _makeUnderline(eid, id){
	_makeFontStyle(id, eid, "u", "makeUnderline");
}

function _makeLineThrough(eid, id){
	_makeFontStyle(id, eid, "d", "makeLineThrough");
}


function xoopsCodeWmp(id, enterWmpPhrase, enterWmpHeightPhrase, enterWmpWidthPhrase){
	var selection = getSelect(id);
	if (selection.length > 0){
		var text = selection;
	}
	else{
		var text = prompt(enterWmpPhrase, "");
	}
	var domobj = xoopsGetElementById(id);
	if ( text.length > 0 ) {
		var text2 = prompt(enterWmpWidthPhrase, "480");
		var text3 = prompt(enterWmpHeightPhrase, "330");
		var result = "[wmp="+text2+","+text3+"]" + text + "[/wmp]";
		xoopsInsertText(domobj, result);
	}
	domobj.focus();
}

function xoopsCodeFlash(id, enterFlashPhrase, enterFlashHeightPhrase, enterFlashWidthPhrase, enableDimensionDetect){
	var selection = getSelect(id);
	if (selection.length > 0){
		var text = selection;
	}
	else{
		var text = prompt(enterFlashPhrase, "");
	}
	var domobj = xoopsGetElementById(id);
	if ( text.length>0 ) {
		var text2 = enableDimensionDetect ? "" : prompt(enterFlashWidthPhrase, "");
		var text3 = enableDimensionDetect ? "" : prompt(enterFlashHeightPhrase, "");
		var result = "[flash="+text2+","+text3+"]" + text + "[/flash]";
		xoopsInsertText(domobj, result);
	}
	domobj.focus();
}

function xoopsCodeMms(id,enterMmsPhrase, enterMmsHeightPhrase, enterMmsWidthPhrase){
	var selection = getSelect(id);
	if (selection.length > 0){
		var selection="mms://"+selection;
		var text = selection;
	}
	else{
		var text = prompt(enterMmsPhrase+"       mms or http", "mms://");
	}
	var domobj = xoopsGetElementById(id);
	if ( text.length > 0 && text != "mms://") {
		var text2 = prompt(enterMmsWidthPhrase, "480");
		var text3 = prompt(enterMmsHeightPhrase, "330");
		var result = "[mms="+text2+","+text3+"]" + text + "[/mms]";
		xoopsInsertText(domobj, result);
	}
	domobj.focus();
}

function xoopsCodeRtsp(id,enterRtspPhrase, enterRtspHeightPhrase, enterRtspWidthPhrase){
	var selection = getSelect(id);
	if (selection.length > 0){
			var selection = "rtsp://"+selection;
			var text = selection;
		}
		else{
			var text = prompt(enterRtspPhrase+"       Rtsp or http", "Rtsp://");
		}
	var domobj = xoopsGetElementById(id);
	if ( text.length > 0 && text!="rtsp://") {
		var text2 = prompt(enterRtspWidthPhrase, "480");
		var text3 = prompt(enterRtspHeightPhrase, "330");
		var result = "[rtsp="+text2+","+text3+"]" + text + "[/rtsp]";
		xoopsInsertText(domobj, result);
	}
	domobj.focus();
}

function xoopsCodeIframe(id, enterIFramePhrase, enterIFrameHeight){
	var selection = getSelect(id);
	if (selection.length > 0){
		var text = selection;
	}else {
		var text = prompt(enterIFramePhrase, "http://");
	}
	var dom = xoopsGetElementById(id);
	if ( text.length>0 && text!="http://") { 
          var text2 = prompt(enterIFrameHeight, "800");
          if ( text2 != null ) { 
               if ( text2 == "" ) { 
                    var result = "[iframe=800]" + text + "[/iframe]"; 
               } else { 
                    var pos = text2.indexOf(unescape('%00')); 
                    if(0 <= pos){ 
                         text2 = text2.substr(0,pos); 
                    } 
                    var result = "[iframe=" + text2 + "]" + text + "[/iframe]"; 
               } 
               dom.value += result;
          } 
     } 
	dom.focus();
}

// very rough calculation on text length
function XoopsCheckLength(id, maxlength, currentLengthPhrase, maxLengthPhrase) {
	var mb_len_extra = 2;
	var domobj = xoopsGetElementById(id);
	var len = domobj.value.length;
	
	if(len > 50 * 1024) {
		len_current = " > 50K";
	}else if(len > 30 * 1024) {
		len_current = " 30-50K";
	}else if(len > 10 * 1024) {
		len_current = " 10-30K";
	}else if(len > 5 * 1024) {
		len_current = " 5-10K";
	}else if(len > 1 * 1024) {
		len_current = " 1-5K";
	}else{
		len_current = len;
		for(var n = 0; n < len; n++) {
			if(domobj.value.charAt(n) > '~') {
				len_current += mb_len_extra;
			}
		}
		
		len_current = len_current + " bytes"; 
	}
		
	var string = currentLengthPhrase.replace(/\%s/, len_current);
	if(maxlength > 0) string += ' [' + maxLengthPhrase + maxlength + ']';
	alert(string);
} 