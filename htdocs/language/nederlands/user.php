<?php
// $Id: user.php 9536 2009-11-13 18:59:32Z pesianstranger $
//%%%%%%		File Name user.php 		%%%%%
define('_US_NOTREGISTERED', 'Nog niet geregistreerd?  Klik <a href=register.php>hier</a>.');
define('_US_LOSTPASSWORD', 'Wachtwoord vergeten?');
define('_US_NOPROBLEM', 'Geen probleem. Vul het e-mailadres in waarmee u op deze website staat geregistreerd.');
define('_US_YOUREMAIL', 'E-mail: ');
define('_US_SENDPASSWORD', 'Verstuur wachtwoord');
define('_US_LOGGEDOUT', 'Uitgelogd');
define('_US_THANKYOUFORVISIT', 'Bedankt voor uw bezoek aan onze website!');
define('_US_INCORRECTLOGIN', 'Foute login!');
define('_US_LOGGINGU', '%s is ingelogd.');
define('_US_RESETPASSWORD', 'Reset uw wachtwoord');
define('_US_SUBRESETPASSWORD', 'Wachtwoord resetten');
define('_US_RESETPASSTITLE', 'Uw wachtwoord is verlopen!');
define('_US_RESETPASSINFO', 'Vult het onderstaande formulier in om uw wachtwoord te resetten. Wanneer uw e-mail, gebruikersnaam en huidige wachtwoord overeenkomen met ons bestand, dan zal uw wachtwoord direct worden gewijzigd waarna u weer kunt inloggen!');
define('_US_PASSEXPIRED', 'Uw wachtwoord is verlopen.<br />U wordt doorgestuurd naar een pagina waar u, uw wachtwoord kunt resetten.');
define('_US_SORRYUNAMENOTMATCHEMAIL', 'De opgegeven gebruikersnaam komt niet overeen met het gegeven e-mailadres!');
define('_US_PWDRESET', 'Uw wachtwoord is succesvol gereset!');
define('_US_SORRYINCORRECTPASS', 'U heeft uw huidige wachtwoord onjuist ingevoerd!');

// 2001-11-17 ADD
define('_US_NOACTTPADM', 'De geselecteerde gebruiker is niet actief of is nog niet geactiveerd.<br />Neem A.u.b contact op met de webmaster voor verdere details.');
define('_US_ACTKEYNOT', 'Activerings code is niet geldig!');
define('_US_ACONTACT', 'Geselecteerde account is al actief!');
define('_US_ACTLOGIN', 'Uw account is geactiveerd. Login met uw wachtwoord.');
define('_US_NOPERMISS', 'Sorry, u heeft geen rechten voor deze actie!');
define('_US_SURETODEL', 'Weet u zeker dat u dit account wilt verwijderen?');
define('_US_REMOVEINFO', 'Deze actie verwijdert alle betreffende data uit onze database!.');
define('_US_BEENDELED', 'Het account is verwijderd.');
define('_US_REMEMBERME', 'Onthoud mij');

//%%%%%%		File Name register.php 		%%%%%
define('_US_USERREG', 'Gebruikersregistratie');
define('_US_EMAIL', 'E-mail');
define('_US_ALLOWVIEWEMAIL', 'Sta anderen toe mijn e-mailadres te bekijken');
define('_US_WEBSITE', 'Website');
define('_US_TIMEZONE', 'Tijdzone');
define('_US_AVATAR', 'Pasfoto');
define('_US_VERIFYPASS', 'Verifieer wachtwoord');
define('_US_SUBMIT', 'Verzenden');
define('_US_LOGINNAME', 'Gebruikersnaam');
define('_US_FINISH', 'Volgende');
define('_US_REGISTERNG', 'Kon nieuwe gebruiker niet registreren.');
define('_US_MAILOK', 'Toestaan dat webmasters en<br /> moderators mij e-mail berichten mogen toesturen?');
define('_US_DISCLAIMER', 'Disclaimer');
define('_US_IAGREE', 'Ik ga akkoord met bovenstaande verklaring');
define('_US_UNEEDAGREE', 'Om u te kunnen registreren dient u akkoord te gaan met de disclaimer!');
define('_US_NOREGISTER', 'Sorry, er kunnen momenteel geen nieuwe gebruikersregistraties plaatsvinden.');


// %s is username. This is a subject for email
define('_US_USERKEYFOR', 'Gebruikers activeringscode voor %s');

define('_US_YOURREGISTERED', 'U bent nu geregistreerd. Een Email met een activeringscode is verzonden naar het e-mail adres dat u heeft opgegeven. Volg a.u.b. de instructies in de e-mail om uw account te activeren. ');
define('_US_YOURREGMAILNG', 'U bent nu geregistreerd. Door een interne serverfout hebben we geen email kunnen verzenden om uw account te activeren. Onze excuses voor dit ongemak. Stuur de webmaster een Email om hem/haar hiervan op de hoogte stellen.');
define('_US_YOURREGISTERED2', 'U bent nu geregistreerd.  Uw account moet worden geactiveerd door een beheerder. Hiervoor vragen wij uw geduld. Zodra uw account is geactiveerd ontvangt u daarvan een Emailbericht. Houd er rekening mee dat het even kan duren.');

// %s is your site name
define('_US_NEWUSERREGAT', 'Nieuwe gebruikersregistratie bij %s');
// %s is a username
define('_US_HASJUSTREG', '%s heeft zich net geregistreerd!');

define('_US_INVALIDMAIL', 'FOUT: Ongeldig emailadres');
define('_US_EMAILNOSPACES', 'FOUT: Email adressen hebben geen spaties.');
define('_US_INVALIDNICKNAME', 'FOUT: Ongeldige gebruikersnaam');
define('_US_NICKNAMETOOLONG', 'Gebruikersnaam is te lang. Het mogen niet meer dan %s karakters zijn.');
define('_US_NICKNAMETOOSHORT', 'Gebruikersnaam is te kort. Het moeten minstens %s karakters zijn.');
define('_US_NAMERESERVED', 'FOUT: Deze naam is gereserveerd.');
define('_US_NICKNAMENOSPACES', 'Gebruik geen spaties in uw gebrukersnaam.');
define('_US_LOGINNAMETAKEN', 'FOUT: Loginnaam is al in gebruik.');
define('_US_NICKNAMETAKEN', 'FOUT: Gebruikersnaam is al in gebruik.');
define('_US_EMAILTAKEN', 'FOUT: Dit emailadres is al in gebruik.');
define('_US_ENTERPWD', 'FOUT: U dient een wachtwoord in te voeren.');
define('_US_SORRYNOTFOUND', 'Sorry, de juiste gebruikers info is niet gevonden.');


define('_US_USERINVITE', 'Lidmaatschap uitnodiging');
define('_US_INVITENONE', 'FOUT: Registratie kan alleen maar op uitnodiging plaatsvinden.');
define('_US_INVITEINVALID', 'FOUT: Incorrecte uitnodigingscode.');
define('_US_INVITEEXPIRED', 'FOUT: Uitnodigingscode is al gebruikt of verlopen.');

define('_US_INVITEBYMEMBER', 'Alleen een bestaand lid kan nieuwe leden uitnodigen; verzoek alstublieft een bestaand lid om een uitnodigingsemail te verzenden.');
define('_US_INVITEMAILERR', 'Door een interne server fout was het niet mogelijk om een email aan u te verzenden met daarin de registratie link. Excusses voor het ongemak, probeert u het alstublieft nogmaals. Bestaat het probleem nog, neem dan contact op met de beheerder van de website om hem of haar op de hoogte te brengen van de situatie. <br />');
define('_US_INVITEDBERR', 'Door een interne fout was het niet mogelijk om u wregistratieverzoek uit te voeren. Excusses voor het ongemak, probeert u het alstublieft nogmaals. Bestaat het probleem nog, neem dan contact op met de beheerder van de website om hem of haar op de hoogte te brengen van de situatie. <br />');
define('_US_INVITESENT', 'Een e-mail, die de registratie link bevat, is verzonden naar het e-mailadres dat u heeft opgegeven. Volg alstublieft de instructie op die in de e-mail staan om u als lid te registreren. Dit kan een paar minuten duren, een ogenblik geduld alstublieft.');
// %s is your site name
define('_US_INVITEREGLINK', 'Uitnodiging om u te registreren op %s');


// %s is your site name
define('_US_NEWPWDREQ', 'Verzoek tot een nieuw wachtwoord voor %s');
define('_US_YOURACCOUNT', 'Uw account op %s');

define('_US_MAILPWDNG', 'mail_wachtwoord: Kan gebruiker niet bijwerken. Neem contact op met de webmaster.');
define('_US_RESETPWDNG', 'reset_wachtwoord: Kan gebruiker invoer niet bijwerken. Neem contact op met de webmaster');

define('_US_RESETPWDREQ', 'Verzoek wachtwoord reset op %s');
define('_US_MAILRESETPWDNG', 'reset_wachtwoord: Kan gebruiker invoer niet bijwerken. Neem contact op met de webmaster');
define('_US_NEWPASSWORD', 'Nieuw wachtwoord');
define('_US_YOURUSERNAME', 'Uw gebruikersnaam');
define('_US_CURRENTPASS', 'Uw huidige wachtwoord');
define('_US_BADPWD', 'Fout wachtwoord, het wachtwoord mag niet de gebruikersnaam bevatten.');

// %s is a username
define('_US_PWDMAILED', 'Wachtwoord van %s is verstuurd.');
define('_US_CONFMAIL', 'Bevestigings e-mail voor %s is verzonden.');
define('_US_ACTVMAILNG', 'Verzenden van mededelings e-mail naar %s is mislukt');
define('_US_ACTVMAILOK', 'Mededelings e-mail naar %s is verzonden.');

//%%%%%%		File Name userinfo.php 		%%%%%
define('_US_SELECTNG', 'Geen gebruiker geselecteerd! Ga terug en probeer het opnieuw.');
define('_US_PM', 'PM');
define('_US_ICQ', 'ICQ');
define('_US_AIM', 'AIM');
define('_US_YIM', 'YIM');
define('_US_MSNM', 'MSNM');
define('_US_LOCATION', 'Lokatie');
define('_US_OCCUPATION', 'Beroep');
define('_US_INTEREST', 'Interessen');
define('_US_SIGNATURE', 'Handtekening');
define('_US_EXTRAINFO', 'Aanvullende info');
define('_US_EDITPROFILE', 'Wijzig profiel');
define('_US_LOGOUT', 'Uitloggen');
define('_US_INBOX', 'Inbox');
define('_US_MEMBERSINCE', 'Lid sinds');
define('_US_RANK', 'Status');
define('_US_POSTS', 'Reacties/Postings');
define('_US_LASTLOGIN', 'Laatste login');
define('_US_ALLABOUT', 'Alles over %s');
define('_US_STATISTICS', 'Statistieken');
define('_US_MYINFO', 'Mijn info');
define('_US_BASICINFO', 'Basis informatie');
define('_US_MOREABOUT', 'Alles over mij');
define('_US_SHOWALL', 'Toon alles');

//%%%%%%		File Name edituser.php 		%%%%%
define('_US_PROFILE', 'Profiel');
define('_US_REALNAME', 'Echte naam');
define('_US_SHOWSIG', 'Gebruik altijd mijn handtekening');
define('_US_CDISPLAYMODE', 'Reactie weergave');
define('_US_CSORTORDER', 'Volgorde reactie(s)');
define('_US_PASSWORD', 'Wachtwoord');
define('_US_TYPEPASSTWICE', '(typ het nieuwe wachtwoord 2x om het te wijzigen)');
define('_US_SAVECHANGES', 'Verandering(en) Opslaan');
define('_US_NOEDITRIGHT', 'Sorry, u heeft geen rechten om deze gebruikers info aan te passen.');
define('_US_PASSNOTSAME', 'U heeft verschillende wachtwoorden ingevuld. Deze behoren hetzelfde te zijn!.');
define('_US_PWDTOOSHORT', 'Sorry, uw wachtwoord dient tenminste <b>%s</b> karakters te bezitten.');
define('_US_PROFUPDATED', 'Uw profiel is aangepast!');
define('_US_USECOOKIE', 'Bewaar mijn gebruikersnaam in een cookie voor 1 jaar');
define('_US_NO', 'Nee');
define('_US_DELACCOUNT', 'Verwijder account');
define('_US_MYAVATAR', 'Mijn pasfoto');
define('_US_UPLOADMYAVATAR', 'Upload pasfoto');
define('_US_MAXPIXEL', 'Max pixels');
define('_US_MAXIMGSZ', 'Max afbeeldingsgrootte (Bytes)');
define('_US_SELFILE', 'Selecteer bestand');
define('_US_OLDDELETED', 'Oude pasfoto zal worden verwijderd!');
define('_US_CHOOSEAVT', 'Kies een pasfoto (avatar) uit de beschikbare lijst');
define('_US_SELECT_THEME', 'Standaard thema');
define('_US_SELECT_LANG', 'Standaard taal');

define('_US_PRESSLOGIN', 'klik op de onderstaande knop om in te loggen');

define('_US_ADMINNO', 'Gebruikers in de webmastergroep kunnen niet verwijderd worden');
define('_US_GROUPS', 'Gebruikersgroepen');

define('_US_YOURREGISTRATION', 'Uw registratie op %s');
define('_US_WELCOMEMSGFAILED', 'Er is een fout opgetreden tijdens het versturen van de welkoms email.');
define('_US_NEWUSERNOTIFYADMINFAIL', 'Berichtgeving naar de website beheerder over een nieuwe gebruikersregistratie is mislukt.');
define('_US_REGFORM_NOJAVASCRIPT', 'Om in te loggen op de website is het noodzakelijk dat het gebruik van Java is ingeschakeld in uw browser.');
define('_US_REGFORM_WARNING', 'Om u te registreren op de website dient u een veilig wachtwoord te gebruiken. Probeer een wachtwoord te maken met letters en cijfers (Hoofd en kleine letters), nummers en symbolen. Probeer een zo complex mogelijk, maar toch te onthouden wachtwoord te maken.');
define('_US_CHANGE_PASSWORD', 'Wachtwoord wijzigen?');
define('_US_POSTSNOTENOUGH', 'Sorry, uw moet tenminste <b>%s</b> postingen/berichten hebben gedaan, om het uploaden van een eigen avatar/pasfoto mogelijk te maken.');
define('_US_UNCHOOSEAVT', 'Totdat u dit aantal bereikt kunt u een avatar/pasfoto uit de onderstaade lijst selecteren.');

// openid
define('_US_OPENID_NOPERM', 'Geen toegang.'); //No permission
define('_US_OPENID_FORM_CAPTION', 'OpenID');
define('_US_OPENID_FORM_DSC', '');
define('_US_OPENID_EXISTING_USER', 'Bestaande gebruiker');
define('_US_OPENID_EXISTING_USER_LOGIN_BELOW', 'Wanneer u een bestaande gebruiker bent, log dan hieronder in met uw gebruikersnaam en wachtwoord om uw gebruikersaccount te koppelen met deze OpenID.');
define('_US_OPENID_NOM_MEMBER', 'Nog geen account ?');
define('_US_OPENID_NON_MEMBER_DSC', 'Wanneer u nog geen gebruikersaccount op deze website heeft, vul dan alstublieft de door u gewenste gebruikersnaam in er wordt dan een gebruikersaccount aangemaakt dat gekoppeld is met deze OpenID.');
define('_US_OPENID_YOUR', 'Uw OpenID');
define('_US_OPENID_LINKED_AUTH_FAILED', 'De door u ingevoerde gebruikersnaam en wachtwoord komen niet overeen met een geldige gebruiker. Probeert u het alstublieft nogmaals.');
define('_US_OPENID_LINKED_AUTH_NOT_ACTIVATED', 'Het gebruikersaccount waarmee u inlogt is nog niet geactiveerd. Activeer uw accoutn en probeer het daarna nogmaals.');
define('_US_OPENID_LINKED_AUTH_CANNOT_SAVE', 'Sorry, er is een fout opgetreden. Het was niet mogelijk niet mogelijk om dit gebruikersaccount bij tewerken met de geauthenticeerde OpenID.');
define('_US_OPENID_NEW_USER_UNAME_TOO_SHORT', 'De gebruikersnaam die u heeft ingvuld is te kort. Probeert u het alstublieft nogmaals.');
define('_US_OPENID_NEW_USER_UNAME_EXISTS', 'De gebruikersnaam die u heeft ingvuld is reeds in gebruik. Probeert u het alstublieft nogmaals.');
define('_US_OPENID_NEW_USER_CANNOT_INSERT', 'Sorry, er is een fout opgetreden. Het was niet mogelijk de nieuwe gebruiker aan te maken. Probeert u het alstublieft nogmaals.');
define('_US_OPENID_NEW_USER_CANNOT_INSERT_INGROUP', 'Sorry, er is een fout opgetreden. Het was niet mogelijk de nieuwe gebruiker toe te voegen aan de juiste groep(en). Neemt alstublieft contact op met de beheerder van deze website.');
define('_US_OPENID_NEW_USER_AUTH_NOT_ACTIVATED', 'De nieuw aangemaakte gebruiker is niet geactiveerd.');
define('_US_OPENID_NEW_USER_CREATED', 'Een nieuwe gebruiker is aangemaakt met de gebruikersnaam %s. Uw wordt automatisch ingelogd...');
define('_US_OPENID_LINKED_DONE', 'Uw OpenID is gekoppeld met de gebruiker %S. Uw wordt ingelogd...');
define('_US_ALREADY_LOGED_IN', 'U bent reeds ingelogd. U kunt zich niet registreren zolang u bent ingelogd op deze website');
define('_US_ALLOWVIEWEMAILOPENID', 'Sta toe dat andere gebruikers mijn emailadres en OpenID kunnen zien');
define('_US_SERVER_PROBLEM_OCCURRED', 'Er was een probleem tijdens de controle tav spammers lijsten!');
define('_US_INVALIDIP', 'FOUT: Het is met dit IP adres niet toegestaan te registreren');

######################## Added in 1.2 ###################################
define('_US_LOGIN_NAME', "Loginnaam");
define('_US_OLD_PASSWORD', "Oude wachtwoord");
define('_US_NICKNAME', 'Weergave naam');
define('_US_MULTLOGIN', 'Het was niet mogelijk in te loggen op de website!! <br />
        <p align="left" style="color:red;">
        Mogelijke oorzaken:<br />
         - U bent reeds ingelogd op de website.<br />
         - Iemand anders is ingelogd op de website en gebruikt uw gebruikernaam en wachtwoord.<br />
         - U heeft de website verlaten of uw browser afgesloten zonder uit te loggen.<br />
        </p>
        Wacht een paar minuten en probeer het nogmaals. Wanneer het probleem zich nog voordoet neem dan contact op met de beheerder van de website.');
define("_US_OPENID_LOGIN", "Login met uw OpenID");
define("_US_OPENID_URL", "Uw OpenID URL:");
define("_US_OPENID_NORMAL_LOGIN", "Ga terug naar normale loginprocedure");
