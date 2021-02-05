<?php

//%%%%%%	File Name mainfile.php 	%%%%%
define('_PLEASEWAIT','Aguarde...');
define('_FETCHING','Carregando...');
define('_TAKINGBACK','Retornando...');
define('_LOGOUT','Sair');
define('_SUBJECT','Assunto');
define('_MESSAGEICON','ícone da mensagem');
define('_COMMENTS','Comentários');
define('_POSTANON','Enviar anonimamente');
define('_DISABLESMILEY','Desativar emoticons');
define('_DISABLEHTML','Desativar HTML');
define('_PREVIEW','Exibir');

define('_GO','Ok!');
define('_NESTED','Ocultar');
define('_NOCOMMENTS','Ocultar comentários');
define('_FLAT','Desagrupar');
define('_THREADED','Agrupar');
define('_OLDESTFIRST','Antigos primeiro');
define('_NEWESTFIRST','Novos primeiro');
define('_MORE','mais...');
define('_IFNOTRELOAD','Se a página não recarregar automaticamente,<br/><a href=\'%s\'>clique aqui</a> para prosseguir.');
define('_WARNINSTALL2','<b>ATENÇÃO:</b> O Diretório %s existe no servidor ainda.<br/>Por motivos de segurança, <b>remova</b> este diretório.');
define('_WARNINWRITEABLE','<b>ATENÇÃO:</b> O arquivo %s <b>tem permissão de escrita</b> pelo servidor.<br/>Por motivos de segurança altere esta permissão.<br/>Em sistemas Unix use \'<b>CHMOD 444</b>\', em Windows ajuste para \'<b>somente leitura</b>\'.');
define('_WARNINNOTWRITEABLE','ATENÇÃO: Arquivo %s Não é gravável pelo servidor. <br />Mude a permissão deste arquivo de acordo com o tipo de servidor.<br /> no Unix (777), no Windows (writeable)');

// Error messages issued by icms_core_Object::cleanVars()
define( '_XOBJ_ERR_REQUIRED', '%s é obrigatório' );
define( '_XOBJ_ERR_SHORTERTHAN', '%s precisa ser menor do que %d caracteres.' );

//%%%%%%	File Name themeuserpost.php 	%%%%%
define('_PROFILE','Perfil');
define('_POSTEDBY','Enviado por');
define('_VISITWEBSITE','Visite');
define('_SENDPMTO','Enviar mensagem particular para %s');
define('_SENDEMAILTO','Enviar e-mail para %s');
define('_ADD','Incluir');
define('_REPLY','Responder');
define('_DATE','Data de envio');   // Posted date

//%%%%%%	File Name admin_functions.php 	%%%%%
define('_MAIN','Principal');
define('_MANUAL','Manual');
define('_INFO','Informações');
define('_CPHOME','Administração');
define('_YOURHOME','Página principal');

//%%%%%%	File Name misc.php (who's-online popup)	%%%%%
define('_WHOSONLINE','Usuários Online');
define('_GUESTS', 'Visitantes');
define('_MEMBERS', 'Usuários');
define('_ONLINEPHRASE','<b>%s</b> visitantes online');
define('_ONLINEPHRASEX','<b>%s</b> na seção: <b>%s</b>');
define('_CLOSE','Fechar');  // Close window

//%%%%%%	File Name module.textsanitizer.php 	%%%%%
define('_QUOTEC','Citando:');

//%%%%%%	File Name admin.php 	%%%%%
define("_NOPERM","Você não tem permissão para acessar esta área.");

//%%%%%		Common Phrases		%%%%%
define("_NO","Não");
define("_YES","Sim");
define("_EDIT","Editar");
define("_DELETE","Excluir");
define("_SUBMIT","Ok!");
define("_MODULENOEXIST","O módulo escolhido não existe.");
define("_ALIGN","Alinhar");
define("_LEFT","Esquerda");
define("_CENTER","Centro");
define("_RIGHT","Direita");
define("_FORM_ENTER", "Digite %s");
// %s represents file name
define("_MUSTWABLE","O arquivo %s deve ter permissão de gravação pelo servidor.");
// Module info
define('_PREFERENCES', 'Preferências');
define("_VERSION", "Versão");
define("_DESCRIPTION", "Descrição");
define("_ERRORS", "Erros");
define("_NONE", "Nenhum");
define('_ON','em');
define('_READS','leituras');
define('_SEARCH','Procurar');
define('_ALL', 'Todos');
define('_TITLE', 'Título');
define('_OPTIONS', 'Opções');
define('_QUOTE', 'Citar');
define('_HIDDENC', 'Esconder Conteúdo:');
define('_HIDDENTEXT', 'This content is hidden for anonymous users, please <a href="'.ICMS_URL.'/register.php" title="Registration at ' . htmlspecialchars ( $icmsConfig ['sitename'], ENT_QUOTES ) . '">register</a> to be able to see it.');
define('_LIST', 'Listar');
define('_LOGIN','Entrar');
define('_USERNAME','Usuário: ');
define('_PASSWORD','Senha: ');
define("_SELECT","Escolher");
define("_IMAGE","Imagem");
define("_SEND","Ok!");
define("_CANCEL","Cancelar");
define("_ASCENDING","Ordem crescente");
define("_DESCENDING","Ordem decrescente");
define('_BACK', 'Retornar');
define('_NOTITLE', 'Sem título');

/* Image manager */
define('_IMGMANAGER','Gestor de imagens');
define('_NUMIMAGES', '%s imagens');
define('_ADDIMAGE','Enviar uma nova imagem');
define('_IMAGENAME','Nome:');
define('_IMGMAXSIZE','Tamanho Máx.(bytes):');
define('_IMGMAXWIDTH','Largura Máx.(pixels):');
define('_IMGMAXHEIGHT','Altura Máx.(pixels):');
define('_IMAGECAT','Categoria:');
define('_IMAGEFILE','Arquivo da imagem:');
define('_IMGWEIGHT','Ordem de visualização das imagens:');
define('_IMGDISPLAY','Exibir esta imagem?');
define('_IMAGEMIME','Tipo MIME:');
define('_FAILFETCHIMG', 'Não foi possível enviar o arquivo %s');
define('_FAILSAVEIMG', 'Não foi possível incluir a imagem %s no banco de dados');
define('_NOCACHE', 'Sem cache');
define('_CLONE', 'Clonar');
define('_INVISIBLE', 'Invisível');

//%%%%%	File Name class/xoopsform/formmatchoption.php 	%%%%%
define("_STARTSWITH", "Inicia com");
define("_ENDSWITH", "Termina com");
define("_MATCHES", "Igual a");
define("_CONTAINS", "Contém");

//%%%%%%	File Name commentform.php 	%%%%%
define("_REGISTER","Registre-se");

//%%%%%%	File Name xoopscodes.php 	%%%%%
define("_SIZE","Tamanho");  // font size
define("_FONT","Fonte");  // font family
define("_COLOR","Côr");  // font color
define("_EXAMPLE","Exemplo");
define("_ENTERURL","Digite o endereço do link que você deseja incluir:");
define("_ENTERWEBTITLE","Digite o título do link:");
define("_ENTERIMGURL","Digite o endereço da imagem que você deseja incluir:");
define("_ENTERIMGPOS","Agora, digite a posição da figura.");
define("_IMGPOSRORL","'R' ou 'r' para direita (right), 'L' ou 'l' para esquerda (left), ou deixe vazio.");
define("_ERRORIMGPOS","ERRO: Digite a posição da imagem.");
define("_ENTEREMAIL","Digite o e-mail que você deseja incluir.");
define("_ENTERCODE","Digite os códigos que você deseja incluir.");
define("_ENTERQUOTE","Digite o texto para citação.");
define("_ENTERHIDDEN","Digite o texto que será escondido para usuários anônimos/visitantes.");
define("_ENTERTEXTBOX","Digite o texto na caixa de texto.");

//%%%%%		TIME FORMAT SETTINGS   %%%%%
define('_SECOND', '1 segundo');
define('_SECONDS', '%s segundos');
define('_MINUTE', '1 minuto');
define('_MINUTES', '%s minutos');
define('_HOUR', '1 hora');
define('_HOURS', '%s horas');
define('_DAY', '1 dia');
define('_DAYS', '%s dias');
define('_WEEK', '1 semana');
define('_MONTH', '1 mês');

define("_DATESTRING","d/m/Y H:i:s");
define("_MEDIUMDATESTRING","d/m/Y H:i");
define("_SHORTDATESTRING","d/n/Y");
/*
 The following characters are recognized in the format string:
 a - "am" or "pm"
 A - "AM" or "PM"
 d - day of the month, 2 digits with leading zeros; i.e. "01" to "31"
 D - day of the week, textual, 3 letters; i.e. "Fri"
 F - month, textual, long; i.e. "January"
 h - hour, 12-hour format; i.e. "01" to "12"
 H - hour, 24-hour format; i.e. "00" to "23"
 g - hour, 12-hour format without leading zeros; i.e. "1" to "12"
 G - hour, 24-hour format without leading zeros; i.e. "0" to "23"
 i - minutes; i.e. "00" to "59"
 j - day of the month without leading zeros; i.e. "1" to "31"
 l (lowercase 'L') - day of the week, textual, long; i.e. "Friday"
 L - boolean for whether it is a leap year; i.e. "0" or "1"
 m - month; i.e. "01" to "12"
 n - month without leading zeros; i.e. "1" to "12"
 M - month, textual, 3 letters; i.e. "Jan"
 s - seconds; i.e. "00" to "59"
 S - English ordinal suffix, textual, 2 characters; i.e. "th", "nd"
 t - number of days in the given month; i.e. "28" to "31"
 T - Timezone setting of this machine; i.e. "MDT"
 U - seconds since the epoch
 w - day of the week, numeric, i.e. "0" (Sunday) to "6" (Saturday)
 Y - year, 4 digits; i.e. "1999"
 y - year, 2 digits; i.e. "99"
 z - day of the year; i.e. "0" to "365"
 Z - timezone offset in seconds (i.e. "-43200" to "43200")
 */

//%%%%%		LANGUAGE SPECIFIC SETTINGS   %%%%%
define('_CHARSET', 'utf-8');
define('_LANGCODE', 'pt_BR');

// change 0 to 1 if this language is a multi-bytes language
define("XOOPS_USE_MULTIBYTES", "0");
// change 0 to 1 if this language is a RTL (right to left) language
define("_ADM_USE_RTL","0");

define('_MODULES','Módulos');
define('_SYSTEM','Sistema');
define('_IMPRESSCMS_NEWS','Novidades');
define('_ABOUT','Sobre o Projeto');
define('_IMPRESSCMS_HOME','Projeto ImpressCMS');
define('_IMPRESSCMS_COMMUNITY','Comunidade');
define('_IMPRESSCMS_ADDONS','Addons');
define('_IMPRESSCMS_WIKI','Wiki');
define('_IMPRESSCMS_BLOG','Blog');
define('_IMPRESSCMS_DONATE','Doações!');
define("_IMPRESSCMS_Support","Suporte do projeto !");
define('_IMPRESSCMS_SOURCEFORGE','SourceForge');
define('_IMPRESSCMS_ADMIN','Administração de');
/** The default separator used in icms_view_Tree::getNicePathFromId */
define('_BRDCRMB_SEP','&nbsp;:&nbsp;');
//Content Manager
define('_CT_NAV','Home');
define('_CT_RELATEDS','Páginas Relacionadas');
//Security image (captcha)
define("_SECURITYIMAGE_GETCODE","Digite o código de segurança");
define("_WARNINGUPDATESYSTEM","Parabéns, você acabou de atualizar o seu site com sucesso  para a versão mais nova do ImpressCMS! <br /> Mas para finalizar o processo de atualização você precisa clicar aqui e atualizar o seu módulo de Sistema. <br /> Clique aqui para o processo de atualização.");

// This shows local support site in ImpressCMS menu, (if selected language is not English)
define('_IMPRESSCMS_LOCAL_SUPPORT', 'http://br.impresscms.org'); //add the local support site's URL
define('_IMPRESSCMS_LOCAL_SUPPORT_TITLE','Suporte Brasileiro');
define("_ALLEFTCON","Digite o texto a ser alinhado à esquerda.");
define("_ALCENTERCON","Digite o texto a ser alinhado ao Centro.");
define("_ALRIGHTCON","Digite o texto a ser alinhado à direita.");

define('_MODABOUT_ABOUT', 'Sobre');
// if you have troubles with this font on your language or it is not working, download tcpdf from: http://www.tecnick.com/public/code/cp_dpage.php?aiocp_dp=tcpdf and add the required font in libraries/tcpdf/fonts then write down the font name here. system will then load this font for your language.
define('_PDF_LOCAL_FONT', '');
define('_CALENDAR_TYPE',''); // this value is for the local calendar used in this system, if you're not sure about this leave this value as it is!
define('_CALENDAR','Calendário');
define('_RETRYPOST','Desculpe, ocorreu um erro de time-out. Você gostaria de enviar novamente?'); // autologin hack GIJ

############# added since 1.2 #############
define('_QSEARCH','Quick Search');
define('_PREV','Prev');
define('_NEXT','Next');
define('_LCL_NUM0','0');
define('_LCL_NUM1','1');
define('_LCL_NUM2','2');
define('_LCL_NUM3','3');
define('_LCL_NUM4','4');
define('_LCL_NUM5','5');
define('_LCL_NUM6','6');
define('_LCL_NUM7','7');
define('_LCL_NUM8','8');
define('_LCL_NUM9','9');
// change 0 to 1 if your language has a different numbering than latin`s alphabet
define("_USE_LOCAL_NUM","0");
define("_ICMS_DBUPDATED","Database Updated Successfully!");
define('_MD_AM_DBUPDATED',_ICMS_DBUPDATED);

define('_TOGGLETINY','Toggle Editor');
define("_ENTERHTMLCODE","Enter the HTML codes that you want to add.");
define("_ENTERPHPCODE","Enter the PHP codes that you want to add.");
define("_ENTERCSSCODE","Enter the CSS codes that you want to add.");
define("_ENTERJSCODE","Enter the JavaScript codes that you want to add.");
define("_ENTERWIKICODE","Enter the wiki term that you want to add.");
define("_ENTERLANGCONTENT","Enter the text that you want to be in %s.");
define('_LANGNAME', 'English');
define('_ENTERYOUTUBEURL', 'Enter YouTube url:');
define('_ENTERHEIGHT', 'Enter frame\'s height');
define('_ENTERWIDTH', 'Enter frame\'s width');
define('_ENTERMEDIAURL', 'Enter media url:');
// !!IMPORTANT!! insert '\' before any char among reserved chars: "a", "A", "B", "c", "d", "D", "F", "g", "G", "h", "H", "i", "I", "j", "l", "L", "m", "M", "n", "O", "r", "s", "S", "t", "T", "U", "w", "W", "Y", "y", "z", "Z"
// insert double '\' before 't', 'r', 'n'
define("_TODAY", "	\\o\\d\\a\\y G:i");
define("_YESTERDAY", "\\Y\e\\s\\t\e\\r\\d\\a\\y G:i");
define("_MONTHDAY", "n/j G:i");
define("_YEARMONTHDAY", "d/m/Y H:i");
define("_ELAPSE", "%s ago");
define('_VISIBLE', 'Visible');
define('_UP', 'Up');
define('_DOWN', 'Down');
define('_CONFIGURE', 'Configure');

// Added in 1.2.2
define('_FILE_DELETED', 'File %s was deleted successfully');

// added in 1.3
define('_CHECKALL', 'Check all');
define('_COPYRIGHT', 'Copyright');
define("_LONGDATESTRING", "F jS Y, h:iA");
define('_AUTHOR', 'Author');
define("_CREDITS", "Credits");
define("_LICENSE", "License");
define("_LOCAL_FOOTER", 'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"https://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a><br />Hosting by <a href="http://www.siteground.com/impresscms-hosting.htm?afcode=7e9aa639d30265c079823a498f5b8f15">SiteGround</a>'); //footer Link to local support site
define("_BLOCK_ID", "Block ID");
define('_IMPRESSCMS_PROJECT','Project Development');

// added in 1.3.5
define("_FILTERS","Filters");
define("_FILTER","Filter");
define("_FILTERS_MSG1","Input Filter: ");
define("_FILTERS_MSG2","Input Filter (HTMLPurifier): ");
define("_FILTERS_MSG3","Output Filter: ");
define("_FILTERS_MSG4","Output Filter (HTMLPurifier): ");


// added in 2.0
define('_ENTER_MENTION', 'Enter the user name to mention:');
define( '_ENTER_HASHTAG', 'Enter the term(s) to tag:');
define('_NAME', 'Name');

define('_OR', 'or');
