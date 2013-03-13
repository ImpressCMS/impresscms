/*
 @Module: log
 disabled console.log actions by default - logs are enabled when a url param of 'log' is true

 example of loggin turned on:
 http://www.fossil.com/webapp/wcs/stores/servlet/HomeView?langId=-1&storeId=12052&catalogId=25005&N=0&log=true

 @Method: initialize
 validates presence of logging param in url

 @Method: disableValidation
 stores window.console in noLog var - sets console.log to empty function

 @Method: enableValidation
 checks if loggin is disabled and resets it to original value if it is
*/
define(function(require) {
    var $ = require('jquery')
  , qs = require('util/mout/queryString')
  , browser = require('util/core/browser')
  , noLog = null
  , module = {
    initialize:function(){
      //Prevents IE from breaking on console entries.
      if (!window.console) {
        window.console = {
          log:function(){},
          warn:function(){}
        };
      }

      if(qs.contains('log') && qs.getParam('log') === true) {
        module.enableConsole();
      } else {
        module.disableConsole();
      }
    }

    , disableConsole: function() {
      noLog = window.console.log;
      window['console']['log'] = function() {};
    }

    , enableConsole: function() {
      if(noLog === null) {
        return;
      } else {
        window['console']['log'] = noLog;
      }
    }
    };

    return module;
});