/* globals Modernizr:true, Backbone:true, Handlebars: true */
define([
	'marionette'
	, 'handlebars'
], function (Marionette) {

	var Core = Marionette;

	Core.app = new Core.Application();

	Core.template = Handlebars;

	Core.test = function (featureName) {
		Modernizr.addTest('json', function(){
			return !!JSON && !!JSON.parse;
		});

		return Modernizr[featureName];
	};

	Core.Renderer.render = function(template, data) {
		return Core.template.render(template, data);
	};

	Core.mediator = {

		_deferreds: {}

		, _oneTime: function (eventName, callback) {
			Marionette.app.once(eventName, function(e) {
				var args = [].slice.call(e, 1);
				callback.apply(Marionette.app, args);
			});
		}

		, _listener: function(eventName, callback) {
			Marionette.app.on(eventName, function(e) {
				var args = [].slice.call(e, 1);
				callback.apply(Marionette.app, args);
			});
		}

		, _speaker: function (eventName, callback) {
			if(typeof Marionette.mediator._deferreds['def_' + eventName] !== 'undefined') {
				callback.apply(Marionette.app, Marionette.mediator._deferreds['def_' + eventName]);
				return true;
			}
			return false;
		}

		, publish: function(eventName) {
			Marionette.app.trigger(eventName, arguments);
		}

		, subscribe: function(eventName, callback) {
			Marionette.mediator._listener(eventName, callback);
		}

		, subscribeOnce: function(eventName, callback) {
			Marionette.mediator._oneTime(eventName, callback);
		}

		, deferredPublish: function(eventName) {
			if(typeof Marionette.mediator._deferreds['def_' + eventName] === 'undefined') {
				Marionette.mediator._deferreds['def_' + eventName] = arguments;
				Marionette.app.trigger('def_' + eventName, arguments);
			}
		}

		, deferredSubscribe: function(eventName, callback) {
			if (Marionette.mediator._speaker(eventName, callback) === true) {
				return;
			}

			Marionette.mediator._listener('def_' + eventName, callback);
		}

		, deferredSubscribeOnce: function(eventName, callback) {
			if (Marionette.mediator._speaker(eventName, callback) === true) {
				return;
			}

			Marionette.mediator._oneTime('def_' + eventName, callback);
		}
	};

	return Core;
});