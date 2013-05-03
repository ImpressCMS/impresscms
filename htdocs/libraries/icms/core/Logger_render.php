<?php
/**
 * The Renderer functions of the Error logger
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @license	LICENSE.txt
 * @category	ICMS
 * @package	Core
 * @subpackage	Logger
 * @author		modified by UnderDog <underdog@impresscms.org>
 * @since		XOOPS - original location /class/logger_render.php
 * @version	$Id: Logger_render.php 12112 2012-11-09 02:15:50Z skenow $
 */

defined('ICMS_ROOT_PATH') or die();

$ret = '';

if ($mode == 'popup') {
	$dump = $this->dump('');
	$data = array(
		'dump' => $dump,
		'close' => _CLOSE
	);
	$ret .= "<script type=\"text/javascript\">\n";
		$ret .= "require(['app/widgets/debugger/popup'], function(debug) {\n";
			$ret .= "debug.initialize(".json_encode($data).");\n";
		$ret .= "});\n";
	$ret .= "</script>\n";
}

if (empty( $mode )) {
	$ret .= "\n<div id='xo-logger-output' class='tabbable' data-module='app/widgets/debugger/main'>\n";
		$ret .= "<ul class=\"nav nav-tabs\">\n";
			$ret .= "<li class='active'><a href='#debug-none' data-toggle='tab'>" . _NONE . "</a></li>\n";
			
			$count = count( $this->errors );
			$ret .= "<li><a href='#debug-errors' data-toggle='tab'>" . _ERRORS . " (" . icms_conv_nr2local($count) . ")</a></li>\n";
			
			$count = count( $this->queries );
			$ret .= "<li><a href='#debug-queries' data-toggle='tab'>" . _QUERIES . " (" . icms_conv_nr2local($count) . ")</a></li>\n";

			$count = count( $this->blocks );
			$ret .= "<li><a href='#debug-blocks' data-toggle='tab'>" . _BLOCKS . " (" . icms_conv_nr2local($count) . ")</a></li>\n";
			
			$count = count( $this->extra );
			$ret .= "<li><a href='#debug-extra' data-toggle='tab'>" . _EXTRA . " (" . icms_conv_nr2local($count) . ")</a></li>\n";
			
			$count = count( $this->logstart );
			$ret .= "<li><a href='#debug-timers' data-toggle='tab'>" . _TIMERS . " (" . icms_conv_nr2local($count) . ")</a></li>\n";
			
			$count = count($this->deprecated);
			$ret .= "<li><a href='#debug-deprecated' data-toggle='tab'>" . _CORE_DEPRECATED . " (" . icms_conv_nr2local($count) . ")</a></li>\n";
		$ret .= "</ul>\n";

		$ret .= "<div class='tab-content'>\n";
			$ret .= "<div class='tab-pane active' id='debug-none'></div>\n";
}

if (empty($mode) || $mode == 'errors') {
	$types = array(
		E_USER_NOTICE => _NOTICE,
		E_USER_WARNING => _WARNING,
		E_USER_ERROR => _ERROR,
		E_NOTICE => _NOTICE,
		E_WARNING => _WARNING,
		E_STRICT => _STRICT,
	);
	$class = 'even';
	$ret .= '<div class="tab-pane" id="debug-errors"><h4>' . _ERRORS . '</h4>';
	foreach ( $this->errors as $error) {
		$ret .= "\n<div class='$class'>";
		$ret .= isset( $types[ $error['errno'] ] ) ? $types[ $error['errno'] ] : 'Unknown';
		$ret .= sprintf( ": %s in file %s line %s<br />\n", $error['errstr'], $error['errfile'], $error['errline'] );
		$ret .= "</div>";
		$class = ($class == 'odd') ? 'even' : 'odd';
	}
	$ret .= "\n</table>\n</div>\n";
}

if (empty($mode) || $mode == 'queries') {	
	$class = 'even';
	$ret .= '<div class="tab-pane" id="debug-queries"><h4>' . _QUERIES . '</h4>';
	$sqlmessages ='';
	foreach ($this->queries as $q) {
		if (isset($q['error'])) {
			$sqlmessages .= '<div class="' . $class . '"><span style="color:#ff0000;">' . htmlentities($q['sql']) . '<br /><strong>' . _ERR_NR . '</strong> ' . $q['errno'] . '<br /><strong>' . _ERR_MSG . '</strong> ' . $q['error'] . '</span></div>';
		} else {
			$sqlmessages .= '<div class="' . $class . '">' . htmlentities($q['sql']) . '</div>';
		}
		$class = ($class == 'odd') ? 'even' : 'odd';
	}
	$ret .= str_replace(XOOPS_DB_PREFIX . '_', '', $sqlmessages);
	$ret .= '<div class="foot"><h5>' . _TOTAL . ' <span style="color:#ff0000;">' . icms_conv_nr2local(count($this->queries)) . '</span> ' . _QUERIES . '</h5></div></div>';
}

if (empty($mode) || $mode == 'blocks') {
	$class = 'even';
	$ret .= '<div class="tab-pane" id="debug-blocks"><h4>' . _BLOCKS . '</h4>';
	foreach ($this->blocks as $b) {
		if ($b['cached']) {
			$ret .= '<div class="' . $class . '"><strong>' . htmlspecialchars($b['name']) . ':</strong> ' . _CACHED . ' : ' . icms_conv_nr2local(sprintf(_REGENERATES, (int) ($b['cachetime']))) . '</div>';
		} else {
			$ret .= '<div class="' . $class . '"><strong>' . htmlspecialchars($b['name']) . ':</strong> ' . _NOCACHE . '</div>';
		}
		$class = ($class == 'odd') ? 'even' : 'odd';
	}
	$ret .= '<div class="foot"><h5>' . _TOTAL . ' <span style="color:#ff0000;">' . icms_conv_nr2local(count($this->blocks)) . '</span> ' . _BLOCK . '</h5></div></div>';
}

if (empty($mode) || $mode == 'extra') {
	$class = 'even';
	$ret .= '<div class="tab-pane" id="debug-extra"><h4>' . _EXTRA . '</h4>';
	foreach ($this->extra as $ex) {
		$ret .= '<div class="' . $class . '"><strong>' . htmlspecialchars($ex['name']) . ':</strong> ' . htmlspecialchars($ex['msg']) . '</div>';
		$class = ($class == 'odd') ? 'even' : 'odd';
	}
	$ret .= '</div>';
}

if (empty($mode) || $mode == 'timers') {
	$class = 'even';
	$ret .= '<div class="tab-pane" id="debug-timers"><h4>' . _TIMERS . '</h4>';
	foreach ( $this->logstart as $k => $v) {
		$ret .= '<div class="' . $class.'"><strong>' . htmlspecialchars($k) . '</strong> ' . sprintf(_TOOKXLONG, '<span style="color:#ff0000;">' . icms_conv_nr2local(sprintf( "%.03f", $this->dumpTime($k) )) . '</span>') . '</div>';
		$class = ($class == 'odd') ? 'even' : 'odd';
	}
	$ret .= '</div>';
}

/** 
 * @author		ImpressCMS
 * @since 		1.3
 */
if (empty($mode) || $mode == 'deprecated') {
	$class = 'even';
	$ret .= '<div class="tab-pane" id="debug-deprecated"><h4>' . _CORE_DEPRECATED . '</h4>';
	foreach ( $this->deprecated as $dep) {
		$ret .= '<div class="' . $class.'">' . $dep . '</div>';
		$class = ($class == 'odd') ? 'even' : 'odd';
	}
	$ret .= '</div>';
}

if (empty( $mode )) {
	$ret .= "</div>\n</div>\n";
}