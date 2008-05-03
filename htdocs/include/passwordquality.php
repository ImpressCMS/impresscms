<?php
##################################################################################################
# Medidor de Qualidade de Senhas
# Tipo: Core Hack
# Descrição: Este hack cria um medidor de qualidade das senhas digitadas pelo usuário na hora do
# cadastro ou edição do perfil. Ele só habilita o envio do formulário caso a senha digitada estiver
# dentro do padrão definido na administração (Sistema=>Preferências=>Configuração dos usuários).
# Este hack não altera o formulário de cadastro/edição de usuários da administração pois é de se
# que o administrador do site que necessita usar um hack deste use senhas seguras na hora de criar
# os usuários.
##################################################################################################
# Rodrigo Pereira Lima aka TheRplima
# therplima@gmail.com
# Última Atualização: 16/09/2006
# Veja o hack funcionando aqui http://rwbanner.brinfo.com.br/register.php
##################################################################################################

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

include_once XOOPS_ROOT_PATH."/modules/system/language/".$xoopsConfig['language']."/admin/preferences.php";
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
//Texto dos nomes dos níveis de qualidade
var qualityName1 = "'._MD_AM_PASSLEVEL1.'";
var qualityName2 = "'._MD_AM_PASSLEVEL2.'";
var qualityName3 = "'._MD_AM_PASSLEVEL3.'";
var qualityName4 = "'._MD_AM_PASSLEVEL4.'";
var qualityName5 = "'._MD_AM_PASSLEVEL5.'";
var qualityName6 = "'._MD_AM_PASSLEVEL6.'";

var passField = "'.$passField.'";
var tipo = "'.$tipo.'";
var tipo1 = "'.$tipo1.'";

//Obtendo informações de configuração do xoops
var minpass = "'.$passConfig['minpass'].'";
var pass_level = "'.$passConfig['pass_level'].'";
</script>';

//Campo senha do formulário mais barra de progresso
if ($passField == 'pass'){
  //Regras Regex para filtrar senha digitada
  $reg_form->addElement(new XoopsFormHidden("regex",'[^0-9]'));      //Regex para filrar somente os digitos numéricos da string
  $reg_form->addElement(new XoopsFormHidden("regex3",'([0-9])\1+')); //Regex para filrar somente os digitos numéricos repetidos e em sequência da string
  $reg_form->addElement(new XoopsFormHidden("regex1",'[0-9a-zA-Z]'));//Regex para filtrar os símbolos da string
  $reg_form->addElement(new XoopsFormHidden("regex4",'(\W)\1+'));    //Regex para filtrar os símbolos repetidos e em sequência da string
  $reg_form->addElement(new XoopsFormHidden("regex2",'[^A-Z]'));     //Regex para filtrar as letras maiúsculas da string
  $reg_form->addElement(new XoopsFormHidden("regex5",'([A-Z])\1+')); //Regex para filtrar as letras maiúsculas repetidas e em sequência da string

  $pass_tray = new XoopsFormElementTray(_US_PASSWORD, '');
  $pass_tray->setDescription(_US_REGFORM_WARNING);
  $pass_inp = new XoopsFormPassword('', $passField, 10, 72, $myts->htmlSpecialChars($pass));
  $pass_inp->setExtra('style="float:left;"');
  $pass_tray->addElement($pass_inp);
  $div_progress = new XoopsFormLabel('',' <script language="javascript" src="'.XOOPS_URL.'/include/percent_bar.js"></script>');
  $pass_tray->addElement($div_progress);
  $reg_form->addElement($pass_tray);
}else{
  //Regras Regex para filtrar senha digitada
  $form->addElement(new XoopsFormHidden("regex",'[^0-9]'));      //Regex para filrar somente os digitos numéricos da string
  $form->addElement(new XoopsFormHidden("regex3",'([0-9])\1+')); //Regex para filrar somente os digitos numéricos repetidos e em sequência da string
  $form->addElement(new XoopsFormHidden("regex1",'[0-9a-zA-Z]'));//Regex para filtrar os símbolos da string
  $form->addElement(new XoopsFormHidden("regex4",'(\W)\1+'));    //Regex para filtrar os símbolos repetidos e em sequência da string
  $form->addElement(new XoopsFormHidden("regex2",'[^A-Z]'));     //Regex para filtrar as letras maiúsculas da string
  $form->addElement(new XoopsFormHidden("regex5",'([A-Z])\1+')); //Regex para filtrar as letras maiúsculas repetidas e em sequência da string

  $pwd_text = new XoopsFormElementTray('', '');
  $pass_inp = new XoopsFormPassword('', $passField, 10, 72);
  $div_progress = new XoopsFormLabel('','<script language="javascript" src="'.XOOPS_URL.'/include/percent_bar.js"></script>');
  $pwd_text->addElement($pass_inp);
  $pwd_text->addElement($div_progress);
}
?>