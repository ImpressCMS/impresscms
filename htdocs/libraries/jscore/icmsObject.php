<?php
/*
* Instantiate new javascript core
* Author: William Hall
* Author: Matt Anderson
*/
$icmsJsConfigData = array(
  'jscore' => ICMS_LIBRARIES_URL . '/jscore/',
  'sitename' => $icmsConfig['sitename'],
  'slogan' => $icmsConfig['slogan'],
  'language' => $icmsConfig['language'],
  'theme_set' => $icmsConfig['theme_set'],
  'theme_admin_set' => $icmsConfig['theme_admin_set'],
  'theme_set_allowed' => $icmsConfig['theme_set_allowed'],
  'path' => $icmsConfig['root_path'],
  'url' => $icmsConfig['xoops_url'],
  'imageset' => ICMS_IMAGES_SET_URL,
  'gaActive' => $icmsConfigMetaFooter['use_google_analytics'],
  'uiTheme' => 'smoothness'
);
$redirectMessage = (!empty($_SESSION['redirect_message'])) ? '"' . $_SESSION['redirect_message'] . '"' : 'false';
unset( $_SESSION['redirect_message'] );

$xoTheme->addScript(NULL, array('type' => 'text/javascript'),
  'var icms = {' .
    'config: ' . json_encode($icmsJsConfigData) .
    ', redirectMessage: ' . $redirectMessage .
  '}' . 
  ', routeReady = {' .
    'isResolved: false' .
    ', callback : {}' .
    ', done : function(callback, scope){' .
      'this.callback = callback;' .
      'this.scope = scope;' .
      'if(this.isResolved){' .
      ' this.call();' .
      '}' .
    '}' .
    ', resolve:function(){' .
      'this.isResolved = true;' .
      'if(typeof this.callback == "function"){' .
      ' this.call();' .
      '}' .
    '}' .
    ', call : function(){' .
    ' this.callback.call(this.scope);' .
    '}' .
  '};'
);
$xoTheme->addScript(ICMS_LIBRARIES_URL . '/jscore/lib/modernizr.js', array('type' => 'text/javascript'));
$xoTheme->addScript(ICMS_LIBRARIES_URL . '/jscore/lib/require.js', array('type' => 'text/javascript', 'data-main' => ICMS_LIBRARIES_URL . '/jscore/bootstrap.js' ));
