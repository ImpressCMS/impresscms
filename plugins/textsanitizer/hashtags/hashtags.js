function icmsCodeHashtag(id, enterHashtag) {
	if (enterHashtag == null) {
		enterHashtag = "Enter the term(s) to tag:";
	}
	var text = prompt(enterHashtag, "");
	var domobj = xoopsGetElementById(id);
	if (text != null && text != "") {
		var pos = text.indexOf(unescape("%00"));
		if (0 < pos) {
			text = text.substr(0, pos);
		}
		var result = "#[" + text + "]";
		xoopsInsertText(domobj, result);
	}

	domobj.focus();
}
