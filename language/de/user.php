<?php

//%%%%%%		File Name user.php 		%%%%%
define('_US_NOTREGISTERED','Nicht registriert? Klicken Sie <a href="register.php">hier</a>.');
define('_US_LOSTPASSWORD','Passwort vergessen?');
define('_US_NOPROBLEM','Kein Problem: Geben Sie einfach die E-Mail-Adresse ein, die wir für Ihr Konto haben.');
define('_US_YOUREMAIL','Deine E-Mail: ');
define('_US_SENDPASSWORD','Passwort senden');
define('_US_LOGGEDOUT','Sie sind jetzt ausgeloggt');
define('_US_THANKYOUFORVISIT','Vielen Dank für Ihren Besuch auf unserer Website!');
define('_US_INCORRECTLOGIN','Falscher Login!');
define('_US_LOGGINGU','Vielen Dank für Ihre Anmeldung, %s.');
define('_US_RESETPASSWORD','Passwort vergessen');
define('_US_SUBRESETPASSWORD','Passwort zurücksetzen');
define('_US_RESETPASSTITLE','Dein Passwort ist abgelaufen!');
define('_US_RESETPASSINFO','Bitte füllen Sie das folgende Formular aus, um Ihr Passwort zurückzusetzen. Wenn Ihre E-Mail, Ihr Benutzername und Ihr aktuelles Passwort mit unserem Eintrag übereinstimmen, Ihr Passwort wird sofort geändert und Sie können sich wieder einloggen!');
define('_US_PASSEXPIRED','Ihr Passwort ist abgelaufen.<br />Sie werden nun zu einem Formular weitergeleitet, in dem Sie Ihr Passwort zurücksetzen können.');
define('_US_SORRYUNAMENOTMATCHEMAIL','Der eingegebene Loginname ist nicht mit der angegebenen E-Mail-Adresse vorhanden!');
define('_US_PWDRESET','Ihr Passwort wurde erfolgreich zurückgesetzt!');
define('_US_SORRYINCORRECTPASS','Sie haben Ihr aktuelles Passwort falsch eingegeben!');

// 2001-11-17 ADD
define('_US_NOACTTPADM','Der ausgewählte Benutzer wurde deaktiviert oder wurde noch nicht aktiviert.<br />Bitte kontaktieren Sie den Administrator für weitere Details.');
define('_US_ACTKEYNOT','Aktivierungsschlüssel nicht korrekt!');
define('_US_ACONTACT','Ausgewähltes Konto ist bereits aktiviert!');
define('_US_ACTLOGIN','Ihr Konto wurde aktiviert. Bitte melden Sie sich mit dem registrierten Passwort an.');
define('_US_NOPERMISS','Entschuldigung, du hast nicht die Berechtigung diese Aktion auszuführen!');
define('_US_SURETODEL','Sind Sie sicher, dass Sie Ihr Konto löschen möchten?');
define('_US_REMOVEINFO','Dadurch werden alle Ihre Daten aus unserer Datenbank entfernt.');
define('_US_BEENDELED','Ihr Konto wurde gelöscht.');
define('_US_REMEMBERME', 'Eingeloggt bleiben');

//%%%%%%		File Name register.php 		%%%%%
define('_US_USERREG','Benutzer-Registrierung');
define('_US_EMAIL','E-Mail');
define('_US_ALLOWVIEWEMAIL','Erlaube anderen Benutzern, meine E-Mail-Adresse zu sehen');
define('_US_WEBSITE','Webseite');
define('_US_TIMEZONE','Zeitzone');
define('_US_AVATAR','Profilbild');
define('_US_VERIFYPASS','Passwort überprüfen');
define('_US_SUBMIT','Absenden');
define('_US_LOGINNAME','Benutzername');
define('_US_FINISH','Beenden');
define('_US_REGISTERNG','Neuer Benutzer konnte nicht registriert werden.');
define('_US_MAILOK','Erhalten Sie gelegentliche E-Mail-Nachrichten von Administratoren und Moderatoren?');
define('_US_DISCLAIMER','Haftungsausschluss');
define('_US_IAGREE','Ich stimme dem oben genannten zu');
define('_US_UNEEDAGREE', 'Leider musst du unserem Haftungsausschluss zustimmen, um dich registrieren zu können.');
define('_US_NOREGISTER','Leider sind wir derzeit für neue Benutzerregistrierungen geschlossen');

// %s is username. This is a subject for email
define('_US_USERKEYFOR','Benutzer Aktivierungsschlüssel für %s');

define('_US_YOURREGISTERED','Sie sind jetzt registriert. Eine E-Mail mit einem Aktivierungsschlüssel wurde an das von Ihnen angegebene E-Mail-Konto gesendet. Bitte folgen Sie den Anweisungen in der E-Mail, um Ihr Konto zu aktivieren. ');
define('_US_YOURREGMAILNG','Du bist jetzt registriert. Aufgrund eines internen Fehlers auf unserem Server konnten wir die Aktivierungsmail jedoch nicht an Ihr E-Mail-Konto senden. Wir entschuldigen uns für die Unannehmlichkeiten, bitte senden Sie dem Webmaster eine E-Mail mit einer Benachrichtigung über die Situation.');
define('_US_YOURREGISTERED2','Du bist jetzt registriert. Bitte warte darauf, dass dein Account von den Administratoren aktiviert wird. Sie erhalten eine E-Mail, sobald Sie aktiviert sind. Dies kann eine Weile dauern, also haben Sie Geduld.');

// %s is your site name
define('_US_NEWUSERREGAT','Neue Benutzerregistrierung am %s');
// %s is a username
define('_US_HASJUSTREG','%s hat sich gerade registriert!');

define('_US_INVALIDMAIL','Fehler: Ungültige E-Mail-Adresse');
define('_US_INVALIDNICKNAME','FEHLER: Ungültiger Loginname, bitte versuchen Sie einen anderen Loginnamen.');
define('_US_NICKNAMETOOLONG','Benutzername ist zu lang. Er muss weniger als %s Zeichen lang sein.');
define('_US_NICKNAMETOOSHORT','Benutzername ist zu kurz. Er muss mehr als %s Zeichen lang sein.');
define('_US_NAMERESERVED','FEHLER: Name ist reserviert.');
define('_US_NICKNAMENOSPACES','Der Benutzername darf keine Leerzeichen enthalten.');
define('_US_LOGINNAMETAKEN','Fehler: Benutzername vergeben.');
define('_US_NICKNAMETAKEN','Fehler: Anzeigename vergeben.');
define('_US_EMAILTAKEN','FEHLER: E-Mail-Adresse ist bereits registriert.');
define('_US_ENTERPWD','FEHLER: Sie müssen ein Passwort eingeben.');
define('_US_SORRYNOTFOUND','Entschuldigung, es wurden keine entsprechenden Benutzerinformationen gefunden.');

define('_US_USERINVITE', 'Einladung zur Mitgliedschaft');
define('_US_INVITENONE','FEHLER: Die Registrierung erfolgt nur auf Einladung.');
define('_US_INVITEINVALID','FEHLER: Falscher Einladungscode.');
define('_US_INVITEEXPIRED','FEHLER: Einladungscode ist bereits verwendet oder abgelaufen.');

define('_US_INVITEBYMEMBER','Nur ein vorhandenes Mitglied kann neue Mitglieder einladen. Bitte fordern Sie eine Einladungs-E-Mail eines registrierten Mitglieds an.');
define('_US_INVITEMAILERR','Du bist jetzt registriert. Aufgrund eines internen Fehlers auf unserem Server konnten wir die Aktivierungsmail jedoch nicht an Ihr E-Mail-Konto senden. Wir entschuldigen uns für die Unannehmlichkeiten, bitte senden Sie dem Webmaster eine E-Mail mit einer Benachrichtigung über die Situation.');
define('_US_INVITEDBERR','Ihre Registrierungsanfrage konnte aufgrund eines internen Fehlers nicht bearbeitet werden. Wir entschuldigen uns für die Unannehmlichkeiten, bitte versuchen Sie es noch einmal, und wenn das Problem weiterhin besteht, senden Sie dem Webmaster eine E-Mail, in der er über die Situation informiert. <br />');
define('_US_INVITESENT','Eine E-Mail mit Registrierungslink wurde an das von Ihnen angegebene E-Mail-Konto gesendet. Bitte folgen Sie den Anweisungen in der E-Mail, um Ihr Konto zu registrieren. Dies kann einige Minuten dauern, also haben Sie Geduld.');
// %s is your site name
define('_US_INVITEREGLINK','Registrierungseinladung von %s');

// %s is your site name
define('_US_NEWPWDREQ','Neue Passwortanfrage bei %s');
define('_US_YOURACCOUNT', 'Ihr Konto bei %s');

define('_US_MAILPWDNG','mail_password: Benutzereintrag konnte nicht aktualisiert werden. Kontaktieren Sie den Administrator');
define('_US_RESETPWDNG','reset_password: Benutzereintrag konnte nicht aktualisiert werden. Kontaktieren Sie den Administrator');

define('_US_RESETPWDREQ','Passwortanfrage bei %s zurücksetzen');
define('_US_MAILRESETPWDNG','reset_password: Benutzereintrag konnte nicht aktualisiert werden. Kontaktieren Sie den Administrator');
define('_US_NEWPASSWORD','Passwort senden');
define('_US_YOURUSERNAME','Ihr Benutzername');
define('_US_CURRENTPASS','Ihr aktuelles Passwort');
define('_US_BADPWD','Falsches Passwort, Passwort darf keinen Benutzernamen enthalten.');

// %s is a username
define('_US_PWDMAILED','Passwort für %s gemailed.');
define('_US_CONFMAIL','Bestätigungsmail für %s gemailed.');
define('_US_ACTVMAILNG', 'Fehler beim Senden der Benachrichtigungsmail an %s');
define('_US_ACTVMAILOK', 'Benachrichtigungsmail an %s gesendet.');

//%%%%%%		File Name userinfo.php 		%%%%%
define('_US_SELECTNG','Kein Benutzer ausgewählt! Bitte gehen Sie zurück und versuchen Sie es erneut.');
define('_US_PM','PM');
define('_US_ICQ','ICQ');
define('_US_AIM','AIM');
define('_US_YIM','YIM');
define('_US_MSNM','MSNM');
define('_US_LOCATION','Wohnort');
define('_US_OCCUPATION','Beruf');
define('_US_INTEREST','Interessen');
define('_US_SIGNATURE','Signatur,');
define('_US_EXTRAINFO','zusätzliche Informationen');
define('_US_EDITPROFILE','Profil bearbeiten');
define('_US_LOGOUT','Abmelden');
define('_US_INBOX','Postfach');
define('_US_MEMBERSINCE','Mitglied seit');
define('_US_RANK','Rang');
define('_US_POSTS','Kommentare/Beiträge');
define('_US_LASTLOGIN','Letzter Login');
define('_US_ALLABOUT','Profil von %s');
define('_US_STATISTICS','Meine Statistiken');
define('_US_MYINFO','Über Mich');
define('_US_BASICINFO','Stammdaten');
define('_US_MOREABOUT','Mehr zu mir');
define('_US_SHOWALL','Alle anzeigen');

//%%%%%%		File Name edituser.php 		%%%%%
define('_US_PROFILE','Profil');
define('_US_REALNAME','Name');
define('_US_SHOWSIG','Meine Signatur immer hinzufügen');
define('_US_CDISPLAYMODE','Comments Display Mode');
define('_US_CSORTORDER','Comments Sort Order');
define('_US_PASSWORD','Password');
define('_US_TYPEPASSTWICE','(type a new password twice to change it)');
define('_US_SAVECHANGES','Save Changes');
define('_US_NOEDITRIGHT',"Sorry, you don't have the right to edit this user's info.");
define('_US_PASSNOTSAME','Both passwords are different. They must be identical.');
define('_US_PWDTOOSHORT','Sorry, your password must be at least <b>%s</b> characters long.');
define('_US_PROFUPDATED','Your Profile Updated!');
define('_US_USECOOKIE','Store my user name in a cookie for 1 year');
//define('_US_NO','No');
define('_US_DELACCOUNT','Delete Account');
define('_US_MYAVATAR', 'My Avatar');
define('_US_UPLOADMYAVATAR', 'Upload Avatar');
define('_US_MAXPIXEL','Max Pixels');
define('_US_MAXIMGSZ','Max Image Size (Bytes)');
define('_US_SELFILE','Select file');
define('_US_OLDDELETED','Your old avatar will be deleted!');
define('_US_CHOOSEAVT', 'Choose avatar from the available list');
define('_US_SELECT_THEME', 'Default Theme');
define('_US_SELECT_LANG', 'Default Language');

define('_US_PRESSLOGIN', 'Press the button below to login');

define('_US_ADMINNO', 'User in the webmasters group cannot be removed');
define('_US_GROUPS', 'User\'s Groups');

define('_US_YOURREGISTRATION', 'Your registration at %s');
define('_US_WELCOMEMSGFAILED', 'Error while sending the welcome email.');
define('_US_NEWUSERNOTIFYADMINFAIL', 'Notification to admin about new user registration failed.');
define('_US_REGFORM_NOJAVASCRIPT', 'To log in at the site it\'s necessary that your browser has javascript enabled.');
define('_US_REGFORM_WARNING', 'To register at the site you need to use a secure password. Try to create your password by using a mixture of letters (upper and lowercase), numbers and symbols. Try to create a password the more complex as possible although you can remember it.');
define('_US_CHANGE_PASSWORD', 'Change Password?');
define('_US_POSTSNOTENOUGH','Sorry, at least you need to have <b>%s</b> posts, to be able to upload your avatar.');
define('_US_UNCHOOSEAVT', 'Until you reach this amount you can choose avatar from the list below.');


define('_US_SERVER_PROBLEM_OCCURRED','There was an issue while checking for spammers list!');
define('_US_INVALIDIP','ERROR: This IP adress is not allowed to register');

######################## Added in 1.2 ###################################
define('_US_LOGIN_NAME', "Login Name");
define('_US_OLD_PASSWORD', "Old Password");
define('_US_NICKNAME','Display Name');
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
define("_MA_USER_LEVEL_ACTIVE", "Aktiv");
define("_MA_USER_LEVEL_INACTIVE", "Inactive");
define("_MA_USER_LEVEL_DISABLED", "Disabled");
define("_MA_USER_RANK", "Rang");

define("_MA_USER_FINDUS","Find Users");
define("_MA_USER_REALNAME","Name");
define("_MA_USER_REGDATE","Joined Date");
define("_MA_USER_EMAIL","Email");
define("_MA_USER_PREVIOUS","Vorherige");
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
define("_MA_USER_ASC","Ascending order");
define("_MA_USER_DESC","Descending order");
define("_MA_USER_LIMIT","Number of users per page");
define("_MA_USER_RESULTS", "Search results");
define("_MA_USER_SHOWMAILOK", "Type of users to show");
define("_MA_USER_MAILOK","Only users that accept mail");
define("_MA_USER_MAILNG","Only users that don't accept mail");
define("_MA_USER_BOTH", "All");

define("_MA_USER_RANGE_LAST_LOGIN","Logged in past <span style='color:#ff0000;'>X</span>days");
define("_MA_USER_RANGE_USER_REGDATE","Registered in past <span style='color:#ff0000;'>X</span>days");
define("_MA_USER_RANGE_POSTS","Beiträge");

define("_MA_USER_HASAVATAR", "Has avatar");
define("_MA_USER_MODE_SIMPLE", "Simple mode");
define("_MA_USER_MODE_ADVANCED", "Advanced mode");
define("_MA_USER_MODE_QUERY", "Query mode");
define("_MA_USER_QUERY", "Query");

define("_MA_USER_SEARCHAGAIN", "Search again");
define("_MA_USER_NOUSERSELECTED", "No user selected");
define("_MA_USER_USERADDED", "Users have been added");

define("_MA_USER_SENDMAIL","Send Email");
