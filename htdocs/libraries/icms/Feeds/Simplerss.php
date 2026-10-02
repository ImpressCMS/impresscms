<?php
/**
 * Class handling RSS feeds, using SimplePie class.
 *
 * @copyright   The ImpressCMS Project http://www.impresscms.org/
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category    ICMS
 * @package     Feeds
 * @subpackage  RSS
 * @since       1.2
 * @author      malanciault <marcan@impresscms.org>
 * @version     $Id: Simplerss.php 12107 2012-11-08 15:22:12Z skenow $
 */

declare(strict_types=1);

namespace Icms\Feeds;

use SimplePie\SimplePie;

defined('ICMS_ROOT_PATH') or exit();

/**
 * ImpressCMS wrapper around {@link SimplePie}.
 *
 * @category    ICMS
 * @package     Feeds
 * @subpackage  RSS
 */
class Simplerss extends SimplePie
{
    /**
     * Constructor.
     *
     * @param string|null $feed_url       URL to parse, or null to configure manually before calling {@see init()}.
     * @param int|null    $cache_duration Number of seconds the cache file is kept for.
     */
    public function __construct($feed_url = null, $cache_duration = null)
    {
        /* SimplePie 1.3+ does not accept arguments in the constructor */
        parent::__construct();

        $this->set_cache_location(ICMS_CACHE_PATH);

        if ($cache_duration !== null) {
            $this->set_cache_duration($cache_duration);
        }

        // Only init the script if we're passed a feed URL
        if ($feed_url !== null) {
            $this->set_feed_url($feed_url);
            $this->init();
        }
    }
}

\class_alias(Simplerss::class, 'icms_feeds_Simplerss');
