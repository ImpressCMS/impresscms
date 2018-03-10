function icmsCodeMention(id, enterMention) {
	if (enterMention == null) {
		enterMention = "Enter the user name to mention:";
	}
	var text = prompt(enterMention, "");
	var domobj = xoopsGetElementById(id);
	if (text != null && text != "") {
		var pos = text.indexOf(unescape("%00"));
		if (0 < pos) {
			text = text.substr(0, pos);
		}
		var result = "@[" + text + "]";
		xoopsInsertText(domobj, result);
	}

	domobj.focus();
}
