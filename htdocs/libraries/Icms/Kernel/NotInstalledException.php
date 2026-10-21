<?php
/**
 * Raised by a headless boot when ImpressCMS is not installed
 *
 * @copyright	The ImpressCMS Project https://www.impresscms.org/
 * @license		https://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU GPL v2
 * @package		ICMS\Kernel
 */

namespace Icms\Kernel;

/**
 * There is no installed mainfile.php. In interactive mode the stub mainfile.php
 * redirects to the installer; a host decides itself how to respond.
 */
class NotInstalledException extends \RuntimeException
{
}
