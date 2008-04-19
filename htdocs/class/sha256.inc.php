<?php 


/******************************************************************************* 
* 
* SHA256 static class for PHP4 
* implemented by feyd _at_ devnetwork .dot. net 
* specification from http://csrc.nist.gov/cryptval/shs/sha256-384-512.pdf 
* 
* (C) Copyright 2005 Developer's Network. All rights reserved. 
* 
* This library is free software; you can redistribute it and/or modify it 
* under the terms of the GNU Lesser General Public License as published by the 
* Free Software Foundation; either version 2.1 of the License, or (at your 
* option) any later version. 
* 
* This library is distributed in the hope that it will be useful, but WITHOUT 
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or 
* FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License 
* for more details. 
* 
* You should have received a copy of the GNU Lesser General Public License 
* along with this library; if not, write to the 
* Free Software Foundation, Inc. 
* 59 Temple Place, Suite 330, 
* Boston, MA 02111-1307 USA 
* 
* Thanks to CertainKey Inc. for providing some example outputs in Javascript 
* 
*----- Version 1.1.0 ---------------------------------------------------------- 
* 
* These classes generate PHP level error when an error, warning, or notice of 
* some kind happens during execution. This allows you to "hide" any of these 
* like you would any other function's error output using error_reporting(). 
* 
* --------------------------------- 
* 
* Function: 
* SHA256::hash() 
* 
* Syntax: 
* string SHA256::hash( string message[, string format ]) 
* 
* Description: 
* SHA256::hash() is intended to hash a single string, of nominal length 
* (memory permitting.) The entire string is loaded into memory, broken 
* into chunks and appended with the hashing fill algorithm. 
* 
* SHA256::hash() is a static function that must be called with `message` 
* and optionally `format`. Possible values for `format` are: 
* 'hex' default; hexidecimal string output (lower case) 
* 'HEX' hexidecimal string output (upper case) 
* 'bin' binary string output 
* 'bit' bit level output (256 character '1' & '0' string) 
* 
* Failures return FALSE. 
* 
* Usage: 
* $hash = SHA256::hash('string to hash'); 
* 
* --------------------------------- 
* 
* Function: 
* SHA256::hashFile() 
* 
* Syntax: 
* string SHA256::hashFile( string filename[, string format ]) 
* 
* Description: 
* SHA256::hashFile() is intended to hash a local file _only_. STDIN is 
* not supported at this time, nor is any other protocol other than 'file.' 
* 
* SHA256::hashFile() is a static function that must be called with a 
* `filename` and optionally `format`. Possible values for `format` are: 
* 'hex' default; hexidecimal string output (lower case) 
* 'HEX' hexidecimal string output (upper case) 
* 'bin' binary string output 
* 'bit' bit level output (256 character '1' & '0' string) 
* 
* Failures return FALSE. 
* 
* Usage: 
* $hash = SHA256::hashFile('/path/to/file.ext'); 
* 
* Note: 
* This function will not accept any other protocols other than 'file'. 
* This is due to the use of fstat(), among other reasons. 
* 
* --------------------------------- 
* 
* Function: 
* SHA256::hashURL() 
* 
* Syntax: 
* string SHA256::hashURL( string url[, string format ]) 
* 
* Description: 
* SHA256::hashURL() is intended to hash a url. 'http://' is optional, if 
* you want to use it. The function checks if allow_url_fopen is enabled. 
* If it is, the url is passed through fopen(), so all protocols supported 
* your installation of PHP are supported automatically. If allow_url_fopen 
* is off, fsockopen is attempted to be used. This version only supports 
* http requests through fsockopen. So beware. 
* 
* SHA256::hashURL() is a static function that must be called with a `url` 
* and optionally `format`. Possible values for `format` are: 
* 'hex' default; hexidecimal string output (lower case) 
* 'HEX' hexidecimal string output (upper case) 
* 'bin' binary string output 
* 'bit' bit level output (256 character '1' & '0' string) 
* 
* Failures return FALSE. 
* 
* If a protocol is not specified, 'http' is assumed. 
* 
* Usage: 
* $hash = SHA256::hashURL('http://www.site.com'); 
* 
* Note: 
* The protocol used _must_ be registered with your PHP with 
* allow_url_fopen enabled, or must be 'http'. 
* 
*----- History ---------------------------------------------------------------- 
* 
* 1.1.0 Split out generic 'hash' class to a seperate include, for future 
* hashes to use. 
* Changed handling around to allow for very large message chunks 
* for files and URL handling (does not affect string hashes). 
* Added file hashing support. see notes above for details. 
* Added url hashing support. see notes above for details. 
* Split testing to a seperate file. 
* Initial check-in to source control 
* Removed octal output as a future option, due to incomplete usage 
* Added bit and upper case hex output 
* 
* 1.0.1 Potential integer truncation bug fix for various operating 
* systems and processor combinations. 
* 
* 1.0.0 First version made available. 
* 
*----- Source Control Information --------------------------------------------- 
* 
* $Workfile: hash_sha256.php $ 
* $Author: Feyd $ 
* $JustDate: 05.04.06 $ 
* $Revision: 4 $ 
* 
* $Header: /inc/hash_sha256.php 4 05.04.06 2:46p Feyd $ 
* 
******************************************************************************/ 
if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}


// hash base class definition file 
require_once XOOPS_ROOT_PATH.'/class/sha256_base.php'; 


/*============================================================================== 
* SHA256 Main Class 
*============================================================================*/ 


class SHA256 extends hash 
{ 
// hash a known string of data 
function hash($str, $mode = 'hex') 
{ 
return SHA256::_hash( '', $str, $mode ); 
} 

// hash a file 
function hashFile($filename, $mode = 'hex') 
{ 
return SHA256::_hash( 'File', $filename, $mode ); 
} 

// hash a URL 
function hashURL($url, $mode = 'hex') 
{ 
return SHA256::_hash( 'URL', $url, $mode ); 
} 


// ------------------------------- 
// BEGIN INTERNAL FUNCTIONS 
// ------------------------------- 

// the actual hash interface function, which ~dynamically switches types. 
function _hash( $type, $str, $mode ) 
{ 
$modes = array( 'hex', 'bin', 'bit' ); 
$ret = false; 

if(!in_array(strtolower($mode), $modes)) 
{ 
trigger_error('mode specified is unrecognized: ' . $mode, E_USER_WARNING); 
} 
else 
{ 
$data =& new SHA256Data( $type, $str ); 

SHA256::compute($data); 

$func = array('SHA256', 'hash' . $mode); 
if(is_callable($func)) 
{ 
$func = 'hash' . $mode; 
$ret = SHA256::$func($data); 
//$ret = call_user_func($func, $data); 

if( $mode === 'HEX' ) 
{ 
$ret = strtoupper( $ret ); 
} 
} 
else 
{ 
trigger_error('SHA256::hash' . $mode . '() NOT IMPLEMENTED.', E_USER_WARNING); 
} 
} 

return $ret; 
} 


// 32-bit summation 
function sum() 
{ 
$T = 0; 
for($x = 0, $y = func_num_args(); $x < $y; $x++) 
{ 
// argument 
$a = func_get_arg($x); 

// carry storage 
$c = 0; 

for($i = 0; $i < 32; $i++) 
{ 
// sum of the bits at $i 
$j = (($T >> $i) & 1) + (($a >> $i) & 1) + $c; 
// carry of the bits at $i 
$c = ($j >> 1) & 1; 
// strip the carry 
$j &= 1; 
// clear the bit 
$T &= ~(1 << $i); 
// set the bit 
$T |= $j << $i; 
} 
} 

return $T; 
} 


// compute the hash. This is the real hashing function. 
function compute(&$hashData) 
{ 
static $vars = 'abcdefgh'; 
static $K = null; 

if($K === null) 
{ 
$K = array ( 
1116352408, 1899447441, -1245643825, -373957723, 
961987163, 1508970993, -1841331548, -1424204075, 
-670586216, 310598401, 607225278, 1426881987, 
1925078388, -2132889090, -1680079193, -1046744716, 
-459576895, -272742522, 264347078, 604807628, 
770255983, 1249150122, 1555081692, 1996064986, 
-1740746414, -1473132947, -1341970488, -1084653625, 
-958395405, -710438585, 113926993, 338241895, 
666307205, 773529912, 1294757372, 1396182291, 
1695183700, 1986661051, -2117940946, -1838011259, 
-1564481375, -1474664885, -1035236496, -949202525, 
-778901479, -694614492, -200395387, 275423344, 
430227734, 506948616, 659060556, 883997877, 
958139571, 1322822218, 1537002063, 1747873779, 
1955562222, 2024104815, -2067236844, -1933114872, 
-1866530822, -1538233109, -1090935817, -965641998, 
); 
} 

$W = array(); 
while(($chunk = $hashData->message->nextChunk()) !== false) 
{ 
// initialize the registers 
for($j = 0; $j < 8; $j++) 
${$vars{$j}} = $hashData->hash[$j]; 

// the SHA-256 compression function 
for($j = 0; $j < 64; $j++) 
{ 
if($j < 16) 
{ 
$T1 = ord($chunk{$j*4 }) & 0xFF; $T1 <<= 8; 
$T1 |= ord($chunk{$j*4+1}) & 0xFF; $T1 <<= 8; 
$T1 |= ord($chunk{$j*4+2}) & 0xFF; $T1 <<= 8; 
$T1 |= ord($chunk{$j*4+3}) & 0xFF; 
$W[$j] = $T1; 
} 
else 
{ 
$W[$j] = SHA256::sum(((($W[$j-2] >> 17) & 0x00007FFF) | ($W[$j-2] << 15)) ^ ((($W[$j-2] >> 19) & 0x00001FFF) | ($W[$j-2] << 13)) ^ (($W[$j-2] >> 10) & 0x003FFFFF), $W[$j-7], ((($W[$j-15] >> 7) & 0x01FFFFFF) | ($W[$j-15] << 25)) ^ ((($W[$j-15] >> 18) & 0x00003FFF) | ($W[$j-15] << 14)) ^ (($W[$j-15] >> 3) & 0x1FFFFFFF), $W[$j-16]); 
} 

$T1 = SHA256::sum($h, ((($e >> 6) & 0x03FFFFFF) | ($e << 26)) ^ ((($e >> 11) & 0x001FFFFF) | ($e << 21)) ^ ((($e >> 25) & 0x0000007F) | ($e << 7)), ($e & $f) ^ (~$e & $g), $K[$j], $W[$j]); 
$T2 = SHA256::sum(((($a >> 2) & 0x3FFFFFFF) | ($a << 30)) ^ ((($a >> 13) & 0x0007FFFF) | ($a << 19)) ^ ((($a >> 22) & 0x000003FF) | ($a << 10)), ($a & $b) ^ ($a & $c) ^ ($b & $c)); 
$h = $g; 
$g = $f; 
$f = $e; 
$e = SHA256::sum($d, $T1); 
$d = $c; 
$c = $b; 
$b = $a; 
$a = SHA256::sum($T1, $T2); 
} 

// compute the next hash set 
for($j = 0; $j < 8; $j++) 
$hashData->hash[$j] = SHA256::sum(${$vars{$j}}, $hashData->hash[$j]); 
} 
} 


// set up the display of the hash in hex. 
function hashHex(&$hashData) 
{ 
$str = ''; 

reset($hashData->hash); 
do 
{ 
$str .= sprintf('%08x', current($hashData->hash)); 
} 
while(next($hashData->hash)); 

return $str; 
} 


// set up the output of the hash in binary 
function hashBin(&$hashData) 
{ 
$str = ''; 

reset($hashData->hash); 
do 
{ 
$str .= pack('N', current($hashData->hash)); 
} 
while(next($hashData->hash)); 

return $str; 
} 


// set up the output of the hash in bits 
function hashBit(&$hashData) 
{ 
$str = ''; 

reset($hashData->hash); 
do 
{ 
$t = current($hashData->hash); 
for($i = 31; $i >= 0; $i--) 
{ 
$str .= ($t & (1 << $i) ? '1' : '0'); 
} 
} 
while(next($hashData->hash)); 

return $str; 
} 
} 


/*============================================================================== 
* SHA256 Data Class 
*============================================================================*/ 


class SHA256Data extends hashData 
{ 
function SHA256Data( $type, $str ) 
{ 
$type = 'SHA256Message' . $type; 
$this->message =& new $type( $str ); 

// H(0) 
$this->hash = array 
( 
1779033703, -1150833019, 
1013904242, -1521486534, 
1359893119, -1694144372, 
528734635, 1541459225, 
); 
} 
} 


/*============================================================================== 
* SHA256 Message Classes 
*============================================================================*/ 


class SHA256Message extends hashMessage 
{ 
function SHA256Message( $str ) 
{ 
$str .= $this->calculateFooter( strlen( $str ) ); 

// break the binary string into 512-bit blocks 
preg_match_all( '#.{64}#s', $str, $this->chunk ); 
$this->chunk = $this->chunk[0]; 

$this->curChunk = -1; 
} 

// retrieve the next chunk of the message 
function nextChunk() 
{ 
if( is_array($this->chunk) && ($this->curChunk >= -1) && isset($this->chunk[$this->curChunk + 1]) ) 
{ 
$this->curChunk++; 
$ret =& $this->chunk[$this->curChunk]; 
} 
else 
{ 
$this->chunk = null; 
$this->curChunk = -1; 
$ret = false; 
} 

return $ret; 
} 

// retrieve the current chunk of the message 
function currentChunk() 
{ 
if( is_array($this->chunk) ) 
{ 
if( $this->curChunk == -1 ) 
{ 
$this->curChunk = 0; 
} 
if( ($this->curChunk >= 0) && isset($this->chunk[$this->curChunk]) ) 
{ 
$ret =& $this->chunk[$this->curChunk]; 
} 
} 
else 
{ 
$ret = false; 
} 

return $ret; 
} 


// internal static function calculateFooter() which, calculates the footer appended to all messages 
function calculateFooter( $numbytes ) 
{ 
$M =& $numbytes; 
$L1 = ($M >> 28) & 0x0000000F; // top order bits 
$L2 = $M << 3; // number of bits 
$l = pack('N*', $L1, $L2); 

// 64 = 64 bits needed for the size mark. 1 = the 1 bit added to the 
// end. 511 = 511 bits to get the number to be at least large enough 
// to require one block. 512 is the block size. 
$k = $L2 + 64 + 1 + 511; 
$k -= $k % 512 + $L2 + 64 + 1; 
$k >>= 3; // convert to byte count 

$footer = chr(128) . str_repeat(chr(0), $k) . $l; 

assert('($M + strlen($footer)) % 64 == 0'); 

return $footer; 
} 
} 


class SHA256MessageFile extends hashMessageFile 
{ 
function SHA256MessageFile( $filename ) 
{ 
$this->filename = $filename; 
$this->fp = false; 
$this->size = false; 
$this->append = ''; 
$this->chunk = ''; 
$this->more = true; 

$info = parse_url( $filename ); 
if( isset( $info['scheme'] ) && !in_array(strtolower( $info['scheme'] ), array('php','file')) ) 
{ 
trigger_error('SHA256MessageFile(' . var_export($filename,true) . ' does not handle the ' . var_export( $info['scheme'], true ) . ' protocol.', E_USER_ERROR); 
return; 
} 

$this->fp = (is_readable( $filename ) ? @fopen( $filename, 'rb' ) : false); 

if( $this->fp === false ) 
{ 
trigger_error('SHA256MessageFile(' . var_export($filename,true) . '): unable to open file for SHA256 hashing.', E_USER_ERROR); 
} 

$stat = @fstat( $this->fp ); 

if( $stat === false ) 
{ 
trigger_error('SHA256MessageFile(' . var_export($filename,true) . '): unable to pull file status information.', E_USER_ERROR); 
return; 
} 

$this->append = SHA256Message::calculateFooter($this->size = intval($stat['size'])); 
} 

// load the next chunk from the file. 
function nextChunk() 
{ 
$ret = false; 

if( $this->fp !== false ) 
{ 
$ret = @fread( $this->fp, 64 ); 
if( ($l = strlen($ret)) != 64 ) 
{ 
if(strlen($ret . $this->append) > 64) 
{ 
$ret = substr( $ret . $this->append, 0, 64 ); 
$this->append = substr( $this->append, 64 - $l ); 
} 
else 
{ 
$ret .= $this->append; 
$this->more = false; 

assert('strlen($ret) % 64 == 0'); 
} 
} 

if(!$this->more) 
{ 
@fclose( $this->fp ); 
$this->fp = false; 
$this->size = false; 
$this->append = ''; 
} 
} 

$this->chunk = (string)$ret; 

return $ret; 
} 

// return the current chunk that was previously loaded 
function currentChunk() 
{ 
if( $this->chunk === '' && $this->fp !== false ) 
{ 
return $this->nextChunk(); 
} 
else 
{ 
return ($this->chunk === '' ? false : $this->chunk); 
} 
} 
} 


class SHA256MessageURL extends hashMessageURL 
{ 
// timeout for a socket resource open request 
var $socket_timeout = 5; 
function SHA256MessageURL( $url ) 
{ 
$this->fp = false; 
$this->more = true; 
$this->first = true; 
$this->headers = false; 
$this->append = ''; 
$this->chunk = ''; 
$this->curChunk = 0; 
$this->size = 0; 

if( ini_get( 'allow_url_fopen' ) == false ) 
{ // allow_url_fopen is off, check if protocol is http or not 
$info = parse_url($url); 
if( !isset($info['scheme']) || (strcasecmp(trim($info['scheme']), 'http') == 0) ) 
{ // http mode, use fsockopen 

if( !isset($info['scheme']) ) 
{ 
$url = 'http://' . $url; 
$info = parse_url($url); 
} 

if( function_exists( 'fsockopen' ) == false ) 
{ 
trigger_error('SHA256MessageURL(): allow_url_fopen is off, fsockopen is disabled or not available.', E_USER_WARNING); 
return; 
} 
elseif(empty($info['host'])) 
{ // fsockopen is on, but there's no known host in the url 
trigger_error('SHA256MessageURL(' . var_export($url,true) .') does not appear to be a url.', E_USER_NOTICE); 
return; 
} 

// protocol has been determined to be 'http', use fsockopen 

$this->fp = @fsockopen( $info['host'], (isset($info['port']) ? $info['port'] : 80), $errno, $errstr, $this->socket_timeout ); 

if(!$this->fp) 
{ // fsockopen failed 
trigger_error('SHA256MessageURL(): fsockopen failure ' . $errno . ' - ' . $errstr, E_USER_WARNING); 
return; 
} 

// send the request for the page 
@fwrite($this->fp, 'GET ' . (empty($info['path']) ? '/' : $info['path']) . " HTTP/1.0\r\nHost: " . strtolower($info['host']) . "\r\n\r\n"); 
$this->headers = true; 
} 
else 
{ 
trigger_error('SHA256MessageURL(' . var_export($url,true) . ') is using an unsupported protocol: ' . var_export($info['scheme']), E_USER_WARNING); 
} 
} 
else 
{ // allow_url_fopen is enabled, lets see if we can open the url 
$info = parse_url( $url ); 

if( !isset($info['scheme']) ) 
{ 
$url = 'http://' . $url; 
} 

$this->fp = fopen( $url, 'rb' ); 

if( $this->fp === false ) 
{ // we cannot open the url 
trigger_error('SHA256MessageURL(' . var_export($url,true) . '): unable to open the url supplied.', E_USER_WARNING); 
} 
} 
} 


// retrieve the next message chunk 
function nextChunk() 
{ 
$this->tossHeader(); 

$ret = false; 

if( is_array($this->chunk) ) 
{ 
// first pass? 
if( $this->first === true ) 
{ 
$this->first = false; 
} 
else 
{ 
$this->curChunk++; 
} 

$ret = (isset($this->chunk[$this->curChunk]) ? $this->chunk[$this->curChunk] : false); 
} 
elseif( $this->fp !== false ) 
{ 
if( $this->first == true ) 
{ 
if( ($l = strlen($this->append)) > 64 ) 
{ 
$ret = substr($this->append, 0, 64); 
$this->append = substr($this->append, 64); 
} 
else 
{ 
$ret = $this->append . fread( $this->fp, 64 - $l ); 
$this->append = ''; 
$this->first = false; 
} 
} 
else 
{ 
$ret = @fread( $this->fp, 64 ); 
} 

$l = strlen($ret); 
$this->size += $l; 

if( $l != 64 ) 
{ 
if(empty($this->append)) 
{ 
$this->append = SHA256Message::calculateFooter( $this->size ); 
} 

if(strlen($ret . $this->append) > 64) 
{ 
$ret = substr( $ret . $this->append, 0, 64 ); 
$this->append = substr( $this->append, 64 - $l ); 
} 
else 
{ 
$ret .= $this->append; 
$this->more = false; 

assert('strlen($ret) % 64 == 0'); 
} 
} 

if(!$this->more) 
{ 
@fclose( $this->fp ); 
$this->fp = false; 
$this->size = false; 
$this->append = ''; 
} 

$this->chunk = (string)$ret; 
} 

return $ret; 
} 


// return the current chunk that was previously loaded 
function currentChunk() 
{ 
if( $this->chunk === '' && $this->fp !== false ) 
{ 
return $this->nextChunk(); 
} 
else 
{ 
return ($this->chunk === '' ? false : $this->chunk); 
} 
} 


// ------------------------------- 
// BEGIN INTERNAL FUNCTIONS 
// ------------------------------- 

// 
function tossHeader() 
{ 
if( $this->headers === true ) 
{ 
$buf = ''; 
while(!feof($this->fp)) 
{ 
$buf .= fread($this->fp, 4); 

if( preg_match("#(\r\n|\n\r|\r|\n)\\1#s", $buf, $match, PREG_OFFSET_CAPTURE ) ) 
{ 
$this->append = substr( $buf, $match[0][1] + strlen($match[0][0]) ); 
$this->headers = false; 
break; 
} 
} 

if( $this->headers === true ) 
{ // the header/content divide was not found, End of file reached. 
trigger_error('SHA256MessageURL::tossHeader(): no headers were sent. Falling back to string hashing', E_USER_NOTICE); 
// prevent this from showing again. 
$this->headers = false; 

// fall back to breaking the whole thing apart into an array of chunks, just like SHA256Message 
$this->chunk = $buf . SHA256Message::calculateFooter( $this->size = strlen( $buf ) ); 
preg_match_all( '#.{64}#s', $str, $this->chunk ); 
$this->chunk = $this->chunk[0]; 
} 
} 
} 
} 


/***************************************************************************** 
* $History: hash_sha256.php $ 
* 
* ***************** Version 4 ***************** 
* User: Feyd Date: 05.04.06 Time: 2:46p 
* Updated in $/inc 
* add some bits to the documentation 
* 
* ***************** Version 3 ***************** 
* User: Feyd Date: 05.04.05 Time: 1:26a 
* Updated in $/inc 
* hashURL() finished. 
* 
* ***************** Version 2 ***************** 
* User: Feyd Date: 05.04.04 Time: 12:40a 
* Updated in $/inc 
* Finished hashFile(). Next up, hashURL(). 
* 
* ***************** Version 1 ***************** 
* User: Feyd Date: 05.04.03 Time: 3:53a 
* Created in $/inc 
* Initial source control addition. 
*****************************************************************************/ 

/* EOF :: Document Settings: tab:4; */ 

?>