/* global icms: true */
define(['underscore'], function(_) {
	return {
		processedRoutes: []

		, initialize: function() {
			var self = this;
			icms.routeReady.done(function() {
				self.applyRoute();
			}, self);
		}

		, applyRoute: function(){
			var self = this;
			$.map(icms.router, function(val) {
				if(!_.contains(self.processedRoutes, val)) {
					require([val], function(appRoute) {
						appRoute.initialize();
					});
					self.processedRoutes.push(val);
				}
			});
		}
	};
});