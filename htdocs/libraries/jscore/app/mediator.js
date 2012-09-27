/*
	Module: mediator
	Handles pub/sub for the site

		Variable: channels
			2d array of publish events/subscription callbacks

		Method: subscribe
		Called to subscribe to a published event

			Argument: channel
			the name of the channel being subscribed to

			Argument: subscriptionCallback
			The function that will execute after the publish event of the channel

			Argument: scope
			(Optional)
			Object passed as scope will be used as the scope of the callback function.
			Useful for accessing module methods by passing 'this' as scope.

		Method: publish
		Called to create publish event for all subscriptions on channel

			Argument: channel
			The channel being published.
*/
define(['jquery'], function($){
	var channels = {}
	, deferreds = {}
	, obj = {};

	obj.subscribe = function (channel, subscriptionCallback, scope) {
		if (!channels[channel]){ channels[channel] = [];}
		if(arguments.length === 3){
			channels[channel].push(function(){subscriptionCallback.call(scope);});
		}else{
			channels[channel].push(subscriptionCallback);
		}
		//console.log(channels[channel].length)
	};

	obj.publish = function (channel) {
		if (!channels[channel]){ return;}
		var args = [].slice.call(arguments, 1)
		, i
		, l;
		for ( i = 0, l = channels[channel].length; i < l; i++) {
			channels[channel][i].apply(this, args);
		}
	};

	obj.deferredPublish = function (defer) {
		if (!deferreds[defer]){ deferreds[defer] = $.Deferred();}
		deferreds[defer].resolve();
	};
	obj.deferredSubscribe = function (defer, subscriptionCallback, scope) {
		if (!deferreds[defer]){ deferreds[defer] = $.Deferred();}
		if(arguments.length === 3){
			deferreds[defer].done(function(){subscriptionCallback.call(scope);});
		}else{
			deferreds[defer].done(subscriptionCallback);
		}
	};
	obj.getDeferred = function(defer){
		return deferreds[defer];
	};
	obj.setDeferred = function(defer){
		if (!deferreds[defer]){ deferreds[defer] = $.Deferred();}
		return deferreds[defer];
	};
	obj.Deferred = function(){
		return  $.Deferred();
	};
	return obj;
});
