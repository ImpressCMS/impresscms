<?php
/**
 * Module RSS Feed Class
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.1
 * @author	Ignacio Segura, "Nachenko"
 */

defined('ICMS_ROOT_PATH') or exit();

/**
 * Generates the data necessary for an RSS feed and assigns it to a smarty template
 *
 * @package	ICMS\Feeds
 */
class icms_feeds_Rss {

	public $title;
	public $url;
	public $description;
	public $language;
	public $charset;
	public $category;
	public $pubDate;
	public $webMaster;
	public $generator;
	public $copyright;
	public $lastbuild;
	public $channelEditor;
	public $width;
	public $height;
	public $ttl;
	public $image = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		global $icmsConfig;
		$this->title = $icmsConfig['sitename'];
		$this->url = ICMS_URL;
		$this->description = $icmsConfig['slogan'];
		$this->language = _LANGCODE;
		$this->charset = _CHARSET;
		$this->pubDate = date('r', time());
		$this->lastbuild = date('r', time());
		$this->webMaster = $icmsConfig['adminmail'];
		$this->channelEditor = $icmsConfig['adminmail'];
		$this->generator = ICMS_VERSION_NAME;
		$this->copyright = _COPYRIGHT . ' ' . formatTimestamp(time(), 'Y')
			. ' ' . $icmsConfig['sitename'];
		$this->width  = 144;
		$this->height = 50;
		$this->ttl    = 60;
		$this->image = array(
			'title' => $this->title,
			'url' => ICMS_URL . '/images/logo.gif',
		);
		$this->feeds = array();
	}

	/**
	 * Render the feed and display it directly
	 */
	public function render() {
		icms::$logger->disableLogger();

		$feed = new \FeedWriter\RSS2();
		$feed->setTitle($this->title);
		$feed->setLink($this->url);
		$feed->setDescription($this->description);
		$feed->setChannelElementsFromArray([
			'webMaster' => $this->webMaster,
			'managingEditor' => $this->channelEditor,
			'ttl' => $this->ttl,
			'copyright' => $this->copyright,
			'category' => $this->category,
			'generator' => $this->generator,
			'language' => $this->language,
			'lastBuildDate' => $this->lastbuild
		]);
		if ($this->image['url']) {
			$feed->setChannelElement('image', [
				'url' => $this->image['url'],
				'title' => $this->title,
				'link' => $this->url,
				'width' => $this->width,
				'height' => $this->height
			]);
		}

		foreach ($this->feeds as $item) {
			$feed->addItem(
				$this->convertFeedItem(
					$feed,
					(array)$item
				)
			);
		}

		header('Content-Type: application/rss+xml');
		echo $feed->generateFeed();
		exit(0);
	}

	/**
	 * Converts feed item from array to real item
	 *
	 * @param \FeedWriter\Feed $feed Feed
	 * @param array $item Feed item data
	 *
	 * @return \FeedWriter\Item
	 */
	protected function convertFeedItem(\FeedWriter\Feed &$feed, array $item) {
		$feed_item = $feed->createNewItem();
		$feed_item->setAuthor($item['author']);
		$feed_item->setDate($item['pubdate']);
		$feed_item->setId($item['guid']);
		$feed_item->setLink($item['link']);
		$feed_item->setTitle($item['title']);
		$feed_item->setDescription($item['description']);
		$feed_item->addElement('category', $item['category']);

		return $feed_item;
	}
}

