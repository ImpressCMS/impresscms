<?php
// $Id: mainfile.php 12313 2013-09-15 21:14:35Z skenow $
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
* Multisite Dispatcher - Domain-based configuration loader
*
* This file routes incoming requests to site-specific configuration files
* based on the HTTP_HOST header. It enables running multiple ImpressCMS
* sites from a single codebase.
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		XOOPS
* @package		Core
* @version		$Id: mainfile.php 12313 2013-09-15 21:14:35Z skenow $
*/

/**
 * Multisite Dispatcher
 * 
 * This dispatcher enables domain-based multisite support for ImpressCMS.
 * It reads the HTTP_HOST, sanitizes it, and loads the appropriate
 * site-specific configuration file from ICMS_TRUSTPATH/sites/
 */

// ImpressCMS is not installed yet - redirect to installer
if (!defined('XOOPS_INSTALL')) {
    // Check if this is a multisite installation
    if (!defined('ICMS_MULTISITE_ROOT_PATH')) {
        // Single site mode - redirect to installer
        header('Location: install/index.php');
        exit();
    }
}

// Multisite dispatcher logic
if (!defined('XOOPS_MAINFILE_INCLUDED')) {
    
    /**
     * Sanitize HTTP_HOST for security
     * 
     * @param string $host The raw HTTP_HOST value
     * @return string The sanitized hostname
     */
    function icms_sanitize_host($host)
    {
        // Remove port number if present
        $host = preg_replace('/:[0-9]+$/', '', $host);
        
        // Convert to lowercase
        $host = strtolower($host);
        
        // Remove any characters that aren't alphanumeric, dash, or dot
        $host = preg_replace('/[^a-z0-9\.\-]/', '', $host);
        
        // Remove leading/trailing dots and dashes
        $host = trim($host, '.-');
        
        // Prevent directory traversal attempts
        $host = str_replace('..', '', $host);
        
        return $host;
    }
    
    /**
     * Get the site configuration path based on HTTP_HOST
     * 
     * @return string|false The path to the site config file, or false if not found
     */
    function icms_get_site_config()
    {
        // Get and sanitize HTTP_HOST
        $http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        
        if (empty($http_host)) {
            // No HTTP_HOST - might be CLI or misconfigured
            return false;
        }
        
        $sanitized_host = icms_sanitize_host($http_host);
        
        if (empty($sanitized_host)) {
            // Sanitization resulted in empty string
            return false;
        }
        
        // If ICMS_MULTISITE_ROOT_PATH is defined, use it to locate config files
        if (defined('ICMS_MULTISITE_ROOT_PATH')) {
            $sites_path = ICMS_MULTISITE_ROOT_PATH . '/sites';
        } else {
            // Try to auto-detect TRUST_PATH location
            // Look for a 'sites' directory one level up from htdocs
            $sites_path = dirname(__DIR__) . '/sites';
        }
        
        // Build the config file path
        $config_file = $sites_path . '/' . $sanitized_host . '/mainfile.php';
        
        // Check if the config file exists
        if (file_exists($config_file) && is_readable($config_file)) {
            return $config_file;
        }
        
        // Try with www prefix removed (e.g., example.com instead of www.example.com)
        if (strpos($sanitized_host, 'www.') === 0) {
            $host_without_www = substr($sanitized_host, 4);
            $config_file = $sites_path . '/' . $host_without_www . '/mainfile.php';
            
            if (file_exists($config_file) && is_readable($config_file)) {
                return $config_file;
            }
        }
        
        // Try default site configuration
        $default_config = $sites_path . '/default/mainfile.php';
        if (file_exists($default_config) && is_readable($default_config)) {
            return $default_config;
        }
        
        return false;
    }
    
    // Load the appropriate site configuration
    $site_config = icms_get_site_config();
    
    if ($site_config !== false) {
        // Load the site-specific configuration
        require $site_config;
    } else {
        // No multisite config found - check if single-site mainfile exists
        // This maintains backward compatibility with existing installations
        $legacy_mainfile = __DIR__ . '/mainfile.dist.php';
        
        if (file_exists($legacy_mainfile)) {
            require $legacy_mainfile;
        } else {
            // No configuration found at all
            if (!defined('XOOPS_INSTALL')) {
                header('Location: install/index.php');
                exit();
            }
        }
    }
}
