/*
  Module: bootstrap
  Loads activator and builder modules, executes both (for modules required by div data attrs)
  Loads common module - common provides legacy functions to window scope in module form.
  Loads main app module
 */

require(
{
  baseUrl: icms.config.jscore,
  paths: {
    util: 'util',
    lib: 'lib',
    text: 'util/require-utils/text',
    underscore: 'lib/underscore',
    backbone: 'lib/backbone-min',
    app: 'app',
    modules: 'app/modules',
    mediator: 'app/mediator',
    models: 'app/models',
    views: 'app/views',
    templates: 'app/templates',
    localStorage: 'app/localStorage',
    routes: 'app/routes',
    locale: 'locale/' + icms.config.language + '/',
    hbs: 'lib/hbs',
    handlebars: 'lib/handlebars',
    jsonpath: 'lib/jsonpath',
    i18nprecompile: 'lib/i18nprecompile',
    json2: 'lib/json2',
    hoverIntent: 'lib/hoverIntent',
    use: 'util/require-utils/use',
    GA : '//www.google-analytics.com/ga',
    themeBase : icms.config.themeUrl
  },
  shim: {
    underscore: {
      exports: "_"
    },
    handlebars: {
      exports: "Handlebars"
    },
    backbone: {
      deps: ['util/require-utils/underscore', 'jquery'],
      exports: 'Backbone'
    },
    jsonpath: {
      exports: 'jsonPath'
    },
    hoverIntent: {
      deps: ['jquery'],
      exports: 'hoverIntent'
    }
  },
  hbs : {
    templateExtension : 'html',
    disableI18n : true
  },
  waitSeconds: 60
},
[
  'app/main'
], function (app) {
  app.initialize();
});//end require()
