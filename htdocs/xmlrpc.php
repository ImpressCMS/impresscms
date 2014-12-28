<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

/**
 * @copyright    http://www.xoops.org/ The XOOPS Project
 * @copyright    http://www.impresscms.org/ The ImpressCMS Project
 * @license      http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package      core
 * @since        XOOPS
 * @author       http://www.xoops.org The XOOPS Project
 * @author       Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version      $Id$
 */

define('XOOPS_XMLRPC', 1);
include './mainfile.php';
error_reporting(0);
include_once ICMS_LIBRARIES_PATH . '/xml/rpc/xmlrpctag.php';
include_once ICMS_LIBRARIES_PATH . '/xml/rpc/xmlrpcparser.php';

icms::$logger->disableLogger();

$response = new XoopsXmlRpcResponse();
$parser = new XoopsXmlRpcParser(rawurlencode($GLOBALS['HTTP_RAW_POST_DATA']));
if (!$parser->parse())
{
	$response->add(new XoopsXmlRpcFault(102));
} else {
	$module_handler = icms::handler('icms_module');
	$module =& $module_handler->getByDirname('news');
	if (!is_object($module))
	{
		$response->add(new XoopsXmlRpcFault(110));
	} else {
		$methods = explode('.', $parser->getMethodName());
		switch($methods[0])
		{
			case 'blogger':
				include_once ICMS_LIBRARIES_PATH. ' /xml/rpc/bloggerapi.php';
				$rpc_api = new BloggerApi($parser->getParam(), $response, $module);
				break;
			case 'metaWeblog':
				include_once ICMS_LIBRARIES_PATH . '/xml/rpc/metaweblogapi.php';
				$rpc_api = new MetaWeblogApi($parser->getParam(), $response, $module);
				break;
			case 'mt':
				include_once ICMS_LIBRARIES_PATH . '/xml/rpc/movabletypeapi.php';
				$rpc_api = new MovableTypeApi($parser->getParam(), $response, $module);
				break;
			case 'xoops':
			default:
				include_once ICMS_LIBRARIES_PATH . '/xml/rpc/xoopsapi.php';
				$rpc_api = new XoopsApi($parser->getParam(), $response, $module);
				break;
		}
		$method = $methods[1];
		if (!method_exists($rpc_api, $method))
		{
			$response->add(new XoopsXmlRpcFault(107));
		} else {
			$rpc_api->$method();
		}
	}
}
$payload =& $response->render();
//$fp = fopen(ICMS_CACHE_PATH.'/xmllog.txt', 'w');
//fwrite($fp, $payload);
//fclose($fp);
header('Server: XOOPS XML-RPC Server');
header('Content-type: text/xml');
header('Content-Length: '.strlen($payload));
echo $payload;

