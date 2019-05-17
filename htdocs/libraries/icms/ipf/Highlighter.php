<?php
/**
 * This file contains the keyhighlighter class that highlights the chosen keyword in the current output buffer.
 *
 * @category	ICMS
 * @package		Ipf
 * @subpackage	Keyhighlighter
 */

/**
 * keyhighlighter class
 *
 * This class highlight the chosen keywords in the current output buffer
 *
 * @category	ICMS
 * @package		Ipf
 * @subpackage	Keyhighlighter
 * @author		Setec Astronomy
 * @version 	1.0
 * @abstract	Highlight specific keywords.
 * @copyright	2004
 * @link 		http://setecastronomy.stufftoread.com
 */
class icms_ipf_Highlighter {

	/**
	 * @access private
	 */
	private $preg_keywords = '';
	/**
	 * @access private
	 */
	private $keywords = '';
	/**
	 * @access private
	 */
	private $singlewords = false;
	/**
	 * @access private
	 */
	private $replace_callback = null;

	public $content;

	/**
	 * Main constructor
	 *
	 * This is the main constructor of keyhighlighter class. <br />
	 * It's the only public method of the class.
	 * @param string $keywords the keywords you want to highlight
	 * @param boolean $singlewords specify if it has to highlight also the single words.
	 * @param callback $replace_callback a custom callback for keyword highlight.
	 * <code>
	 * <?php
	 * require 'keyhighlighter.class.php' ;
	 *
	 * function my_highlighter ($matches) {
	 * 	return '<span style="font-weight: bolder; color: #FF0000;">' . $matches[0] . '</span>';
	 * }
	 *
	 * new keyhighlighter ('W3C', false, 'my_highlighter');
	 * readfile ('http://www.w3c.org/');
	 *
	 * </code>
	 */
	public function __construct($keywords, $singlewords = false, $replace_callback = null) {
		$this->keywords = $keywords;
		$this->singlewords = $singlewords;
		$this->replace_callback = $replace_callback;
	}

	/**
	 * @access private
	 */
	private function replace($replace_matches) {

		$patterns = array();
		if ($this->singlewords) {
			$keywords = explode(' ', $this->preg_keywords);
			foreach ($keywords as $keyword) {
				$patterns[] = '/(?' . '>' . $keyword . '+)/si';
			}
		} else {
			$patterns[] = '/(?' . '>' . $this->preg_keywords . '+)/si';
		}

		$result = $replace_matches[0];

		foreach ($patterns as $pattern) {
			if (!is_null($this->replace_callback)) {
				$result = preg_replace_callback($pattern, $this->replace_callback, $result);
			} else {
				$result = preg_replace($pattern, '<span class="highlightedkey">\\0</span>', $result);
			}
		}

		return $result;
	}

	/**
	 * @access private
	 */
	private function highlight($buffer) {
		$buffer = '>' . $buffer . '<';
		$this->preg_keywords = preg_replace('/[^\w ]/si', '', $this->keywords);
		$buffer = preg_replace_callback ("/(\>(((?" . ">[^><]+)|(?R))*)\<)/is", array(&$this, 'replace'), $buffer);
		$buffer = substr($buffer, 1, -1);
		return $buffer;
	}
}

