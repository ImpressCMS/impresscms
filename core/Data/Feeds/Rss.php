<?php
/**
 * Module RSS Feed Class
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.1
 * @author	Ignacio Segura, "Nachenko"
 */

namespace ImpressCMS\Core\Data\Feeds;

use icms;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

/**
 * Generates the data necessary for an RSS feed and assigns it to a smarty template
 *
 * @package	ICMS\Feeds
 */
class Rss {

	public $title;
	public $url;
	public $description;
	public $pubDate;
	public $lastbuild;
	public $ttl;
	/**
	 * @var array
	 */
	public $feeds = [];
	/**
	 * @var string
	 */
	public $copyright;

	/**
	 * Constructor
	 */
	public function __construct() {
		global $icmsConfig;
		$this->title = $icmsConfig['sitename'];
		$this->url = ICMS_URL;
		$this->description = $icmsConfig['slogan'];
		$this->pubDate = date('r', time());
		$this->lastbuild = date('r', time());
		$this->copyright = _COPYRIGHT . ' ' . formatTimestamp(time(), 'Y')
			. ' ' . $icmsConfig['sitename'];
		$this->ttl    = 60;
		$this->feeds = [];
	}

	/**
	 * Render the feed and display it directly
	 */
	public function render() {
		icms::$logger->disableLogger();

		$feed = new Feed();

		$channel = new Channel();
		$channel
			->title($this->title)
			->url($this->url)
			->description($this->description)
			->copyright($this->copyright)
			->ttl($this->ttl)
			->pubDate($this->pubDate)
			->lastBuildDate($this->lastbuild)
			->language($this->language);
		$channel->appendTo($feed);

		foreach ($this->feeds as $item) {
			(new Item())
				->title($item['title'])
				->description($item['description'])
				->category($item['category'])
				->url($item['link'])
				->guid($item['guid'])
				->pubDate($item['pubdate'])
				->author($item['author'])
				->appendTo($channel);
		}

		header('Content-Type: application/rss+xml');
		echo $feed->render();
	}
}

