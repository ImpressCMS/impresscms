<?php

//%%%%%%		File Name user.php 		%%%%%
define('_US_NOTREGISTERED','Você ainda não está registrado? Clique <a href=register.php>aqui</a> para fazer seu cadastro.');
define('_US_LOSTPASSWORD','Esqueceu a senha?');
define('_US_NOPROBLEM','Digite o e-mail que você usou para cadastrar-se no site.');
define('_US_YOUREMAIL','Seu e-mail: ');
define('_US_SENDPASSWORD','Enviar senha');
define('_US_LOGGEDOUT','Você saiu do site.');
define('_US_THANKYOUFORVISIT','Obrigado por sua visita.');
define('_US_INCORRECTLOGIN','Dados incorretos!');
define('_US_LOGGINGU','Obrigado por entrar no site, %s.');
define('_US_RESETPASSWORD','Redefinir sua senha');
define('_US_SUBRESETPASSWORD','Redefinir senha');
define('_US_RESETPASSTITLE','Sua senha expirou!');
define('_US_RESETPASSINFO','Por favor, preencha o formulário a seguir para redefinir sua senha. Se o seu e-mail, nome de usuário e senha atual for informada em nosso registro, sua senha será mudada instantaneamente e você será capaz de efetuar o login novamente!');
define('_US_PASSEXPIRED','Sua senha expirou.<br />Você irá agora ser redirecionado para um formulário onde você poderá redefinir a sua senha.');
define('_US_SORRYUNAMENOTMATCHEMAIL','O nome de usuário que você digitou não está associado com o  Endereço de e-mail!');
define('_US_PWDRESET','Sua senha foi redefinida com sucesso!');
define('_US_SORRYINCORRECTPASS','Você digitou a senha atual incorreta!');

// 2001-11-17 ADD
define('_US_NOACTTPADM','O registro do usuário selecionado foi desativado ou ainda não foi ativado.<br/>Entre em contato com o administrador para maiores informações.');
define('_US_ACTKEYNOT','A código de ativação <b>não</b> está correto.');
define('_US_ACONTACT','A conta selecionada está ativa.');
define('_US_ACTLOGIN','Seu cadastro foi ativado.<br />Você já pode entrar no site com a senha escolhida.');
define('_US_NOPERMISS','Você não tem permissão para executar esta operação.');
define('_US_SURETODEL','Tem certeza de que deseja excluir seu cadastro?');
define('_US_REMOVEINFO','Esta ação irá excluir todas as suas informações do nosso banco de dados.');
define('_US_BEENDELED','O seu cadastro foi excluído!');
define('_US_REMEMBERME', 'Lembrar?');

//%%%%%%		File Name register.php 		%%%%%
define('_US_USERREG','Registro');
define('_US_EMAIL','e-mail');
define('_US_ALLOWVIEWEMAIL','Permitir que outros visitantes vejam meu e-mail.');
define('_US_WEBSITE','Site');
define('_US_TIMEZONE','Fuso-horário');
define('_US_AVATAR','Avatar');
define('_US_VERIFYPASS','Verificar senha');
define('_US_SUBMIT','Ok');
define('_US_LOGINNAME','Username');
define('_US_FINISH','Finalizar');
define('_US_REGISTERNG','Não foi possível cadastrar o novo usuário');
define('_US_MAILOK','Podemos entrar em contato com você via e-mail?');
define('_US_DISCLAIMER','Termo de Responsabilidade');
define('_US_IAGREE','Concordo com o descrito acima');
define('_US_UNEEDAGREE', 'Para cadastrar-se é obrigatória a concordí¢ncia com o Termo de Responsabilidade.');
define('_US_NOREGISTER','O registro de novos usuários está suspenso.');

// %s is username. This is a subject for email
define('_US_USERKEYFOR','Este é o código de ativação para %s');

define('_US_YOURREGISTERED','Você está registrado.<br />Uma mensagem com o código de ativação foi enviada ao seu e-mail.<br />Siga as instruções do e-mail para ativar o seu cadastro.');
define('_US_YOURREGMAILNG','Você está registrado.<br />Entretanto, o e-mail com o código de ativação não pode ser enviado.<br />Contate o administrador do site para ativação do seu cadastro.');
define('_US_YOURREGISTERED2','Você está registrado.<br />Aguarde pela aprovação dos administradores do site.');

// %s is your site name
define('_US_NEWUSERREGAT','%s - Registro de novo usuário.');
// %s is a username
define('_US_HASJUSTREG','O usuário %s registrou-se no site.');

define('_US_INVALIDMAIL','ERRO: e-mail inválido');
define('_US_INVALIDNICKNAME','ERRO: Nome de usuário inválido');
define('_US_NICKNAMETOOLONG','O nome de usuário é muito extenso. Deve ter menos de 25 caracteres.');
define('_US_NICKNAMETOOSHORT','O nome de usuário é muito pequeno. Deve ser maior que %s caracteres.');
define('_US_NAMERESERVED','ERRO: este nome está reservado.');
define('_US_NICKNAMENOSPACES','O nome de usuário não pode conter espaços.');
define('_US_LOGINNAMETAKEN','ERROR: Username taken.');
define('_US_NICKNAMETAKEN','ERRO: O nome de usuário já está em uso.');
define('_US_EMAILTAKEN','ERRO: Este e-mail já está em uso.');
define('_US_ENTERPWD','ERRO: é necessário que você digite uma senha.');
define('_US_SORRYNOTFOUND','Não foi localizada nenhuma informação do usuário.');

define('_US_USERINVITE', 'Composição de convite');
define('_US_INVITENONE','ERRO: O registro é apenas por convite.');
define('_US_INVITEINVALID','ERRO: Código do convite está incorreto.');
define('_US_INVITEEXPIRED','ERRO: Código de convite deste usuário já existe ou está expirado.');

define('_US_INVITEBYMEMBER','Apenas um membro já existente pode convidar novos membros. Você deve solicitar um convite de algum outro membro registado por e-mail.');
define('_US_INVITEMAILERR','Não foi possível enviar o e-mail com link para o seu registro por e-mail, devido a um erro interno que ocorreu em nosso servidor. Pedimos desculpas pelo inconveniente, por favor, tente novamente e se o problema persistir, você deve entrar em contato com o webmaster ou enviar um e-mail notificando o que está acontecendo. <br />');
define('_US_INVITEDBERR','Não foi possível processar o seu pedido de registro devido a um erro interno. Pedimos desculpas pelo inconveniente, por favor, tente novamente e se o problema persistir, você deve entrar em contato com o webmaster ou enviar um e-mail notificando o que está acontecendo. <br />');
define('_US_INVITESENT','Um E-mail contendo um link com a inscrição foi enviado para o e-mail que você forneceu. Por favor, siga as instruções no e-mail para registrar a sua conta. Isto pode demorar alguns minutos por isso, seja paciente.');
// %s is your site name
define('_US_INVITEREGLINK','Registro de convite %s');

// %s is your site name
define('_US_NEWPWDREQ','Nova solicitação de senha para %s');
define('_US_YOURACCOUNT', 'O seu registro em %s');

define('_US_MAILPWDNG','Senha_email: não foi possível atualizar os dados do usuário. Entre em contato com o administrador do site.');
define('_US_RESETPWDNG','Redefinir_Senha: Não foi possível atualizar a sua informação de usuário. Entre em contato com o Administrador do Site');

define('_US_RESETPWDREQ','Pedido para Redefinir senha %s');
define('_US_MAILRESETPWDNG','Redefinir_Senha: Não foi possível atualizar a sua informação de usuário. Entre em contato com o Administrador do Site');
define('_US_NEWPASSWORD','Nova Senha');
define('_US_YOURUSERNAME','Usuário');
define('_US_CURRENTPASS','Sua Senha Atual');
define('_US_BADPWD','Senha ruim, não pode conter o seu nome de usuário como senha.');

// %s is a username
define('_US_PWDMAILED','A senha foi enviada para %s .');
define('_US_CONFMAIL','O e-mail de confirmação foi enviado para %s .');
define('_US_ACTVMAILNG', 'Erro no envio do e-mail com o código de ativação do cadastro para %s .');
define('_US_ACTVMAILOK', 'Um e-mail com o código de ativação foi enviado para %s .');

//%%%%%%		File Name userinfo.php 		%%%%%
define('_US_SELECTNG','Nenhum usuário selecionado.<br/>Volte e tente novamente.');
define('_US_PM','Mensagem Particular');
define('_US_ICQ','ICQ Instant Messenger');
define('_US_AIM','AOL Instant Messenger');
define('_US_YIM','Yahoo! Messenger');
define('_US_MSNM','MSN Messenger');
define('_US_LOCATION','Localidade');
define('_US_OCCUPATION','Ocupação');
define('_US_INTEREST','Interesses');
define('_US_SIGNATURE','Assinatura');
define('_US_EXTRAINFO','Informações extras');
define('_US_EDITPROFILE','Editar perfil');
define('_US_LOGOUT','Sair');
define('_US_INBOX','Caixa de entrada');
define('_US_MEMBERSINCE','Usuário desde');
define('_US_RANK','Posição');
define('_US_POSTS','Mensagens e Comentários');
define('_US_LASTLOGIN','última visita');
define('_US_ALLABOUT','Informações de %s');
define('_US_STATISTICS','Estatísticas');
define('_US_MYINFO','Informações pessoais');
define('_US_BASICINFO','Informações básicas');
define('_US_MOREABOUT','Mais sobre mim');
define('_US_SHOWALL','Exibir tudo');

//%%%%%%		File Name edituser.php 		%%%%%
define('_US_PROFILE','Perfil');
define('_US_REALNAME','Nome Completo');
define('_US_SHOWSIG','Sempre incluir a minha assinatura');
define('_US_CDISPLAYMODE','Modo de visualização dos comentários');
define('_US_CSORTORDER','Ordem de visualização dos comentários');
define('_US_PASSWORD','Senha');
define('_US_TYPEPASSTWICE','(digite a nova senha duas vezes para alterá-la)');
define('_US_SAVECHANGES','Salvar alterações');
define('_US_NOEDITRIGHT',"Você não tem permissão para editar as informações deste usuário.");
define('_US_PASSNOTSAME','Verifique se as senhas foram digitadas corretamente. Elas devem ser idênticas');
define('_US_PWDTOOSHORT','Sua senha precisa ter o mí­nimo de <b>%s</b> caracteres.');
define('_US_PROFUPDATED','O seu perfil foi atualizado.');
define('_US_USECOOKIE','Salvar meu nome de usuário em um cookie por um ano.');
//define('_US_NO','No');
define('_US_DELACCOUNT','Excluir cadastro');
define('_US_MYAVATAR', 'Meu avatar');
define('_US_UPLOADMYAVATAR', 'Enviar Avatar');
define('_US_MAXPIXEL','Tamanho máximo da imagem (em pixels)');
define('_US_MAXIMGSZ','Tamanho máximo da imagem (em bytes)');
define('_US_SELFILE','Selecione o arquivo');
define('_US_OLDDELETED','O avatar anterior será excluído.');
define('_US_CHOOSEAVT', 'Escolha um avatar disponível na lista');
define('_US_SELECT_THEME', 'Tema Escolhido');
define('_US_SELECT_LANG', 'Idioma padrão');

define('_US_PRESSLOGIN', 'Pressione o botão para entrar');

define('_US_ADMINNO', 'Administradores não podem ser excluídos.');
define('_US_GROUPS', 'Grupos de Usuários');

define('_US_YOURREGISTRATION', 'O seu registo em %s');
define('_US_WELCOMEMSGFAILED', 'Erro ao enviar as boas-vindas por e-mail.');
define('_US_NEWUSERNOTIFYADMINFAIL', 'Notificação para o Admin sobre o registro de um novo usuário falhou.');
define('_US_REGFORM_NOJAVASCRIPT', 'Para entrar no site é necessário que o seu navegador (browser) esteja ativado para permitir javascript.');
define('_US_REGFORM_WARNING', 'Para fazer o registrar no site você precisará usar uma senha segura. Experimente ao criar sua senha, uma mistura de letras (maiúsculas e minúsculas), números e símbolos. Tente criar uma senha complexa da melhor forma possível, para habilitar o botão gravar.');
define('_US_CHANGE_PASSWORD', 'Alterar senha?');
define('_US_POSTSNOTENOUGH','Desculpe, para executar esta operação, você precisa de pelo menos <b>%s</b> mensagens, para poder carregar o seu avatar.');
define('_US_UNCHOOSEAVT', 'Enquanto você não tem acesso para enviar o seu Avatar, você pode escolher um a partir da lista abaixo.');


define('_US_SERVER_PROBLEM_OCCURRED','Houve um problema ao verificar a lista de spammers!');
define('_US_INVALIDIP','ERRO: Este endereço IP não é permitido para cadastro');

######################## Added in 1.2 ###################################
define('_US_LOGIN_NAME', "Login Name");
define('_US_OLD_PASSWORD', "Old Password");
define('_US_NICKNAME','Nome de Usuário');
define('_US_MULTLOGIN', 'It was not possible to login on the site!! <br />
        <p align="left" style="color:red;">
        Possible causes:<br />
         - You are already logged in on the site.<br />
         - Someone else logged in on the site using your username and password.<br />
         - You left the site or close the browser window without clicking the logout button.<br />
        </p>
        Wait a few minutes and try again later. If the problems still persists contact the site administrator.');

// added in 1.3
define('_US_NOTIFICATIONS', "Notifications");

// relocated from finduser.php in 2.0
// formselectuser.php

define("_MA_USER_MORE", "Search users");
define("_MA_USER_REMOVE", "Remove unselected users");

//%%%%%%	File Name findusers.php 	%%%%%
define("_MA_USER_ADD_SELECTED", "Add selected users");

define("_MA_USER_GROUP", "Group");
define("_MA_USER_LEVEL", "Level");
define("_MA_USER_LEVEL_ACTIVE", "Ativo");
define("_MA_USER_LEVEL_INACTIVE", "Inactive");
define("_MA_USER_LEVEL_DISABLED", "Disabled");
define("_MA_USER_RANK", "Posição");

define("_MA_USER_FINDUS","Find Users");
define("_MA_USER_REALNAME","Nome Completo");
define("_MA_USER_REGDATE","Joined Date");
define("_MA_USER_EMAIL","Email");
define("_MA_USER_PREVIOUS","<< anterior");
define("_MA_USER_NEXT","Next");
define("_MA_USER_USERSFOUND","%s user(s) found");

define("_MA_USER_ACTUS", "Active Users: %s");
define("_MA_USER_INACTUS", "Inactive Users: %s");
define("_MA_USER_NOFOUND","No Users Found");
define("_MA_USER_UNAME","User Name");
define("_MA_USER_ICQ","ICQ Number");
define("_MA_USER_AIM","AIM Handle");
define("_MA_USER_YIM","YIM Handle");
define("_MA_USER_MSNM","MSNM Handle");
define("_MA_USER_LOCATION","Location contains");
define("_MA_USER_OCCUPATION","Occupation contains");
define("_MA_USER_INTEREST","Interest contains");
define("_MA_USER_URLC","URL contains");
define("_MA_USER_SORT","Sort by");
define("_MA_USER_ORDER","Order");
define("_MA_USER_LASTLOGIN","Last login");
define("_MA_USER_POSTS","Number of posts");
define("_MA_USER_ASC","Ordem crescente");
define("_MA_USER_DESC","Ordem decrescente");
define("_MA_USER_LIMIT","Number of users per page");
define("_MA_USER_RESULTS", "Search results");
define("_MA_USER_SHOWMAILOK", "Type of users to show");
define("_MA_USER_MAILOK","Only users that accept mail");
define("_MA_USER_MAILNG","Only users that don't accept mail");
define("_MA_USER_BOTH", "Todos");

define("_MA_USER_RANGE_LAST_LOGIN","Logged in past <span style='color:#ff0000;'>X</span>days");
define("_MA_USER_RANGE_USER_REGDATE","Registered in past <span style='color:#ff0000;'>X</span>days");
define("_MA_USER_RANGE_POSTS","Mensagens");

define("_MA_USER_HASAVATAR", "Has avatar");
define("_MA_USER_MODE_SIMPLE", "Simple mode");
define("_MA_USER_MODE_ADVANCED", "Advanced mode");
define("_MA_USER_MODE_QUERY", "Query mode");
define("_MA_USER_QUERY", "Query");

define("_MA_USER_SEARCHAGAIN", "Search again");
define("_MA_USER_NOUSERSELECTED", "No user selected");
define("_MA_USER_USERADDED", "Users have been added");

define("_MA_USER_SENDMAIL","Send Email");
