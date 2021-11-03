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
define('_US_CDISPLAYMODE','Kommentaranzeige');
define('_US_CSORTORDER','Reihenfolge der Kommentare');
define('_US_PASSWORD','Passwort');
define('_US_TYPEPASSTWICE','(Geben Sie zweimal ein neues Passwort ein, um es zu ändern)');
define('_US_SAVECHANGES','Änderungen speichern');
define('_US_NOEDITRIGHT',"Sie haben leider nicht das Recht, die Benutzerinformationen zu bearbeiten.");
define('_US_PASSNOTSAME','Beide Passwörter sind unterschiedlich, sie müssen identisch sein.');
define('_US_PWDTOOSHORT','Ihr Passwort muss mindestens <b>%s</b> Zeichen lang sein.');
define('_US_PROFUPDATED','Dein Profil wurde aktualisiert!');
define('_US_USECOOKIE','Speichere meinen Benutzernamen in einem Cookie für ein Jahr');
//define('_US_NO','No');
define('_US_DELACCOUNT','Konto löschen');
define('_US_MYAVATAR', 'Mein Profilbild');
define('_US_UPLOADMYAVATAR', 'Profilbild hochladen');
define('_US_MAXPIXEL','Max Pixels');
define('_US_MAXIMGSZ','Maximale Bildgröße (Bytes)');
define('_US_SELFILE','Datei auswählen');
define('_US_OLDDELETED','Dein alter Avatar wird gelöscht!');
define('_US_CHOOSEAVT', 'Avatar aus der verfügbaren Liste auswählen');
define('_US_SELECT_THEME', 'Standard-Theme');
define('_US_SELECT_LANG', 'Standardsprache');

define('_US_PRESSLOGIN', 'Zum Anmelden auf den Button unten drücken');

define('_US_ADMINNO', 'Benutzer in der Webmasters Gruppe kann nicht entfernt werden');
define('_US_GROUPS', 'Benutzer-Gruppen');

define('_US_YOURREGISTRATION', 'Ihre Anmeldung bei %s');
define('_US_WELCOMEMSGFAILED', 'Fehler beim Senden der Willkommens-E-Mail.');
define('_US_NEWUSERNOTIFYADMINFAIL', 'Benachrichtigung an Admin über neue Benutzerregistrierung fehlgeschlagen.');
define('_US_REGFORM_NOJAVASCRIPT', 'Um sich auf der Website anzumelden, ist es notwendig, dass Ihr Browser Javascript aktiviert hat.');
define('_US_REGFORM_WARNING', 'Um sich auf der Website zu registrieren, müssen Sie ein sicheres Passwort verwenden. Versuchen Sie, Ihr Passwort mit einer Mischung aus Buchstaben (Groß- und Kleinbuchstaben), Zahlen und Symbolen zu erstellen. Versuchen Sie, ein Passwort zu erstellen, je komplexer wie möglich, obwohl Sie es merken können.');
define('_US_CHANGE_PASSWORD', 'Passwort ändern?');
define('_US_POSTSNOTENOUGH','Entschuldigung, mindestens benötigst du <b>%s</b> Beiträge, um deinen Avatar hochladen zu können.');
define('_US_UNCHOOSEAVT', 'Bis Sie diesen Betrag erreicht haben, können Sie Avatar aus der Liste unten auswählen.');


define('_US_SERVER_PROBLEM_OCCURRED','Beim Überprüfen der Spammerliste ist ein Problem aufgetreten!');
define('_US_INVALIDIP','FEHLER: Diese IP-Adresse darf sich nicht registrieren');

######################## Added in 1.2 ###################################
define('_US_LOGIN_NAME', "Benutzername");
define('_US_OLD_PASSWORD', "Altes Passwort");
define('_US_NICKNAME','Anzeigename');
define('_US_MULTLOGIN', 'Es war nicht möglich, sich auf der Website anzumelden!! <br />
        <p align="left" style="color:red;">
        Mögliche Ursachen:<br />
         - Sie sind bereits eingeloggt.<br />
         - Jemand anderes hat sich mit Ihrem Benutzernamen und Passwort auf der Website eingeloggt.<br />
         - Sie haben die Seite verlassen oder das Browser-Fenster geschlossen, ohne auf den Logout-Button zu klicken.<br />
        </p>
        Warte einige Minuten und versuche es später erneut. Wenn die Probleme weiterhin bestehen, kontaktieren Sie den Administrator.');

// added in 1.3
define('_US_NOTIFICATIONS', "Benachrichtigungen");

// relocated from finduser.php in 2.0
// formselectuser.php

define("_MA_USER_MORE", "Benutzer suchen");
define("_MA_USER_REMOVE", "Nicht ausgewählte Nutzer entfernen");

//%%%%%%	File Name findusers.php 	%%%%%
define("_MA_USER_ADD_SELECTED", "Ausgewählte Benutzer hinzufügen");

define("_MA_USER_GROUP", "Gruppen");
define("_MA_USER_LEVEL", "Level");
define("_MA_USER_LEVEL_ACTIVE", "Aktiv");
define("_MA_USER_LEVEL_INACTIVE", "Inaktiv");
define("_MA_USER_LEVEL_DISABLED", "Deaktiviert");
define("_MA_USER_RANK", "Rang");

define("_MA_USER_FINDUS","Benutzer finden");
define("_MA_USER_REALNAME","Name");
define("_MA_USER_REGDATE","Anmeldedatum");
define("_MA_USER_EMAIL","E-Mail");
define("_MA_USER_PREVIOUS","Vorherige");
define("_MA_USER_NEXT","Nächste");
define("_MA_USER_USERSFOUND","%s Benutzer gefunden");

define("_MA_USER_ACTUS", "Aktive Benutzer: %s");
define("_MA_USER_INACTUS", "Inaktive Benutzer: %s");
define("_MA_USER_NOFOUND","Keine User gefunden");
define("_MA_USER_UNAME","Benutzername");
define("_MA_USER_ICQ","ICQ-Nummer");
define("_MA_USER_AIM","AIM-Handle");
define("_MA_USER_YIM","YIM-Handle");
define("_MA_USER_MSNM","MSNM-Handle");
define("_MA_USER_LOCATION","Ort enthält");
define("_MA_USER_OCCUPATION","Beruf enthält");
define("_MA_USER_INTEREST","Interessen");
define("_MA_USER_URLC","URL enthält");
define("_MA_USER_SORT","Sortieren nach");
define("_MA_USER_ORDER","Reihenfolge");
define("_MA_USER_LASTLOGIN","Letzter Login");
define("_MA_USER_POSTS","Anzahl der Beiträge");
define("_MA_USER_ASC","Aufsteigende Reihenfolge");
define("_MA_USER_DESC","Absteigende Reihenfolge");
define("_MA_USER_LIMIT","Anzahl der Nutzer pro Seite");
define("_MA_USER_RESULTS", "Suchergebnisse");
define("_MA_USER_SHOWMAILOK", "Benutzertyp anzeigen");
define("_MA_USER_MAILOK","Nur Benutzer, die E-Mails akzeptieren");
define("_MA_USER_MAILNG","Nur Benutzer, die keine E-Mail akzeptieren");
define("_MA_USER_BOTH", "Alle");

define("_MA_USER_RANGE_LAST_LOGIN","Eingeloggt in den letzten <span style='color:#ff0000;'>X</span>Tagen");
define("_MA_USER_RANGE_USER_REGDATE","Eingeloggt in den letzten <span style='color:#ff0000;'>X</span>Tagen");
define("_MA_USER_RANGE_POSTS","Beiträge");

define("_MA_USER_HASAVATAR", "Hat Avatar");
define("_MA_USER_MODE_SIMPLE", "Einfacher Modus");
define("_MA_USER_MODE_ADVANCED", "Erweiterter Modus");
define("_MA_USER_MODE_QUERY", "Abfragemodus");
define("_MA_USER_QUERY", "Abfrage");

define("_MA_USER_SEARCHAGAIN", "Erneute Suche");
define("_MA_USER_NOUSERSELECTED", "Kein Benutzer ausgewählt");
define("_MA_USER_USERADDED", "Benutzer wurden hinzugefügt");

define("_MA_USER_SENDMAIL","E-Mail senden");
