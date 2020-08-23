<?php
/*
	itemsCount:		Total number of items in the current list
	pageSize:		Number of items in each page
	offset:			Index of the 1st item currently displayed
	linksCount:		Number of direct links to show (default to 5)
	url:			URL mask used to generate links (%s will be replace by offset)
	itemsCount=$items_count pageSize=$module_config.perpage offset=$offset
	url="viewcat.php?cid=`$entity.cid`&orderby=`$sort_order`&offset=%s"
*/

class xoPageNavFunction
{

	/**
	 * Name of function in smarty
	 */
	const NAME = 'xoPageNav';

	/**
	 * Disabled constructor.
	 */
	private function __construct()
	{
	}

	/**
	 * Magic method to use class as function
	 *
	 * @param $params
	 * @param $smarty
	 * @return string
	 */
	public function __invoke($params, &$smarty)
	{
		$pageSize = isset($params['pageSize']) ? (int)$params['pageSize'] : 10;
		$itemsCount = isset($params['itemsCount']) ? (int)$params['itemsCount'] : 10;
		$offset = isset($params['offset']) ? (int)$params['offset'] : 0;
		$linksCount = isset($params['linksCount']) ? (int)$params['linksCount'] : 0;
		$url = $params['url'] ?? '#';
		$class = $params['class'] ?? '';
		if ($pageSize < 1) {
			$pageSize = 10;
		}
		$pagesCount = (int)($itemsCount / $pageSize);
		if ($itemsCount <= $pageSize || $pagesCount < 2) {
			return '';
		}
		$str = '';
		$currentPage = (int)($offset / $pageSize) + 1;
		$lastPage = (int)($itemsCount / $pageSize) + 1;

		$minPage = min(1, ceil($currentPage - $linksCount / 2));
		$maxPage = max($lastPage, floor($currentPage + $linksCount / 2));

		if ($currentPage > 1) {
			$str .= '<a href="' . icms::url(str_replace('%s', $offset - $pageSize, $url)) . '">Previous</a>';
		}
		for ($i = $minPage; $i <= $maxPage; $i++) {
			$tgt = htmlspecialchars(icms::url(str_replace('%s', ($i - 1) * $pageSize, $url)), ENT_QUOTES);
			$str .= "<a href='$tgt'>$i</a>";
		}
		if ($currentPage < $lastPage) {
			$str .= '<a href="' . icms::url(str_replace('%s', $offset + $pageSize, $url)) . '">Next</a>';
		}
		$class = @!empty($class) ? htmlspecialchars($class, ENT_QUOTES) : 'pagenav';

		$str = "<div class='$class'>$str</div>";
		return $str;
	}
}

