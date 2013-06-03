/* global icms: true */
/*
Module: routes
Include file for all route modules.
Loaded by bootstrap.
*/
define(function () {
	var route = {
		initialize: function() {
			routeReady.done(function(){
				this.applyRoute();
			}, this);
		},
		applyRoute: function(){
			if( typeof router !== 'undefined') {
				require([router],function(route){
					if(route.initialize) {
						route.initialize();
						window.routerLoaded = window.router;
						window.router = undefined;
					}
				});
			}

			if( typeof window.themeRoute !== 'undefined') {
				var tRoute = icms.config.themeUrl + '/app/routes/' + window.themeRoute;
				require([tRoute], function(route) {
					if(route.initialize) {
						route.initialize();
						window.themeRouteLoaded = window.themeRoute;
						window.themeRoute = undefined;
					}
				});
			}

		}
	};

	return route;
});
