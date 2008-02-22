<?php
/**
 * XoopsLogger component main class file
 *
 * See the enclosed file LICENSE for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Kazumi Ono  <onokazu@xoops.org>
 * @author		Skalpa Keo <skalpa@xoops.org>
 * @since		2.0
 * @package		xos_kernel
 * @subpackage	XoopsLogger
 * @version		$Id: logger.php 694 2006-09-04 11:33:22Z skalpa $
 */

/**
 * Collects information for a page request
 *
 * Records information about database queries, blocks, and execution time
 * and can display it as HTML. It also catches php runtime errors.
 * @package xos_kernel
 */
class XoopsLogger {
    /**#@+
     * @var array
     */
    var $queries = array();
    var $blocks = array();
    var $extra = array();
    var $logstart = array();
    var $logend = array();
    var $errors = array();
    /**#@-*/

    var $usePopup = false;
    var $activated = true;
    
	/**@access protected*/
    var $renderingEnabled = false;
    
    function XoopsLogger() {
    }
    /**
     * Get a reference to the only instance of this class
     * @return  object XoopsLogger  reference to the only instance
     */
    function &instance() {
    	static $instance;

    	if ( !isset( $instance ) ) {
    		$instance = new XoopsLogger();
			// Always catch errors, for security reasons
	    	set_error_handler( 'XoopsErrorHandler_HandleError' );
    	}
        return $instance;
    }
	/**
	 * Enable logger output rendering
	 * When output rendering is enabled, the logger will insert its output within the page content.
	 * If the string <!--{xo-logger-output}--> is found in the page content, the logger output will
	 * replace it, otherwise it will be inserted after all the page output.
	 */
    function enableRendering() {
		if ( !$this->renderingEnabled ) {
    		ob_start( array( &$this, 'render' ) );
    		$this->renderingEnabled = true;
		}
    }
    /**
     * Disable logger output rendering.
     */
    function disableRendering() {
		if ( $this->renderingEnabled ) {
    		$this->renderingEnabled = false;
		}
    }

	/**
	 * Returns the current microtime in seconds.
	 * @return float
	 */
	function microtime() {
		$now = explode( ' ', microtime() );
		return (float)$now[0] + (float)$now[1];
	}
    /**
     * Start a timer
     * @param   string  $name   name of the timer
     */
    function startTime($name = 'XOOPS') {
        $this->logstart[$name] = $this->microtime();
    }
    /**
     * Stop a timer
     * @param   string  $name   name of the timer
     */
    function stopTime($name = 'XOOPS') {
        $this->logend[$name] = $this->microtime();
    }
    /**
     * Log a database query
     * @param   string  $sql    SQL string
     * @param   string  $error  error message (if any)
     * @param   int     $errno  error number (if any)
     */
    function addQuery($sql, $error=null, $errno=null) {
        if ( $this->activated )		$this->queries[] = array('sql' => $sql, 'error' => $error, 'errno' => $errno);
    }
    /**
     * Log display of a block
     * @param   string  $name       name of the block
     * @param   bool    $cached     was the block cached?
     * @param   int     $cachetime  cachetime of the block
     */
    function addBlock($name, $cached = false, $cachetime = 0) {
        if ( $this->activated )		$this->blocks[] = array('name' => $name, 'cached' => $cached, 'cachetime' => $cachetime);
    }
    /**
     * Log extra information
     * @param   string  $name       name for the entry
     * @param   int     $msg  text message for the entry
     */
    function addExtra($name, $msg) {
        if ( $this->activated )		$this->extra[] = array('name' => $name, 'msg' => $msg);
    }

	/**
	 * Error handling callback (called by the zend engine)
	 */  
    function handleError( $errno, $errstr, $errfile, $errline ) {
    	$errstr = $this->sanitizePath( $errstr );
    	$errfile = $this->sanitizePath( $errfile );
    	if ( $this->activated && ( $errno & error_reporting() ) ) {
	    	// NOTE: we only store relative pathnames
			$this->errors[] = compact( 'errno', 'errstr', 'errfile', 'errline' );
    	}
		if ( $errno == E_USER_ERROR ) {
			$trace = true;
			if ( substr( $errstr, 0, '8' ) == 'notrace:' ) {
				$trace = false;
				$errstr = substr( $errstr, 8 );
			}
			echo 'This page cannot be displayed due to an internal error.<br/><br/>';
			echo "You can provide the following information to the administrators of ";
			echo "this site to help them solve the problem:<br /><br />";
			echo "Error: $errstr<br />";
			if ( $trace && function_exists( 'debug_backtrace' ) ) {
				echo "<div style='color:#ffffff;background-color:#ffffff'>Backtrace:<br />";
				$trace = debug_backtrace();
				array_shift( $trace );
				foreach ( $trace as $step ) {
					if ( isset( $step['file'] ) ) {
						echo $this->sanitizePath( $step['file'] );
						echo ' (' . $step['line'] . ")\n<br />";
					}					
				}
				echo '</div>';
			}
			exit();
		}
	}
	/**
	 * @access protected
	 */
	function sanitizePath( $path ) {
		$path = str_replace( 
			array( '\\', XOOPS_ROOT_PATH, str_replace( '\\', '/', realpath( XOOPS_ROOT_PATH ) ) ),
			array( '/', '', '' ),
			$path
		);		
		return $path;
	}
	
	/**
	 * Output buffering callback inserting logger dump in page output
	 */
	function render( $output ) {
		global $xoopsUser,$xoopsModule;

	    $groups   = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$moduleid = (isset($xoopsModule) && is_object($xoopsModule)) ? $xoopsModule->mid() : 1;

		$gperm_handler =& xoops_gethandler('groupperm');

		if ( !$this->activated || !$gperm_handler->checkRight('enable_debug', $moduleid, $groups) ) {
			return $output;
		}
		$this->renderingEnabled = $this->activated = false;
		
		$log = $this->dump( $this->usePopup ? 'popup' : '' );

		$pattern = '<!--{xo-logger-output}-->';
		$pos = strpos( $output, $pattern );
		if ( $pos !== false ) {
			return substr( $output, 0, $pos ) . $log . substr( $output, $pos + strlen( $pattern ) );
		} else {
			return $output . $log;
		}
	}
    /**#@+
     * @protected
     */
	function dump( $mode = '' ) {
		include XOOPS_ROOT_PATH . '/class/logger_render.php';
		return $ret;
	}
    /**
     * get the current execution time of a timer
     *
     * @param   string  $name   name of the counter
     * @return  float   current execution time of the counter
     */
    function dumpTime( $name = 'XOOPS' ) {
        if ( !isset($this->logstart[$name]) ) {
            return 0;
        }
        $stop = isset( $this->logend[$name] ) ? $this->logend[$name] : $this->microtime();
		return $stop - $this->logstart[$name];
    }
    /**#@-*/
    /**#@+
     * @deprecated
     */
    function dumpAll() {			return $this->dump( '' );			}
    function dumpBlocks() {	    	return $this->dump( 'blocks' );		}
    function dumpExtra() {	    	return $this->dump( 'extra' );		}
    function dumpQueries() {		return $this->dump( 'queries' );	}
    /**#@-*/
}

/*
* PHP Error handler
*
* NB: You're not supposed to call this function directly, if you dont understand why, then
* you'd better spend some time reading your PHP manual before you hurt somebody
*
* @internal: Using a function and not calling the handler method directly coz old PHP versions
* set_error_handler() have problems with the array( obj,methodname ) syntax
*/
function XoopsErrorHandler_HandleError( $errNo, $errStr, $errFile, $errLine, $errContext = null ) {
	$logger =& XoopsLogger::instance();
	$logger->handleError( $errNo, $errStr, $errFile, $errLine, $errContext );
}

?>
