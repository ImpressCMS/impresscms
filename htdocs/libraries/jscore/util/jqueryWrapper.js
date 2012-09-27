define(function () {
	console.log('jquery wrapper loaded');
	/*var jqueryPath = 'jqueryAbs';
	var jquery;
	require(['/wcsstore/CommonFossil/javascript/shell/lib/jquery-1.7.min.2'], function(j){
		console.log($);
		console.log(j);
		return $;
	});*/
	var $ = require('jquery');
	console.log($);
	return $;
});
