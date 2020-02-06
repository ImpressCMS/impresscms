<?php

//%%%%%%		File Name user.php 		%%%%%
define('_US_NOTREGISTERED','Вы не зарегистрированы?  Нажмите <a href=register.php>здесь</a>.');
define('_US_LOSTPASSWORD','Забыли свой пароль?');
define('_US_NOPROBLEM','Нет проблем. Просто введите e-mail Вашей учетной записи.');
define('_US_YOUREMAIL','Ваш e-mail адрес: ');
define('_US_SENDPASSWORD','Выслать пароль');
define('_US_LOGGEDOUT','Теперь Вы завершили сеанс');
define('_US_THANKYOUFORVISIT','Спасибо за визит на наш сайт!');
define('_US_INCORRECTLOGIN','Неверный вход!');
define('_US_LOGGINGU','Спасибо за визит на наш сайт, %s.');
define('_US_RESETPASSWORD','Сброс Вашего пароля');
define('_US_SUBRESETPASSWORD','Сброс пароля');
define('_US_RESETPASSTITLE','Срок Вашего пароля истек!');
define('_US_RESETPASSINFO','Пожалуйста заполните следующую форму заказа на сброс Вашего пароля. Если Ваш email, имя пользователя и текущий пароль соответствуют Вашей записи, пароль будет изменен и Вы сможете залогироваться вновь!');
define('_US_PASSEXPIRED','Срок Вашего пароля истек.<br />Сейчас Вы будете перенаправлены на форму, где Вы сможете сбросить Ваш пароль.');
define('_US_SORRYUNAMENOTMATCHEMAIL','Введенное имя пользователя не ассоциируется с Email адресом!');
define('_US_PWDRESET','Ваш пароль сброшен (очищен)!');
define('_US_SORRYINCORRECTPASS','Вы неверно ввели Ваш текущий пароль!');

// 2001-11-17 ADD
define('_US_NOACTTPADM','Выбранный пользователь был дезактивирован или еще не был активирован.<br />Пожалуйста свяжитесь с администратором для уточнения.');
define('_US_ACTKEYNOT','Код активации не верен!');
define('_US_ACONTACT','Выбранная учетная запись уже активирована!');
define('_US_ACTLOGIN','Ваша учетная запись была активирована. Пожалуйста используйте имя и пароль введенные при регистрации.');
define('_US_NOPERMISS','Извините, Вы не имеете прав для выполнения этой операции!');
define('_US_SURETODEL','Вы уверены, что хотите удалить свою учетную запись?');
define('_US_REMOVEINFO','Это приведет к удалению всей информации о Вас из нашей базы данных.');
define('_US_BEENDELED','Ваша учетная запись была удалена.');
define('_US_REMEMBERME', 'Запомнить меня');

//%%%%%%		File Name register.php 		%%%%%
define('_US_USERREG','Регистрация пользователя');
define('_US_EMAIL','E-mail');
define('_US_ALLOWVIEWEMAIL','Разрешить другим пользователям видеть e-mail адрес');
define('_US_WEBSITE','Адрес сайта');
define('_US_TIMEZONE','Временная зона');
define('_US_AVATAR','Аватар');
define('_US_VERIFYPASS','Проверка пароля');
define('_US_SUBMIT','Выполнить');
define('_US_LOGINNAME','Имя пользователя');
define('_US_FINISH','Завершить');
define('_US_REGISTERNG','Не могу зарегистрировать нового пользователя.');
define('_US_MAILOK','Принимать рассылаемые иногда предупреждения<br /> от администраторов и модераторов сайта?');
define('_US_DISCLAIMER','Условия');
define('_US_IAGREE','Я согласен со сказанным выше');
define('_US_UNEEDAGREE', 'Извините, для регистрации Вы должны согласиться с нашими условиями.');
define('_US_NOREGISTER','Извините, на данный момент сайт не принимает новых регистраций.');

// %s is username. This is a subject for email
define('_US_USERKEYFOR','Код активации пользователя для %s');

define('_US_YOURREGISTERED','Теперь Вы зарегистрированы. На введенный Вами при регистрации E-mail адрес выслано сообщение содержащее ключ активации пользователя. Пожалуйста следуйте инструкциям, указанным в e-mail сообщении для активации учетной записи. ');
define('_US_YOURREGMAILNG','Теперь Вы зарегистрированы. Однако, мы не смогли выслать ключ активации на Ваш e-mail адрес по причине внутренней ошибки на нашем сервере. Мы приносим свои извинения за причиненные неудобства, пожалуйста свяжитесь с администратором сайта по e-mail для решения возникшей проблемы.');
define('_US_YOURREGISTERED2','Теперь Вы зарегистрированы. Пожалуйста подождите пока ваша учетная запись не будет активирована администратором. Вы получите e-mail сообщение когда это произойдет. Это может занять некоторое время, поэтому будьте терпеливы.');

// %s is your site name
define('_US_NEWUSERREGAT','Новая регистрация пользователя на сайте %s');
// %s is a username
define('_US_HASJUSTREG','%s был только-что зарегистрирован!');

define('_US_INVALIDMAIL','ОШИБКА: Неверный e-mail');
define('_US_INVALIDNICKNAME','ОШИБКА: Неверное имя пользователя');
define('_US_NICKNAMETOOLONG','Имя пользователя слишком длинное. Оно должно быть менее %s символов.');
define('_US_NICKNAMETOOSHORT','Имя пользователя слишком короткое. Оно должно быть более %s символов.');
define('_US_NAMERESERVED','ОШИБКА: Имя зарезервировано.');
define('_US_NICKNAMENOSPACES','Нельзя использовать пробелы в имени пользователя.');
define('_US_LOGINNAMETAKEN','ERROR: Username taken.');
define('_US_NICKNAMETAKEN','ОШИБКА: Это имя пользователя уже занято.');
define('_US_EMAILTAKEN','ОШИБКА: Такой e-mail адрес уже зарегистрирован.');
define('_US_ENTERPWD','ОШИБКА: Вы должны ввести пароль.');
define('_US_SORRYNOTFOUND','Извините, информация о введенном пользователе не найдена.');

define('_US_USERINVITE', 'Membership invitation');
define('_US_INVITENONE','ERROR: Registration is by invitation only.');
define('_US_INVITEINVALID','ERROR: Incorrect invitation code.');
define('_US_INVITEEXPIRED','ERROR: Invitation code is already used or expired.');

define('_US_INVITEBYMEMBER','Only an existing member can invite new members; please request an invitation email from some registered member.');
define('_US_INVITEMAILERR','We were unable to send the mail with registration link to your email account due to an internal error that had occurred on our server. We are sorry for the inconvenience, please try again and if problem persists, do send the webmaster an email notifying him/her of the situation. <br />');
define('_US_INVITEDBERR','We were unable to process your registration request due to an internal error. We are sorry for the inconvenience, please try again and if problem persists, do send the webmaster an email notifying him/her of the situation. <br />');
define('_US_INVITESENT','An email containing registration link has been sent to the email account you provided. Please follow the instructions in the mail to register your account. This could take few minutes so please be patient.');
// %s is your site name
define('_US_INVITEREGLINK','Registration invitation from %s');

// %s is your site name
define('_US_NEWPWDREQ','Запрос нового пароля на сайт %s');
define('_US_YOURACCOUNT', 'Ваша учетная запись на сайте %s');

define('_US_MAILPWDNG','mail_password: не могу обновить запись пользователя. Свяжитесь с администратором');
define('_US_RESETPWDNG','reset_password: невозможно обновить запись пользователя. Contact the Administrator');

define('_US_RESETPWDREQ','Запрос сброса пароля от %s');
define('_US_MAILRESETPWDNG','reset_password: невозможно обновить запись пользователя. Contact the Administrator');
define('_US_NEWPASSWORD','Новый пароль');
define('_US_YOURUSERNAME','Ваше имя');
define('_US_CURRENTPASS','Ваш текущий пароль');
define('_US_BADPWD','Плохой пароль, Пароль не может сожержать имя пользователя.');

// %s is a username
define('_US_PWDMAILED','Пароль для %s отправлен по e-mail.');
define('_US_CONFMAIL','E-mail подтверждение для %s отправлено.');
define('_US_ACTVMAILNG', 'Ошибка отправки e-mail сообщения для %s');
define('_US_ACTVMAILOK', 'E-mail сообщение для %s отправлено.');

//%%%%%%		File Name userinfo.php 		%%%%%
define('_US_SELECTNG','Не выбран пользователь! Пожалуйста вернитесь назад и попробуйте снова.');
define('_US_PM','Личные сообщения');
define('_US_ICQ','ICQ');
define('_US_AIM','AIM');
define('_US_YIM','YIM');
define('_US_MSNM','MSNM');
define('_US_LOCATION','Местожительство');
define('_US_OCCUPATION','Род занятий');
define('_US_INTEREST','Интересы');
define('_US_SIGNATURE','Подпись');
define('_US_EXTRAINFO','Дополнительная информация');
define('_US_EDITPROFILE','Редактировать');
define('_US_LOGOUT','Выход');
define('_US_INBOX','Сообщения');
define('_US_MEMBERSINCE','Дата регистрации');
define('_US_RANK','Ранг');
define('_US_POSTS','Комментариев/Сообщений');
define('_US_LASTLOGIN','Последний вход');
define('_US_ALLABOUT','Все о %s');
define('_US_STATISTICS','Статистика');
define('_US_MYINFO','Моя информация');
define('_US_BASICINFO','Основная информация');
define('_US_MOREABOUT','Дальше обо мне');
define('_US_SHOWALL','Показать все');

//%%%%%%		File Name edituser.php 		%%%%%
define('_US_PROFILE','Учетная запись');
define('_US_REALNAME','Настоящее имя');
define('_US_SHOWSIG','Всегда добавлять мою подпись');
define('_US_CDISPLAYMODE','Режим отображения комментариев');
define('_US_CSORTORDER','Порядок сортировки комментариев');
define('_US_PASSWORD','Пароль');
define('_US_TYPEPASSTWICE','(введите новый пароль дважды для его смены)');
define('_US_SAVECHANGES','Сохранить изменения');
define('_US_NOEDITRIGHT',"Извините, Вы не имеете прав для редактирования этой информации пользователя.");
define('_US_PASSNOTSAME','Введенные пароли различаются. Они должны быть идентичными.');
define('_US_PWDTOOSHORT','Извините, Ваш пароль должен содержать не менее <b>%s</b> символов.');
define('_US_PROFUPDATED','Ваша учетная запись обновлена!');
define('_US_USECOOKIE','Сохранить мое имя в "Cookie" на 1 год');
//define('_US_NO','No');
define('_US_DELACCOUNT','Удалить');
define('_US_MYAVATAR', 'Мой аватар');
define('_US_UPLOADMYAVATAR', 'Загрузка аватара');
define('_US_MAXPIXEL','Максимум точек');
define('_US_MAXIMGSZ','Максимальный размер рисунка (байт)');
define('_US_SELFILE','Выбор файла');
define('_US_OLDDELETED','Ваш старый аватар будет удален!');
define('_US_CHOOSEAVT', 'Выберите аватар из доступного списка');
define('_US_SELECT_THEME', 'Тема по умолчанию');
define('_US_SELECT_LANG', 'Язык по умолчанию');

define('_US_PRESSLOGIN', 'Нажмите кнопку для входа в систему');

define('_US_ADMINNO', 'Пользователь из группы вэбмастеров не может быть удалён');
define('_US_GROUPS', 'Группы пользователей');

define('_US_YOURREGISTRATION', 'Ваша регистрация на %s');
define('_US_WELCOMEMSGFAILED', 'Ощибка во время отправки приветственного email.');
define('_US_NEWUSERNOTIFYADMINFAIL', 'Сбой во время оповещения администратора о регистрации нового пользователя.');
define('_US_REGFORM_NOJAVASCRIPT', 'Чтобы войти в систему, Вам необходимо в браузере разрешить использование javascript.');
define('_US_REGFORM_WARNING', 'Для регистрации на сайте Вам необходимо использовать защищенный пароль. Создайте пароль, используя буквы (верхний или нижний регистр), числа и символы. Создайте пароль по-возможности более комплексный, но чтобы Вы смогли его запомнить.');
define('_US_CHANGE_PASSWORD', 'Изменить пароль?');
define('_US_POSTSNOTENOUGH','Извините, Вы должны иметь не менее <b>%s</b> постов, чтобы обновить Ваш аватар.');
define('_US_UNCHOOSEAVT', 'Вы можете выбрать аватар из списка.');


define('_US_SERVER_PROBLEM_OCCURRED','Проблема при проверке списка спамеров!');
define('_US_INVALIDIP','ОШИБКА: Этому IP-адресу не разрешено регистрироваться');

######################## Added in 1.2 ###################################
define('_US_LOGIN_NAME', "Login Name");
define('_US_OLD_PASSWORD', "Old Password");
define('_US_NICKNAME','Имя пользователя');
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
define("_MA_USER_LEVEL_ACTIVE", "Активный");
define("_MA_USER_LEVEL_INACTIVE", "Inactive");
define("_MA_USER_LEVEL_DISABLED", "Disabled");
define("_MA_USER_RANK", "Ранг");

define("_MA_USER_FINDUS","Find Users");
define("_MA_USER_REALNAME","Настоящее имя");
define("_MA_USER_REGDATE","Joined Date");
define("_MA_USER_EMAIL","E-mail");
define("_MA_USER_PREVIOUS","<< Пред.");
define("_MA_USER_NEXT","След. >>");
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
define("_MA_USER_ORDER","Порядок отображения в менеджере рисунков");
define("_MA_USER_LASTLOGIN","Last login");
define("_MA_USER_POSTS","Number of posts");
define("_MA_USER_ASC","По возрастанию");
define("_MA_USER_DESC","По убывания");
define("_MA_USER_LIMIT","Number of users per page");
define("_MA_USER_RESULTS", "Search results");
define("_MA_USER_SHOWMAILOK", "Type of users to show");
define("_MA_USER_MAILOK","Only users that accept mail");
define("_MA_USER_MAILNG","Only users that don't accept mail");
define("_MA_USER_BOTH", "Все");

define("_MA_USER_RANGE_LAST_LOGIN","Logged in past <span style='color:#ff0000;'>X</span>days");
define("_MA_USER_RANGE_USER_REGDATE","Registered in past <span style='color:#ff0000;'>X</span>days");
define("_MA_USER_RANGE_POSTS","Сообщений");

define("_MA_USER_HASAVATAR", "Has avatar");
define("_MA_USER_MODE_SIMPLE", "Simple mode");
define("_MA_USER_MODE_ADVANCED", "Advanced mode");
define("_MA_USER_MODE_QUERY", "Query mode");
define("_MA_USER_QUERY", "Query");

define("_MA_USER_SEARCHAGAIN", "Search again");
define("_MA_USER_NOUSERSELECTED", "No user selected");
define("_MA_USER_USERADDED", "Users have been added");

define("_MA_USER_SENDMAIL","Send Email");
