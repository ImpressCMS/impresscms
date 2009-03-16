<?php
/**
* Password Quality Meeter
*
* This script create a password quality meeter in the register and change profile forms
* to make users use more secure passwords on the site.
* To activate the password meeter you need to go to System Admin >> Preferences >> User Settings and
* change the value of the Minimum security level field to something different of OFF (insecure).
* With the password meeter activated the users will be forced to use password in accordance with the 
* defined security level.
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.1
* @author		Rodrigo Pereira Lima (aka TheRplima) <therplima@impresscms.org>
* @version		$Id$
*/

function get_rnd_iv($iv_len)
{
   $iv = '';
   while ($iv_len-- > 0) {
      $iv .= chr(mt_rand() & 0xff);
   }
   return $iv;
}

function md5_encrypt($plain_text, $password, $iv_len = 16)
{
   $plain_text .= "x13";
   $n = strlen($plain_text);
   if ($n % 16) $plain_text .= str_repeat("{TEXTO}", 16 - ($n % 16));
   $i = 0;
   $enc_text = get_rnd_iv($iv_len);
   $iv = substr($password ^ $enc_text, 0, 512);
   while ($i < $n) {
      $block = substr($plain_text, $i, 16) ^ pack('H*', md5($iv));
      $enc_text .= $block;
      $iv = substr($block . $iv, 0, 512) ^ $password;
      $i += 16;
   }

   return base64_encode($enc_text);
}
function md5_decrypt($enc_text, $password, $iv_len = 16)
{
   $enc_text = base64_decode($enc_text);
   $n = strlen($enc_text);
   $i = $iv_len;
   $plain_text = '';
   $iv = substr($password ^ substr($enc_text, 0, $iv_len), 0, 512);
   while ($i < $n) {
      $block = substr($enc_text, $i, 16);
      $plain_text .= $block ^ pack('H*', md5($iv));
      $iv = substr($block . $iv, 0, 512) ^ $password;
      $i += 16;
   }
   return preg_replace('/\x13\x00*$/', '', $plain_text);
}

$senha = 'TheRplima';
$has_javascript = (isset($_GET['par']))?md5_decrypt($_GET['par'],$senha):null;
if(!isset($has_javascript)) {
  $valor_cript1 = md5_encrypt('ok_has_javascript', $senha);
  if ($_SERVER['QUERY_STRING'] == ""){
    echo '
    <script language="javascript">
    <!--
    window.location=window.location+"?par='.$valor_cript1.'";
    // -->
    </script>';
  }else{
    echo '
    <script language="javascript">
    <!--
    window.location="?par='.$valor_cript1.'"+"&'.$_SERVER['QUERY_STRING'].'";
    // -->
    </script>';
  }
  redirect_header('index.php',3,_US_REGFORM_NOJAVASCRIPT);
}
$config_handler =& xoops_gethandler('config');
$passConfig =& $config_handler->getConfigsByCat(2);
global $xoopsConfig;
echo '<script type="text/javascript" src="'.XOOPS_URL.'/include/passwordquality.js"></script>';

icms_loadLanguageFile('system', 'preferences', true);
$tipo = explode("/",$_SERVER['PHP_SELF']);
if ($tipo[count($tipo)-1] == 'register.php'){
  $passField = 'pass';
  $tipo = 1;
  $tipo1 = 1;
}else{
  $passField = 'password';
  $tipo = $xoopsUser->getVar('uname');
  $tipo1 = $xoopsUser->getVar('email');
}
echo '<script type="text/javascript">
/**
 * Variables to define the names of the quality levels (Insecure, Weak, Resonable, etc)
 */
var qualityName1 = "'._MD_AM_PASSLEVEL1.'";
var qualityName2 = "'._MD_AM_PASSLEVEL2.'";
var qualityName3 = "'._MD_AM_PASSLEVEL3.'";
var qualityName4 = "'._MD_AM_PASSLEVEL4.'";
var qualityName5 = "'._MD_AM_PASSLEVEL5.'";
var qualityName6 = "'._MD_AM_PASSLEVEL6.'";

var passField = "'.$passField.'";
var tipo = "'.$tipo.'";
var tipo1 = "'.$tipo1.'";

/**
 * Getting the settings defined in the ImpressCMS preferences about the password (min length and security level)
 */
var minpass = "'.$passConfig['minpass'].'";
var pass_level = "'.$passConfig['pass_level'].'";
</script>';

/**
 * Adding the password field with the password meeter
 */
if ($passField == 'pass'){
  /**
   * Regex rules to deterine the password level 
   */
  $reg_form->addElement(new XoopsFormHidden("regex",'[^0-9]'));      //Regex rule to get only the numeric digits on the password string
  $reg_form->addElement(new XoopsFormHidden("regex3",'([0-9])\1+')); //Regex rule to get only the numeric digits repeated and in sequence (i.e: 111222333) on the password string
  $reg_form->addElement(new XoopsFormHidden("regex1",'[0-9a-zA-Z]'));//Regex rule to get only the symbols on the password string
  $reg_form->addElement(new XoopsFormHidden("regex4",'(\W)\1+'));    //Regex rule to get only the symbols repeated and in sequence (i.e: {{{}}}]]]\\\) on the password string
  $reg_form->addElement(new XoopsFormHidden("regex2",'[^A-Z]'));     //Regex rule to get only the uppercase letters in the password string
  $reg_form->addElement(new XoopsFormHidden("regex5",'([A-Z])\1+')); //Regex rule to get only the uppercase letters repeated and in sequence (i.e: AAABBBCCC) on the password string

  $pass_tray = new XoopsFormElementTray(_US_PASSWORD, '');
  $pass_tray->setDescription(_US_REGFORM_WARNING);
  $pass_inp = new XoopsFormPassword('', $passField, 10, 72, $myts->htmlSpecialChars($pass));
  $pass_inp->setExtra('style="float:'._GLOBAL_LEFT.';"');
  $pass_tray->addElement($pass_inp, true);
  $div_progress = new XoopsFormLabel('',' <script language="javascript" src="'.XOOPS_URL.'/include/percent_bar'.(( defined('_ADM_USE_RTL') && _ADM_USE_RTL )?'_rtl':'').'.js"></script>');
  $pass_tray->addElement($div_progress);
  $reg_form->addElement($pass_tray);
}else{
  /**
   * Regex rules to deterine the password level 
   */
  $form->addElement(new XoopsFormHidden("regex",'[^0-9]'));      //Regex rule to get only the numeric digits on the password string
  $form->addElement(new XoopsFormHidden("regex3",'([0-9])\1+')); //Regex rule to get only the numeric digits repeated and in sequence (i.e: 111222333) on the password string
  $form->addElement(new XoopsFormHidden("regex1",'[0-9a-zA-Z]'));//Regex rule to get only the symbols on the password string
  $form->addElement(new XoopsFormHidden("regex4",'(\W)\1+'));    //Regex rule to get only the symbols repeated and in sequence (i.e: {{{}}}]]]\\\) on the password string
  $form->addElement(new XoopsFormHidden("regex2",'[^A-Z]'));     //Regex rule to get only the uppercase letters in the password string
  $form->addElement(new XoopsFormHidden("regex5",'([A-Z])\1+')); //Regex rule to get only the uppercase letters repeated and in sequence (i.e: AAABBBCCC) on the password string

  $pwd_text = new XoopsFormElementTray('', '');
  $pass_inp = new XoopsFormPassword('', $passField, 10, 72);
  $div_progress = new XoopsFormLabel('','<script language="javascript" src="'.XOOPS_URL.'/include/percent_bar'.(( defined('_ADM_USE_RTL') && _ADM_USE_RTL )?'_rtl':'').'.js"></script>');
  $pwd_text->addElement($pass_inp);
  $pwd_text->addElement($div_progress);
}
?>