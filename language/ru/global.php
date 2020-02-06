<?php

//%%%%%%	File Name mainfile.php 	%%%%%
define('_PLEASEWAIT','Пожалуйста, подождите');
define('_FETCHING','Загрузка...');
define('_TAKINGBACK','Вернитесь назад, где были....');
define('_LOGOUT','Выход');
define('_SUBJECT','Заголовок');
define('_MESSAGEICON','Иконка сообщения');
define('_COMMENTS','Комментарии');
define('_POSTANON','Послать анонимно');
define('_DISABLESMILEY','Отключить смайлики');
define('_DISABLEHTML','Отключить html');
define('_PREVIEW','Просмотр');

define('_GO','Выполнить');
define('_NESTED','Уплотненный');
define('_NOCOMMENTS','Без комментариев');
define('_FLAT','Плоский');
define('_THREADED','Нитевидный');
define('_OLDESTFIRST','Старые первыми');
define('_NEWESTFIRST','Новые первыми');
define('_MORE','Далее...');
define('_IFNOTRELOAD','Если страница автоматически не перезагрузится, пожалуйста нажмите <a href=\'%s\'>сюда</a>');
define('_WARNINSTALL2','ПРЕДУПРЕЖДЕНИЕ: Каталог %s существует на Вашем сервере. <br />Пожалуйста, удалите этот каталог из соображений безопасности.');
define('_WARNINWRITEABLE','ПРЕДУПРЕЖДЕНИЕ: Файл %s доступен для записи. <br />Пожалуйста, измените права доступа к этому файлу из соображений безопасности.<br /> В Unix (444), в Win32 (только чтение)');
define('_WARNINNOTWRITEABLE','ПРЕДУПРЕЖДЕНИЕ: Файл %s недоступен для записи. <br />Пожалуйста, измените права доступа к этому файлу из соображений функциональности.<br /> В Unix (777), в Win32 (запись)');

// Error messages issued by icms_core_Object::cleanVars()
define( '_XOBJ_ERR_REQUIRED', 'Требуется %s' );
define( '_XOBJ_ERR_SHORTERTHAN', '%s должен быть короче %d символов.' );

//%%%%%%	File Name themeuserpost.php 	%%%%%
define('_PROFILE','Профиль');
define('_POSTEDBY','Отправитель');
define('_VISITWEBSITE','Посетить web-сайт');
define('_SENDPMTO','Отправить личное сообщение %s');
define('_SENDEMAILTO','Отправить Email %s');
define('_ADD','Добавить');
define('_REPLY','Ответить');
define('_DATE','Дата');   // Posted date

//%%%%%%	File Name admin_functions.php 	%%%%%
define('_MAIN','Главная');
define('_MANUAL','Руководство');
define('_INFO','Информация');
define('_CPHOME','Панель управления');
define('_YOURHOME','Главная');

//%%%%%%	File Name misc.php (who's-online popup)	%%%%%
define('_WHOSONLINE','Кто в онлайне');
define('_GUESTS', 'Гости');
define('_MEMBERS', 'Участники');
define('_ONLINEPHRASE','<b>%s</b> пользователь(ей) в онлайне');
define('_ONLINEPHRASEX','<b>%s</b> пользователь(ей) просматривают <b>%s</b>');
define('_CLOSE','Закрыть');  // Close window

//%%%%%%	File Name module.textsanitizer.php 	%%%%%
define('_QUOTEC','Quote:');

//%%%%%%	File Name admin.php 	%%%%%
define("_NOPERM","Извините, Вы не имеете разрешения на доступ в эту зону.");

//%%%%%		Common Phrases		%%%%%
define("_NO","Нет");
define("_YES","Да");
define("_EDIT","Редактировать");
define("_DELETE","Удалить");
define("_SUBMIT","Выполнить");
define("_MODULENOEXIST","Выбранный модуль не существует!");
define("_ALIGN","Выравнивание");
define("_LEFT","Слева");
define("_CENTER","По центру");
define("_RIGHT","Справа");
define("_FORM_ENTER", "Пожалуйста, введите %s");
// %s represents file name
define("_MUSTWABLE","Файл %s должен быть доступен на запись сервером!");
// Module info
define('_PREFERENCES', 'Настройки');
define("_VERSION", "Версия");
define("_DESCRIPTION", "Описание");
define("_ERRORS", "Ошибки");
define("_NONE", "Ничего");
define('_ON','on');
define('_READS','прочтений');
define('_SEARCH','Поиск');
define('_ALL', 'Все');
define('_TITLE', 'Заголовок');
define('_OPTIONS', 'Опции');
define('_QUOTE', 'Quote');
define('_HIDDENC', 'Скрытый контент:');
define('_HIDDENTEXT', 'This content is hidden for anonymous users, please <a href="'.ICMS_URL.'/register.php" title="Registration at ' . htmlspecialchars ( $icmsConfig ['sitename'], ENT_QUOTES ) . '">register</a> to be able to see it.');
define('_LIST', 'Список');
define('_LOGIN','Вход');
define('_USERNAME','Имя пользователя: ');
define('_PASSWORD','Пароль: ');
define("_SELECT","Выбор");
define("_IMAGE","Рисунок");
define("_SEND","Отправить");
define("_CANCEL","Отменить");
define("_ASCENDING","По возрастанию");
define("_DESCENDING","По убывания");
define('_BACK', 'Назад');
define('_NOTITLE', 'Нет заголовка');

/* Image manager */
define('_IMGMANAGER','Менеджер рисунков');
define('_NUMIMAGES', '%s рисунков');
define('_ADDIMAGE','Добавить файл рисунка');
define('_IMAGENAME','Имя:');
define('_IMGMAXSIZE','Макс допустимый размер (байты):');
define('_IMGMAXWIDTH','Макс допустимая ширина (пикселы):');
define('_IMGMAXHEIGHT','Макс допустимая высота (пикселы):');
define('_IMAGECAT','Категория:');
define('_IMAGEFILE','Файл рисунка:');
define('_IMGWEIGHT','Порядок отображения в менеджере рисунков:');
define('_IMGDISPLAY','Показать этот рисунок?');
define('_IMAGEMIME','MIME тип:');
define('_FAILFETCHIMG', 'Не могу взять загруженный файл %s');
define('_FAILSAVEIMG', 'Ошибка сохранения рисунка %s в базу данных');
define('_NOCACHE', 'Не кэшировать');
define('_CLONE', 'Клонировать');
define('_INVISIBLE', 'Скрыть');

//%%%%%	File Name class/xoopsform/formmatchoption.php 	%%%%%
define("_STARTSWITH", "Начинается с");
define("_ENDSWITH", "Заканчивается");
define("_MATCHES", "Совпадения");
define("_CONTAINS", "Содержит");

//%%%%%%	File Name commentform.php 	%%%%%
define("_REGISTER","Регистрация");

//%%%%%%	File Name xoopscodes.php 	%%%%%
define("_SIZE","РАЗМЕР");  // font size
define("_FONT","ШРИФТ");  // font family
define("_COLOR","ЦВЕТ");  // font color
define("_EXAMPLE","ПРИМЕР");
define("_ENTERURL","Введите адрес ссылки которую Вы хотите добавить:");
define("_ENTERWEBTITLE","Введите название сайта:");
define("_ENTERIMGURL","Введите адрес рисунка который Вы хотите добавить.");
define("_ENTERIMGPOS","Теперь введите расположение рисунка.");
define("_IMGPOSRORL","'R' или 'r' если справа, 'L' или 'l' если слева, или оставьте поле пустым.");
define("_ERRORIMGPOS","ОШИБКА! Введите расположение рисунка.");
define("_ENTEREMAIL","Введите e-mail адрес который Вы хотите добавить.");
define("_ENTERCODE","Введите коды, которые Вы хотите добавить.");
define("_ENTERQUOTE","Введите текст, который вы хотите цитировать.");
define("_ENTERHIDDEN","Введите текст, который вы хотите скрыть от гостей.");
define("_ENTERTEXTBOX","Пожалуйста, введите текст в поле ввода.");

//%%%%%		TIME FORMAT SETTINGS   %%%%%
define('_SECOND', '1 секунда');
define('_SECONDS', '%s секунд');
define('_MINUTE', '1 минута');
define('_MINUTES', '%s минут');
define('_HOUR', '1 час');
define('_HOURS', '%s часов');
define('_DAY', '1 день');
define('_DAYS', '%s дней');
define('_WEEK', '1 неделя');
define('_MONTH', '1 месяц');

define("_DATESTRING","d.m.Y H:i");
define("_MEDIUMDATESTRING","d.m.Y H:i");
define("_SHORTDATESTRING","d.m.Y");
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
define('_LANGCODE', 'ru');

// change 0 to 1 if this language is a multi-bytes language
define("XOOPS_USE_MULTIBYTES", "0");
// change 0 to 1 if this language is a RTL (right to left) language
define("_ADM_USE_RTL","0");

define('_MODULES','Модули');
define('_SYSTEM','Система');
define('_IMPRESSCMS_NEWS','Новости');
define('_ABOUT','Проект ImpressCMS');
define('_IMPRESSCMS_HOME','Главная');
define('_IMPRESSCMS_COMMUNITY','Сообщество');
define('_IMPRESSCMS_ADDONS','Дополнения');
define('_IMPRESSCMS_WIKI','Вики');
define('_IMPRESSCMS_BLOG','Блог');
define('_IMPRESSCMS_DONATE','Поддержите!');
define("_IMPRESSCMS_Support","Поддержите проект!");
define('_IMPRESSCMS_SOURCEFORGE','Проект на SourceForge');
define('_IMPRESSCMS_ADMIN','Администрирование ');
/** The default separator used in icms_view_Tree::getNicePathFromId */
define('_BRDCRMB_SEP','&nbsp;:&nbsp;');
//Content Manager
define('_CT_NAV','Главная');
define('_CT_RELATEDS','Связанные страницы');
//Security image (captcha)
define("_SECURITYIMAGE_GETCODE","Введите защитный код");
define("_WARNINGUPDATESYSTEM","Поздравляем, сейчас Вы провели обновление Вашего сайта до последней версии ImpressCMS!<br />Для завершения процесса апгрейда Вам необходимо кликнуть здесь и обновить системный модуль.<br />Нажать здесь для продолжения.");

// This shows local support site in ImpressCMS menu, (if selected language is not English)
define('_IMPRESSCMS_LOCAL_SUPPORT', 'http://www.impresscms.ru'); //add the local support site's URL
define('_IMPRESSCMS_LOCAL_SUPPORT_TITLE','Сайт локальной поддержки');
define("_ALLEFTCON","Введите текст, который будет распологаться слева.");
define("_ALCENTERCON","Введите текст, который будет распологаться в центре.");
define("_ALRIGHTCON","Введите текст, который будет распологаться справа.");

define('_MODABOUT_ABOUT', 'О модуле');
// if you have troubles with this font on your language or it is not working, download tcpdf from: http://www.tecnick.com/public/code/cp_dpage.php?aiocp_dp=tcpdf and add the required font in libraries/tcpdf/fonts then write down the font name here. system will then load this font for your language.
define('_PDF_LOCAL_FONT', '');
define('_CALENDAR_TYPE',''); // this value is for the local calendar used in this system, if you're not sure about this leave this value as it is!
define('_CALENDAR','Календарь');
define('_RETRYPOST','Извините, время истекло. Желаете повторить снова ?'); // autologin hack GIJ

############# added since 1.2 #############
define('_QSEARCH','Quick Search');
define('_PREV','Prev');
define('_NEXT','След. >>');
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
define("_YEARMONTHDAY", "d.m.Y H:i");
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
define("_CREDITS", "Участники");
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
define('_NAME', 'Имя');

define('_OR', 'или');
