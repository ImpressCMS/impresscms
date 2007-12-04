<?php

defined( 'XOOPS_ROOT_PATH' ) or die();

$ret = '';

if ( $mode == 'popup' ) {
	$dump = $this->dump( '' );
	$content = '
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset='._CHARSET.'" />
	<meta http-equiv="content-language" content="'._LANGCODE.'" />
	<title>'.$xoopsConfig['sitename'].'</title>
	<link rel="stylesheet" type="text/css" media="all" href="'.getcss($xoopsConfig['theme_set']).'" />
</head>
<body>' . $dump . '
	<div style="text-align:center;">
		<input class="formButton" value="'._CLOSE.'" type="button" onclick="javascript:window.close();" />
	</div>
</body>
</html>';
	$ret .= '
<script type="text/javascript">
	debug_window = openWithSelfMain("about:blank", "popup", 680, 450, true);
	debug_window.document.clear();
';
	$lines = preg_split("/(\r\n|\r|\n)( *)/", $content);
	foreach ($lines as $line) {
		$ret .= "\n" . 'debug_window.document.writeln("'.str_replace( array( '"', '</' ), array( '\"', '<\/' ), $line).'");';
	}
	$ret .= '
	debug_window.focus();
	debug_window.document.close();
</script>';
}

if ( empty( $mode ) ) {
	$views = array( 'errors', 'queries', 'blocks', 'extra' );
	$ret .= "\n<div id=\"xo-logger-output\">\n<div id='xo-logger-tabs'>\n";
	$ret .= "<a href='javascript:xoSetLoggerView(\"\")'>All</a>\n";
	foreach ( $views as $view ) {
		$count = count( $this->$view );
		$ret .= "<a href='javascript:xoSetLoggerView(\"$view\")'>$view ($count)</a>\n";
	}
	$count = count( $this->logstart );
	$ret .= "<a href='javascript:xoSetLoggerView(\"timers\")'>timers ($count)</a>\n";
	$ret .= "</div>\n";
}

if ( empty($mode) || $mode == 'errors' ) {
	$types = array(
		E_USER_NOTICE => 'Notice',
		E_USER_WARNING => 'Warning',
		E_USER_ERROR => 'Error',
		E_NOTICE => 'Notice',
		E_WARNING => 'Warning',
		E_STRICT => 'Strict',
	);
	$class = 'even';
	$ret .= '<table id="xo-logger-errors" class="outer"><tr><th>Errors</th></tr>';
	foreach ( $this->errors as $error ) {
		$ret .= "\n<tr><td class='$class'>";
		$ret .= isset( $types[ $error['errno'] ] ) ? $types[ $error['errno'] ] : 'Unknown';
		$ret .= sprintf( ": %s in file %s line %s<br />\n", $error['errstr'], $error['errfile'], $error['errline'] );
		$ret .= "</td></tr>";
        $class = ($class == 'odd') ? 'even' : 'odd';
	}
	$ret .= "\n</table>\n";
}

if ( empty($mode) || $mode == 'queries' ) {
	$class = 'even';
	$ret .= '<table id="xo-logger-queries" class="outer"><tr><th>Queries</th></tr>';
    foreach ($this->queries as $q) {
        if (isset($q['error'])) {
            $ret .= '<tr class="'.$class.'"><td><span style="color:#ff0000;">'.htmlentities($q['sql']).'<br /><b>Error number:</b> '.$q['errno'].'<br /><b>Error message:</b> '.$q['error'].'</span></td></tr>';
        } else {
            $ret .= '<tr class="'.$class.'"><td>'.htmlentities($q['sql']).'</td></tr>';
        }
        $class = ($class == 'odd') ? 'even' : 'odd';
    }
    $ret .= '<tr class="foot"><td>Total: <span style="color:#ff0000;">'.count($this->queries).'</span> queries</td></tr></table>';
}
if ( empty($mode) || $mode == 'blocks' ) {
    $class = 'even';
    $ret .= '<table id="xo-logger-blocks" class="outer"><tr><th colspan="2">Blocks</th></tr>';
    foreach ($this->blocks as $b) {
        if ($b['cached']) {
            $ret .= '<tr><td class="'.$class.'"><b>'.htmlspecialchars($b['name']).':</b> Cached (regenerates every '.intval($b['cachetime']).' seconds)</td></tr>';
        } else {
            $ret .= '<tr><td class="'.$class.'"><b>'.htmlspecialchars($b['name']).':</b> No Cache</td></tr>';
        }
        $class = ($class == 'odd') ? 'even' : 'odd';
    }
    $ret .= '<tr class="foot"><td>Total: <span style="color:#ff0000;">'.count($this->blocks).'</span> blocks</td></tr></table>';
}
if ( empty($mode) || $mode == 'extra' ) {
	$this->addExtra( 'Included files', count ( get_included_files() ) . ' files' );
	$memory = 0;
	if ( function_exists( 'memory_get_usage' ) ) {
		$memory = memory_get_usage() . ' bytes';
	} else {
		$os = isset( $_ENV['OS'] ) ? $_ENV['OS'] : $_SERVER['OS'];
		if ( strpos( strtolower( $os ), 'windows') !== false ) {
			$out = array();
			exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $out );
			$memory = substr( $out[5], strpos( $out[5], ':') + 1) . ' [Estimated]';
		}
	}
	if ( $memory ) {
		$this->addExtra( 'Memory usage', $memory );
	}
	
    $class = 'even';
    $ret .= '<table id="xo-logger-extra" class="outer"><tr><th colspan="2">Extra</th></tr>';
    foreach ($this->extra as $ex) {
        $ret .= '<tr><td class="'.$class.'"><b>'.htmlspecialchars($ex['name']).':</b> '.htmlspecialchars($ex['msg']).'</td></tr>';
        $class = ($class == 'odd') ? 'even' : 'odd';
    }
    $ret .= '</table>';
}
if ( empty($mode) || $mode == 'timers' ) {
    $class = 'even';
    $ret .= '<table id="xo-logger-timers" class="outer"><tr><th colspan="2">Timers</th></tr>';
    foreach ( $this->logstart as $k => $v ) {
        $ret .= '<tr><td class="'.$class.'"><b>'.htmlspecialchars($k).'</b> took <span style="color:#ff0000;">' . sprintf( "%.03f", $this->dumpTime($k) ) . '</span> seconds to load.</td></tr>';
        $class = ($class == 'odd') ? 'even' : 'odd';
    }
    $ret .= '</table>';
}

if ( empty( $mode ) ) {
	$ret .= <<<EOT
</div>
<script type="text/javascript">
	function xoLogCreateCookie(name,value,days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
	}
	function xoLogReadCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	}
	function xoLogEraseCookie(name) {
		createCookie(name,"",-1);
	}
	function xoSetLoggerView( name ) {
		var log = document.getElementById( "xo-logger-output" );
		if ( !log ) return;
		var i, elt;
		for ( i=0; i!=log.childNodes.length; i++ ) {
			elt = log.childNodes[i];
			if ( elt.tagName && elt.tagName.toLowerCase() != 'script' && elt.id != "xo-logger-tabs" ) {
				elt.style.display = ( !name || elt.id == "xo-logger-" + name ) ? "block" : "none";
			}
		}
		xoLogCreateCookie( 'XOLOGGERVIEW', name, 1 );
	}
	xoSetLoggerView( xoLogReadCookie( 'XOLOGGERVIEW' ) );
</script>
	
EOT;
}


?>