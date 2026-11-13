<?php
/**
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	core
 * @since		2009
 * @author	Wiliam Hall (aka Mr. Theme) <mrtheme@impresscms.org>
 * @version	$Id: suggest.php 12403 2014-01-26 21:35:08Z skenow $
 **/
include 'mainfile.php';
icms::$logger->disableLogger();

/**
 * Only the administration quick search uses this script, so it is limited to
 * administrators of the system module.
 */
function icms_suggest_isAdmin(): bool
{
	if (!is_object(icms::$user)) {
		return false;
	}

	$systemModule = icms::handler('icms_module')->getByDirname('system');
	if (!is_object($systemModule)) {
		return false;
	}

	return (bool) icms::$user->isAdmin($systemModule->getVar('mid'));
}

function icms_suggest_escape(string $value): string
{
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function icms_suggest_truncate(string $value, int $length): string
{
	if (mb_strlen($value) <= $length) {
		return $value;
	}

	return mb_substr($value, 0, $length) . '...';
}

if (!isset($_POST['queryString'])) {
	echo 'There should be no direct access to this script!';
	exit;
}

if (!icms_suggest_isAdmin()) {
	http_response_code(403);
	exit;
}

$queryString = trim((string) $_POST['queryString']);

// Is the string length greater than 2?
if (mb_strlen($queryString) <= 2) {
	exit;
}

$like = '%' . addcslashes($queryString, '\%_') . '%';
$sql = 'SELECT s.name, s.img, s.`desc`, s.url FROM `' . XOOPS_DB_PREFIX . '_autosearch_list` s'
	. ' INNER JOIN `' . XOOPS_DB_PREFIX . '_autosearch_cat` c ON s.cat_id = c.cid'
	. ' WHERE s.name LIKE ? ORDER BY s.cat_id LIMIT 8';
$statement = icms::$db->prepare($sql);
$executed = $statement && $statement->execute(array($like));

echo "<ul id='searchresults'>";

if (!$executed) {
	echo '<li>ERROR: There was a problem with the query.</li>';
	echo '</ul>';
	exit;
}

$results = $statement->fetchAll(PDO::FETCH_ASSOC);

if (empty($results)) {
	echo "<li><a href='javascript:void(0);'>
		<img src='" . ICMS_IMAGES_SET_URL . "/actions/exit.png' alt='no results found' />
		<span class='searchheading'>Sorry</span>
		<span class='searchdesc'>No results were found that matched your query. Please try again.</span></a></li>";
}

foreach ($results as $result) {
	echo '<li><a href="' . icms_suggest_escape(ICMS_URL . $result['url']) . '">';
	echo '<img src="' . icms_suggest_escape(ICMS_URL . $result['img']) . '" alt="" />';
	echo '<span class="searchheading">' . icms_suggest_escape(icms_suggest_truncate((string) $result['name'], 35)) . '</span>';
	echo '<span class="searchdesc">' . icms_suggest_escape(icms_suggest_truncate((string) $result['desc'], 80)) . '</span></a></li>';
}

echo '<li><span class="seperator"><a href="http://www.impresscms.org" rel="external">ImpressCMS Project</a></span><br /></li>';
echo '</ul>';
