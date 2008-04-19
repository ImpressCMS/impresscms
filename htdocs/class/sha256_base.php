<?php 


/******************************************************************************* 
* 
* Hashing abstract base class definition file. 
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
*----- Version 1.1.0 ---------------------------------------------------------- 
* 
* These classes generate PHP level error when an error, warning, or notice of 
* some kind happens during execution. This allows you to "hide" any of these 
* like you would any other function's error output using error_reporting(). 
* 
*----- History ---------------------------------------------------------------- 
* 
* 1.1.0 Split from hash_sha256.php 
* 
*----- Source Control Information --------------------------------------------- 
* 
* $Workfile: hash_base.php $ 
* $Author: Feyd $ 
* $JustDate: 05.04.06 $ 
* $Revision: 4 $ 
* 
* $Header: /inc/hash_base.php 4 05.04.06 2:57p Feyd $ 
* 
******************************************************************************/ 
if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}


// hashing class state and register storage object. Abstract base class only. 
class hashData 
{ 
// final hash 
var $hash = null; 
} 


// hashing class message object. Abstract base class only. 
class hashMessage 
{ 
// retrieve the next chunk 
function nextChunk() 
{ 
trigger_error('hashMessage::nextChunk() NOT IMPLEMENTED', E_USER_WARNING); 
return false; 
} 

// retrieve the current chunk 
function currentChunk() 
{ 
trigger_error('hashMessage::currentChunk() NOT IMPLEMENTED', E_USER_WARNING); 
return false; 
} 
} 


// hashing class message object for files. Abstract base class only. 
class hashMessageFile extends hashMessage 
{ 
function hashMessageFile( $filename ) 
{ 
trigger_error('hashMessageFile::hashMessageFile() NOT IMPLEMENTED', E_USER_WARNING); 
return false; 
} 
} 


// hashing class message object for URLs. Abstract base class only. 
class hashMessageURL extends hashMessage 
{ 
function hashMessageURL( $url ) 
{ 
trigger_error('hashMessageURL::hashMessageURL() NOT IMPLEMENTED', E_USER_WARNING); 
return false; 
} 
} 


// hashing class. Abstract base class only. 
class hash 
{ 
// The base modes are: 
// 'bin' - binary output (most compact) 
// 'bit' - bit output (largest) 
// 'hex' - hexidecimal (default, medium) 
// 'HEX' - hexidecimal (upper case) 

// perform a hash on a string 
function hash($str, $mode = 'hex') 
{ 
trigger_error('hash::hash() NOT IMPLEMENTED', E_USER_WARNING); 
return false; 
} 

// chop the resultant hash into $length byte chunks 
function hashChunk($str, $length, $mode = 'hex') 
{ 
trigger_error('hash::hashChunk() NOT IMPLEMENTED', E_USER_WARNING); 
return false; 
} 

// perform a hash on a file. 
function hashFile($filename, $mode = 'hex') 
{ 
trigger_error('hash::hashFile() NOT IMPLEMENTED', E_USER_WARNING); 
return false; 
} 

// chop the resultant hash into $length byte chunks 
function hashChunkFile($filename, $length, $mode = 'hex') 
{ 
trigger_error('hash::hashChunkFile() NOT IMPLEMENTED', E_USER_WARNING); 
return false; 
} 

// perform a hash on a URL 
function hashURL($url, $timeout = null, $mode = 'hex') 
{ 
trigger_error('hash::hashURL() NOT IMPLEMENTED', E_USER_WARNING); 
return false; 
} 

// chop the resultant hash into $length byte chunks 
function hashChunkURL($url, $length, $timeout = null, $mode = 'hex') 
{ 
trigger_error('hash::hashChunkURL() NOT IMPLEMENTED', E_USER_WARNING); 
return false; 
} 
} 

/***************************************************************************** 
* $History: hash_base.php $ 
* 
* ***************** Version 4 ***************** 
* User: Feyd Date: 05.04.06 Time: 2:57p 
* Updated in $/inc 
* adjust/modify some documentation for release of 1.1.0 
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