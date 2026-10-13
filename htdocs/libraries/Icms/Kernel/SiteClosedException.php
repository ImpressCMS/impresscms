<?php
/**
 * Raised by a headless boot when the site is closed for the current visitor
 *
 * @copyright	The ImpressCMS Project https://www.impresscms.org/
 * @license		https://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU GPL v2
 * @package		ICMS\Kernel
 */

namespace Icms\Kernel;

/**
 * The site is closed (`closesite`) and the current visitor may not bypass it.
 *
 * In interactive mode include/common.php renders include/site-closed.php
 * instead; a host turns this exception into its own response (for example 503).
 */
class SiteClosedException extends \RuntimeException
{
}
