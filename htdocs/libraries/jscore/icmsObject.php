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
  'uiTheme' => 'smoothness',
  'adminMenu' => false
);

$icmsJsUserData = array(
  'isUser' => (!icms::$user) ? 0 : 1,
  'uid' => (!icms::$user) ? 0 : icms::$user->vars['uid']['value'],
  'name' => (!icms::$user) ? 0 : icms::$user->vars['name']['value'],
  'uname' => (!icms::$user) ? 0 : icms::$user->vars['uname']['value'],
  'email' => (!icms::$user) ? 0 : icms::$user->vars['email']['value'],
  'user_avatar' => (!icms::$user) ? 0 : icms::$user->vars['user_avatar']['value'],
  'user_regdate' => (!icms::$user) ? 0 : icms::$user->vars['user_regdate']['value'],
  'posts' => (!icms::$user) ? 0 : icms::$user->vars['posts']['value'],
  'timezone_offset' => (!icms::$user) ? 0 : icms::$user->vars['timezone_offset']['value'],
  'last_login' => (!icms::$user) ? 0 : icms::$user->vars['last_login']['value'],
  'language' => (!icms::$user) ? 0 : icms::$user->vars['language']['value']
);

$redirectMessage = (!empty($_SESSION['redirect_message'])) ? '"' . $_SESSION['redirect_message'] . '"' : 'false';
unset( $_SESSION['redirect_message'] );

$xoTheme->addScript(NULL, array('type' => 'text/javascript'),
  'var icms = {' .
    'config: ' . json_encode($icmsJsConfigData) .
    ', user: ' . json_encode($icmsJsUserData) .
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
