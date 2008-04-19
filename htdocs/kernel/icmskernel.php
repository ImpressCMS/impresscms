<?php
/**
 * Extremely reduced kernel class
 * Few notes:
 * - modules should use this class methods to generate physical paths/URIs (the ones which do not conform
 * will perform badly when true URL rewriting is implemented)
 */
class IcmsKernel {
	var $paths = array(
		'www' => array(), 'modules' => array(), 'themes' => array(),
	);
	function IcmsKernel() {
		$this->paths['www'] = array( ICMS_ROOT_PATH, ICMS_URL );
		$this->paths['modules'] = array( ICMS_ROOT_PATH . '/modules', ICMS_URL . '/modules' );
		$this->paths['themes'] = array( ICMS_ROOT_PATH . '/themes', ICMS_URL . '/themes' );
	}
	/**
	 * Convert a XOOPS path to a physical one
	 */
	function path( $url, $virtual = false ) {
		$path = '';
		@list( $root, $path ) = explode( '/', $url, 2 );
		if ( !isset( $this->paths[$root] ) ) {
			list( $root, $path ) = array( 'www', $url );
		}
		if ( !$virtual ) {		// Returns a physical path
			return $this->paths[$root][0] . '/' . $path;
		}
		return !isset( $this->paths[$root][1] ) ? '' : ( $this->paths[$root][1] . '/' . $path );
	}
	/**
	* Convert a XOOPS path to an URL
	*/
	function url( $url ) {
		return ( false !== strpos( $url, '://' ) ? $url : $this->path( $url, true ) );
	}
	/**
	* Build an URL with the specified request params
	*/
	function buildUrl( $url, $params = array() ) {
		if ( $url == '.' ) {
			$url = $_SERVER['REQUEST_URI'];
		}
		$split = explode( '?', $url );
		if ( count($split) > 1 ) {
			list( $url, $query ) = $split;
			parse_str( $query, $query );
			$params = array_merge( $query, $params );
		}
		if ( !empty( $params ) ) {
			foreach ( $params as $k => $v ) {
				$params[$k] = $k . '=' . rawurlencode($v);
			}
			$url .= '?' . implode( '&', $params );
		}
		return $url;
	}

}
?>