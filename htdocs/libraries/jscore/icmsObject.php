<?php
/*
  * Instantiate new javascript core
  * @author: William Hall
  * @copyright The ImpressCMS Project http://www.impresscms.org/
  * @license   LICENSE.txt
  * @license   GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
  * @package   core
  * @since   2.0
*/

global $icmsConfigMetaFooter;

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
  'i18n' => isset($icmsConfig['i18n']) ? $icmsConfig['i18n'] === false ? false : array(
    'locale' => $icmsConfig['i18n']
  ) : false,
  'adminMenu' => false,
  'showProjectMenu' => false,
  'onlineCount' => '0',
  'membersOnline' => '0',
  'social' => array(
    'fbAppId' => isset($icmsConfig['fbAppId']) ? $icmsConfig['fbAppId'] : false,
    'fbChannel' => isset($icmsConfig['fbChannel']) ? $icmsConfig['fbChannel'] : false,
  ),
  'themeUrl' => $icmsConfig['xoops_url'] . '/themes/' . $icmsConfig['theme_set']
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

// Front Side ACP Menu
if (is_object(icms::$user)) {
  $icmsModule = icms::handler('icms_module')->getByDirname('system');
  if (icms::$user->isAdmin($icmsModule->getVar('mid'))) {
    if ( file_exists ( ICMS_CACHE_PATH . '/adminmenu_' . $icmsConfig ['language'] . '.php' )) {
      $file = file_get_contents(ICMS_CACHE_PATH . "/adminmenu_" . $icmsConfig ['language'] . ".php");
      $admin_menu = eval('return ' . $file . ';');
      $icmsJsConfigData['adminMenu'] = $admin_menu;
      $icmsJsConfigData['showProjectMenu'] = isset($icmsConfigPersona['show_impresscms_menu']) ? $icmsConfigPersona['show_impresscms_menu'] : true;

      global $icmsModule;
      $online_handler = icms::handler('icms_core_Online');
      mt_srand((double)microtime()*1000000);
      // set gc probabillity to 10% for now..
      if (mt_rand(1, 100) < 11) {
        $online_handler->gc(300);
      }
      if (is_object(icms::$user)) {
        $uid = icms::$user->getVar('uid');
        $uname = icms::$user->getVar('uname');
      } else {
        $uid = 0;
        $uname = '';
      }
      if (is_object($icmsModule)) {
        $online_handler->write($uid, $uname, time(), $icmsModule->getVar('mid'), $_SERVER['REMOTE_ADDR']);
      } else {
        $online_handler->write($uid, $uname, time(), 0, $_SERVER['REMOTE_ADDR']);
      }
      $onlines = $online_handler->getAll();
      if (FALSE !== $onlines) {
        $total = count($onlines);
        $guests = 0;
        for ($i = 0; $i < $total; $i++) {
          if ($onlines[$i]['online_uid'] == 0) {
            $guests++;
          }
        }
        $members = $total - $guests;
        $icmsJsConfigData['onlineCount'] = $total;
        $icmsJsConfigData['membersOnline'] = $members;
      }

    }
  }
}

$redirectMessage = (!empty($_SESSION['redirect_message'])) ? '"' . $_SESSION['redirect_message'] . '"' : 'false';
unset( $_SESSION['redirect_message'] );

$icmsTheme->addScript(NULL, array('type' => 'text/javascript'),
  'var icms = {' .
    'config: ' . json_encode($icmsJsConfigData) .
    ', user: ' . json_encode($icmsJsUserData) .
    ', module: {'.
      'lookup: function(mod, args, callback) {' . 
        '$.ajax({'.
          'url: icms.config.url + "/modules/" + mod + "/" + mod + ".php?"+args'.
          ', dataType: "json"'.
          ', success: function(data) {'.
            'icms.module[mod] = data;'.
            'if(typeof callback === "function") { callback(); }'.
          '}'.
        '});'.
      '}'.
    '}' .
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
, 'head', '-10');

$bootstrap = file_exists( ICMS_LIBRARIES_URL . '/jscore/bootstrap-built.js') ? ICMS_LIBRARIES_URL . '/jscore/bootstrap-built.js' : ICMS_LIBRARIES_URL . '/jscore/bootstrap.js';
$icmsTheme->addScript(ICMS_LIBRARIES_URL . '/jscore/lib/modernizr.js', array('type' => 'text/javascript'), '', 'head', '-2');
$icmsTheme->addScript(ICMS_LIBRARIES_URL . '/jscore/lib/require.js', array('type' => 'text/javascript', 'data-main' => $bootstrap, 'data-loaded' => 'icms_core' ), '', 'head', '-1');