<?php
namespace ImpressCMS\Core;

use League\MimeTypeDetection\GeneratedExtensionToMimeTypeMap;

/**
 * Different utilities used by the core
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.3
 * @author	marcan <marcan@impresscms.org>
 */

/**
 * Utils class
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS
 * @since	1.3
 * @author	marcan <marcan@impresscms.org>
 */
class Utils {
	/**
	 * mimetypes
	 *
	 * @return   array     array of mimetypes
	 */
	static public function mimetypes() {
		return GeneratedExtensionToMimeTypeMap::MIME_TYPES_FOR_EXTENSIONS;
	}
}

