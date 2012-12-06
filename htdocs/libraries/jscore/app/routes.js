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
			if( typeof router !== 'undefined') {
				require([router],function(route){
					if(route.initialize) {
						route.initialize();
						window.routerLoaded = router;
						router = undefined;
					}
				});
			}

			if( typeof themeRoute !== 'undefined') {
				var tRoute = icms.config.themeUrl + '/app/routes/' + themeRoute;
				require([tRoute], function(route) {
					if(route.initialize) {
						route.initialize();
						window.themeRouteLoaded = themeRoute;
						themeRoute = undefined;
					}
				});
			}

		}
	};

	return route;
});
