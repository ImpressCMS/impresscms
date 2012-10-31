<?php

require_once dirname(dirname(__FILE__)).'/class/gtickets.php' ;
$myts =& icms_core_Textsanitizer::getInstance() ;
$db =& icms_db_Factory::instance() ;

// GET vars
$pos = empty( $_GET[ 'pos' ] ) ? 0 : intval( $_GET[ 'pos' ] ) ;
$num = empty( $_GET[ 'num' ] ) ? 20 : intval( $_GET[ 'num' ] ) ;

	// for RTL users
	@define( '_GLOBAL_LEFT' , @_ADM_USE_RTL == 1 ? 'right' : 'left' ) ;
	@define( '_GLOBAL_RIGHT' , @_ADM_USE_RTL == 1 ? 'left' : 'right' ) ;

// Table Name
$log_table = $db->prefix( $mydirname."_log" ) ;

// Protector object
require_once dirname(dirname(__FILE__)).'/class/protector.php' ;
$db =& icms_db_Factory::instance() ;
$protector =& Protector::getInstance( $db->conn ) ;
$conf = $protector->getConf() ;


//
// transaction stage
//

if( ! empty( $_POST['action'] ) ) {

	// Ticket check
	if ( ! $xoopsGTicket->check( true , 'protector_admin' ) ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}

	if( $_POST['action'] == 'update_ips' ) {
		$error_msg = '' ;

		$lines = empty( $_POST['bad_ips'] ) ? array() : explode( "\n" , trim( $_POST['bad_ips'] ) ) ;
		$bad_ips = array() ;
		foreach( $lines as $line ) {
			@list( $bad_ip , $jailed_time ) = explode( ':' , $line , 2 ) ;
			$bad_ips[ trim( $bad_ip ) ] = empty( $jailed_time ) ? 0x7fffffff : intval( $jailed_time ) ;
		}
		if( ! $protector->write_file_badips( $bad_ips ) ) {
			$error_msg .= _AM_MSG_BADIPSCANTOPEN ;
		}

		$group1_ips = empty( $_POST['group1_ips'] ) ? array() : explode( "\n" , trim( $_POST['group1_ips'] ) ) ;
		foreach( array_keys( $group1_ips ) as $i ) {
			$group1_ips[$i] = trim( $group1_ips[$i] ) ;
		}
		$fp = @fopen( $protector->get_filepath4group1ips() , 'w' ) ;
		if( $fp ) {
			@flock( $fp , LOCK_EX ) ;
			fwrite( $fp , serialize( array_unique( $group1_ips ) ) . "\n" ) ;
			@flock( $fp , LOCK_UN ) ;
			fclose( $fp ) ;
		} else {
			$error_msg .= _AM_MSG_GROUP1IPSCANTOPEN ;
		}

		$redirect_msg = $error_msg ? $error_msg : _AM_MSG_IPFILESUPDATED ;
		redirect_header( "index.php" , 2 , $redirect_msg ) ;
		exit ;

	} else if( $_POST['action'] == 'delete' && isset( $_POST['ids'] ) && is_array( $_POST['ids'] ) ) {
		// remove selected records
		foreach( $_POST['ids'] as $lid ) {
			$lid = intval( $lid ) ;
			$db->query( "DELETE FROM $log_table WHERE lid='$lid'" ) ;
		}
		redirect_header( "index.php" , 2 , _AM_MSG_REMOVED ) ;
		exit ;

	} else if( $_POST['action'] == 'deleteall' ) {
		// remove all records
		$db->query( "DELETE FROM $log_table" ) ;
		redirect_header( "index.php" , 2 , _AM_MSG_REMOVED ) ;
		exit ;

	} else if( $_POST['action'] == 'compactlog' ) {
		// compactize records (removing duplicated records (ip,type)
		$result = $db->query( "SELECT `lid`,`ip`,`type` FROM $log_table ORDER BY lid DESC" ) ;
		$buf = array() ;
		$ids = array() ;
		while( list( $lid , $ip , $type ) = $db->fetchRow( $result ) ) {
			if( isset( $buf[ $ip . $type ] ) ) {
				$ids[] = $lid ;
			} else {
				$buf[ $ip . $type ] = true ;
			}
		}
		$db->query( "DELETE FROM $log_table WHERE lid IN (".implode(',',$ids).")" ) ;
		redirect_header( "index.php" , 2 , _AM_MSG_REMOVED ) ;
		exit ;
	}
}


//
// display stage
//

// query for listing
$rs = $db->query( "SELECT count(lid) FROM $log_table" ) ;
list( $numrows ) = $db->fetchRow( $rs ) ;
$prs = $db->query( "SELECT l.lid, l.uid, l.ip, l.agent, l.type, l.description, UNIX_TIMESTAMP(l.timestamp), u.uname FROM $log_table l LEFT JOIN ".$db->prefix("users")." u ON l.uid=u.uid ORDER BY timestamp DESC LIMIT $pos,$num" ) ;

// Page Navigation
$nav = new icms_view_PageNav( $numrows , $num , $pos , 'pos' , "num=$num" ) ;
$nav_html = $nav->renderNav( 10 ) ;

// Number selection
$num_options = '' ;
$num_array = array( 20 , 100 , 500 , 2000 ) ;
foreach( $num_array as $n ) {
	if( $n == $num ) {
		$num_options .= "<option value='$n' selected='selected'>$n</option>\n" ;
	} else {
		$num_options .= "<option value='$n'>$n</option>\n" ;
	}
}

// beggining of Output
icms_cp_header();

// title
$moduleName = $xoopsModule->getVar('name');
echo "<div class='CPbigTitle' style='background-image: url(../images/iconbig_icms.png)'><a href='#'>" . $moduleName . "</a></div>\n";

include dirname(__FILE__).'/mymenu.php' ;

// configs writable check
if( ! is_writable(dirname(dirname(__FILE__)) . '/configs') ) {
  $writableError = "<div class='coreMessage errorMsg'>\n";
    $writableError .= "<p>\n";
      $writableError .= "<strong>" . _AM_FMT_CONFIGSNOTWRITABLE . "</strong><br />\n";
      $writableError .= "<span>" . dirname(dirname(__FILE__))  . "/configs</span>\n";
    $writableError .= "</p>\n";
  $writableError .= "</div>\n";

  echo $writableError;
}

// bad_ips
$bad_ips = $protector->get_bad_ips( true ) ;
uksort( $bad_ips , 'protector_ip_cmp' ) ;
$bad_ips4disp = '' ;
foreach( $bad_ips as $bad_ip => $jailed_time ) {
	$line = $jailed_time ? $bad_ip . ':' . $jailed_time : $bad_ip ;
	$line = str_replace( ':2147483647' , '' , $line ) ; // remove :0x7fffffff
	$bad_ips4disp .= htmlspecialchars( $line , ENT_QUOTES ) . "\n" ;
}

// group1_ips
$group1_ips = $protector->get_group1_ips() ;
usort( $group1_ips , 'protector_ip_cmp' ) ;
$group1_ips4disp = htmlspecialchars(implode("\n",$group1_ips),ENT_QUOTES) ;

// edit configs about IP ban and IPs for group=1
  $ipForm .= "<form name='ConfigForm' action='' method='post'>\n";
    $ipForm .= "<div class='icms-theme-form'>\n";
      $ipForm .= "<fieldset>\n";
        $ipForm .= $xoopsGTicket->getTicketHtml(__LINE__,1800,'protector_admin') . "\n";
        $ipForm .= "<input type='hidden' name='action' value='update_ips' />\n";
        $ipForm .= "<legend>" . $moduleName . "</legend>\n";
        $ipForm .= "<div class='icms-form-contents'>\n";

          $ipForm .= "<div class='fieldWrapper group-bad-ips'>\n";
            $ipForm .= "<label for='bad_ips'>" . _AM_TH_BADIPS . "</label>\n";
            $ipForm .= "<p class='ip_path'><span>" . htmlspecialchars($protector->get_filepath4badips()) . "</span></p>\n";
            $ipForm .= "<textarea name='bad_ips' id='bad_ips'>" . $bad_ips4disp . "</textarea>\n";
          $ipForm .= "</div>\n";

          $ipForm .= "<div class='fieldWrapper group-group1-ips'>\n";
            $ipForm .= "<label for='group1_ips'>" . _AM_TH_GROUP1IPS . "</label>\n";
            $ipForm .= "<p class='ip_path'><span>" . htmlspecialchars($protector->get_filepath4group1ips()) . "</span></p>\n";
            $ipForm .= "<textarea name='group1_ips' id='group1_ips'>" . $group1_ips4disp . "</textarea>\n";
          $ipForm .= "</div>\n";

          $ipForm .= "<div class='fieldWrapper group-button'>\n";
            $ipForm .= "<input type='submit' value='" . _GO . "' />\n";
          $ipForm .= "</div>\n";

        $ipForm .= "</div>\n";
      $ipForm .= "</fieldset>\n";
    $ipForm .= "</div>\n";
  $ipForm .= "</form>\n";

// Render $ipForm
echo $ipForm;


// header of log listing
$logHeader = "<div class='quickSearchBoxFilterWrapper'>\n";
  $logHeader .= "<form action='' method='GET'>\n";
    $logHeader .= "<div class='filterAndLimit'>\n";
      $logHeader .= "<div class='singleObject'>\n";
        $logHeader .= "<select name='num' onchange='submit();'>" . $num_options . "</select>\n";
        $logHeader .= "<input type='submit' value='" . _SUBMIT . "'>\n";
      $logHeader .= "</div>\n";
    $logHeader .= "</div>\n";

    $logHeader .= "<div class='pageNavWrapper'>\n";
      $logHeader .= $nav_html . "\n";
    $logHeader .= "</div>\n";
  $logHeader .= "</form>\n";
$logHeader .= "</div>\n";

// Render $logHeader
echo $logHeader;

// logTable
$logTable = "<form name='MainForm' action='' method='POST'>\n";
  $logTable .= "<div class='icms-table=form'>\n";
    $logTable .= $xoopsGTicket->getTicketHtml(__LINE__,1800,'protector_admin') . "\n";
    $logTable .= "<input type='hidden' name='action' value='' />\n";
    $logTable .= "<table width='95%' class='outer' cellpadding='4' cellspacing='1'>\n";
      $logTable .= "<tr valign='middle'>\n";
        $logTable .= "<th width='5'><input type='checkbox' name='dummy' onclick=\"with(document.MainForm){for(i=0;i<length;i++){if(elements[i].type=='checkbox'){elements[i].checked=this.checked;}}}\" /></th>\n";
        $logTable .= "<th>" . _AM_TH_DATETIME . "</th>\n";
        $logTable .= "<th>" . _AM_TH_USER . "</th>\n";
        $logTable .= "<th>" . _AM_TH_IP . "<br />" . _AM_TH_AGENT . "</th>\n";
        $logTable .= "<th>" . _AM_TH_TYPE . "</th>\n";
        $logTable .= "<th>" . _AM_TH_DESCRIPTION . "</th>\n";
      $logTable .= "</tr>\n";

      // body of log listing
      $oddeven = 'odd' ;
      while( list( $lid , $uid , $ip , $agent , $type , $description , $timestamp , $uname ) = $db->fetchRow( $prs ) ) {
      	$oddeven = ( $oddeven == 'odd' ? 'even' : 'odd' ) ;

      	$ip = htmlspecialchars( $ip , ENT_QUOTES ) ;
      	$type = htmlspecialchars( $type , ENT_QUOTES ) ;
      	$description = htmlspecialchars( $description , ENT_QUOTES ) ;
      	$uname = htmlspecialchars( ( $uid ? $uname : _GUESTS ) , ENT_QUOTES ) ;

      	// make agents shorter
      	if( preg_match( '/MSIE\s+([0-9.]+)/' , $agent , $regs ) ) {
      		$agent_short = 'IE ' . $regs[1] ;
      	} else if( stristr( $agent , 'Gecko' ) !== false ) {
      		$agent_short = strrchr( $agent , ' ' ) ;
      	} else {
      		$agent_short = substr( $agent , 0 , strpos( $agent , ' ' ) ) ;
      	}
      	$agent4disp = htmlspecialchars( $agent , ENT_QUOTES ) ;
      	$agent_desc = $agent == $agent_short ? $agent4disp : htmlspecialchars( $agent_short , ENT_QUOTES ) . "<img src='../images/dotdotdot.gif' alt='$agent4disp' title='$agent4disp' />" ;


        $logTable .= "<tr>\n";
          $logTable .= "<td class='" . $oddeven . "'><input type='checkbox' name='ids[]' value='" . $lid . "' /></td>\n";
          $logTable .= "<td class='" . $oddeven . "'>" . formatTimestamp($timestamp) . "</td>\n";
          $logTable .= "<td class='" . $oddeven . "'>$uname</td>\n";
          $logTable .= "<td class='" . $oddeven . "'>" . $ip . "<br />" . $agent_desc . "</td>\n";
          $logTable .= "<td class='" . $oddeven . "'>" . $type . "</td>\n";
          $logTable .= "<td class='" . $oddeven . "' width='100%'>" . $description . "</td>\n";
        $logTable .= "</tr>\n";
      }

      // footer of log listing
    $logTable .= "</table>\n";
  $logTable .= "</div>\n";

  $logTable .= "<div class='pageNavWrapper'>\n";
    $logTable .= $nav_html . "\n";
  $logTable .= "</div>\n";  

  $logTable .= "<div class='actionButtonTray'>\n";
  $logTable .= "<input type='button' value='" . _AM_LABEL_REMOVE . "' onclick='if(confirm(\"" . _AM_JS_REMOVECONFIRM . "\")){document.MainForm.action.value=\"delete\"; submit();}' />\n";  
  $logTable .= "<input type='button' value='" . _AM_LABEL_COMPACTLOG . "' onclick='if(confirm(\"" . _AM_JS_COMPACTLOGCONFIRM . "\")){document.MainForm.action.value=\"compactlog\"; submit();}' />\n";
  $logTable .= "<input type='button' value='" . _AM_LABEL_REMOVEALL . "' onclick='if(confirm(\"" . _AM_JS_REMOVEALLCONFIRM . "\")){document.MainForm.action.value=\"deleteall\"; submit();}' />\n";
  $logTable .= "</div>\n";
$logTable .= "</form>\n";

// Render $logTable
echo $logTable;

icms_cp_footer();


function protector_ip_cmp( $a , $b )
{
	$as = explode( '.' , $a ) ;
	$aval = @$as[0] * 167777216 + @$as[1] * 65536 + @$as[2] * 256 + @$as[3] ;
	$bs = explode( '.' , $b ) ;
	$bval = @$bs[0] * 167777216 + @$bs[1] * 65536 + @$bs[2] * 256 + @$bs[3] ;

	return $aval > $bval ? 1 : -1 ;
}
