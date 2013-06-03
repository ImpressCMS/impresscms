/*
 @Module: log
 disabled console.log actions by default - logs are enabled when a url param of 'log' is true

 example of logging turned on:
 http://www.impresscms.org?log=true

 @Method: initialize
 validates presence of logging param in url

 @Method: disableValidation
 stores window.console in noConsole var - sets console.* to empty functions

 @Method: enableValidation
 checks if logging is disabled and resets it to original value if it is
*/
define(['jquery','util/mout/queryString'], function($, qs) {
    var noConsole = null
  , module = {
    initialize:function(){
      //Prevents IE from breaking on console entries.
      if (!window.console) {
        window.console = {
          log:function(){},
          warn:function(){}
        };
      }

      var url = window.location.href;
      if(qs.contains(url, 'console') && qs.getParam(url, 'console') === true) {
        module.enableConsole();
      } else {
        module.disableConsole();
      }
    }

    , disableConsole: function() {
      console.log('Logging disabled locally: use (?/&)console=true to enable');
      noConsole = window.console;
      window.console = {
        log: function () {},
        warn: function () {},
        error: function () {},
        dir: function () {},
        group: function () {},
        groupEnd: function () {},
        debug: function () {},
        info: function () {},
        assert: function () {},
        count: function () {},
        dirxml: function () {},
        profile: function () {},
        profileEnd: function () {},
        time: function () {},
        timeEnd: function () {},
        trace: function () {}
      };
    }

    , enableConsole: function() {
      if(noConsole === null) {
        return;
      } else {
        window.console = noConsole;
      }
    }
  };

  return module;
});