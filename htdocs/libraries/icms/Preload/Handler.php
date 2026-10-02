<?php
/**
 * ICMS Preload Handler
 *
 * @copyright   http://www.impresscms.org/ The ImpressCMS Project
 * @license     LICENSE.txt
 * @category    ICMS
 * @package     Preload
 * @since       1.1
 * @author      marcan <marcan@impresscms.org>
 * @author      Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version     SVN: $Id: Handler.php 12368 2013-11-17 04:04:28Z skenow $
 */

declare(strict_types=1);

namespace Icms\Preload;

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * Handles preload events automatically detected from the files in ICMS_PRELOAD_PATH.
 *
 * @copyright   The ImpressCMS Project http://www.impresscms.org/
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category    ICMS
 * @package     Preload
 * @since       1.1
 * @author      marcan <marcan@impresscms.org>
 */
class Handler
{
    /**
     * @var array list of all preload files in ICMS_PRELOAD_PATH
     */
    private $_preloadFilesArray = [];

    /**
     * @var array list of all events for all preload files, indexed by event name and sorted by order of execution
     */
    private $_preloadEventsArray = [];

    /**
     * Constructor.
     *
     * Determine the preloads by scanning the preloads directory and the preloads directory for each module specified.
     * Preloads in the system preloads directory will execute for all requests. Preloads in a module's preload
     * directory will only load for that module's requests.
     */
    public function __construct()
    {
        $preloadFilesArray = str_replace('.php', '', \icms_core_Filesystem::getFileList(
            ICMS_PRELOAD_PATH,
            '',
            ['php']
        ));
        foreach ($preloadFilesArray as $filename) {
            // exclude index.html
            if (!in_array($this->getClassName($filename), get_declared_classes())) {
                $this->_preloadFilesArray[] = $filename;
                $this->addPreloadEvents($filename);
            }
        }

        // add ondemand preload
        global $icmsOnDemandPreload;
        if (isset($icmsOnDemandPreload) && count($icmsOnDemandPreload) > 0) {
            foreach ($icmsOnDemandPreload as $onDemandPreload) {
                $this->_preloadFilesArray[] = $onDemandPreload['filename'];
                $this->addPreloadEvents($onDemandPreload['filename'], $onDemandPreload['module']);
            }
        }
    }

    /**
     * Add the events defined in filename.
     *
     * To be attached to the preload events, methods in each preload class must be prefixed with 'event'
     * and have a defined event (like 'beforeFilterHTMLinput'). A fully qualified method would look
     * like 'eventBeforeFilterHTMLinput'.
     *
     * @todo implement an order parameter, to enable prioritizing responses to an event
     */
    public function addPreloadEvents($filename, $module = false): void
    {
        if ($module) {
            $filepath = ICMS_ROOT_PATH . "/modules/$module/preload/$filename.php";
        } else {
            $filepath = ICMS_PRELOAD_PATH . "/$filename.php";
        }
        include_once $filepath;

        $classname = $this->getClassName($filename);

        if (in_array($classname, get_declared_classes())) {
            $preloadItem = new $classname();

            $class_methods = get_class_methods($classname);
            foreach ($class_methods as $method) {
                if (strpos($method, 'event') === 0) {
                    $preload_event = strtolower(str_replace('event', '', $method));

                    $callback = [$preloadItem, $method];
                    \icms_Event::attach('icms', $preload_event, $callback);
                }
            }
        }
    }

    /**
     * Access the only instance of this class.
     */
    public static function &getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Triggers a specific event on all the libraries.
     */
    public function triggerEvent(string $event, $array = []): void
    {
        $event = strtolower($event);
        \icms_Event::trigger('icms', $event, null, $array);
    }

    /**
     * Construct the name of the class based on the filename.
     *
     * All preloads will be discovered if the class name is the
     * file name without the extension (uppercase the first letter) prefixed with 'IcmsPreload'
     * For example, file name = protectEmail.php -> class name = IcmsPreloadProtectEmail
     */
    public function getClassName(string $filename): string
    {
        return 'IcmsPreload' . ucfirst(str_replace('.php', '', $filename));
    }
}

\class_alias(Handler::class, 'icms_preload_Handler');
