/*
Module: routes
Include file for all route modules.
Loaded by bootstrap.
*/
define(function (require) {
	var route = {
		initialize: function(routes) {
			routeReady.done(function(){
				this.applyRoute();
			}, this);
		},
		applyRoute: function(){
			if( typeof router !== ' undefined') {
				require(['routes/' + router + '/main'],function(route){
					route.initialize();
				});
			} else {
				console.warn('No router provided - so no router loaded - only shell will load.');
				require(['routes/' + shell + '/main'],function(route){
					route.initialize();
				});
			}
		}
	};

	return route;
});
