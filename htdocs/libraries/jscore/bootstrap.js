/*
  Module: bootstrap
  Loads activator and builder modules, executes both (for modules required by div data attrs)
  Loads main app module
 */

require(
{
  baseUrl: icms.config.jscore,
  paths: {
    util: 'util',
    lib: 'lib',
    order: 'util/order',
    text: 'util/text',
    domReady: 'util/domReady',
    jquery: 'lib/jquery',
    underscore: 'lib/underscore',
    backbone: 'lib/backbone-min',
    app: 'app',
    modules: 'app/modules',
    mediator: 'app/mediator',
    models: 'app/models',
    views: 'app/views',
    templates: 'app/templates',
    localStorage: 'app/localStorage',
    tools: 'utils/tools',
    routes: 'app/routes',
    locale: 'locale/' + icms.config.language + '/',
    hogan: 'lib/hogan',
    jsonpath: 'lib/jsonpath',
    use: 'util/use',
    GA : '//www.google-analytics.com/ga'
  },
  use: {
    underscore: {
      attach: "_"
    },
    hogan: {
      attach: "Hogan"
    },
    backbone: {
      deps: ["util/use!underscore", "jquery"],
      attach: "Backbone"
    },
    jsonpath: {
      attach: 'jsonPath'
    }
  },
  waitSeconds: 60
},
[
  'order!jquery',
  'order!app/main',
  'util/domReady'
],
//placeholders _# are used to load the files properly in order even if there's no need to reference them
function (jquery, app, domReady) {
	//Prevents IE from breaking on console entries.
	if (!window.console) { console = {log: function() {}, warn: function() {}}; }
  app.initialize();
});//end require()
