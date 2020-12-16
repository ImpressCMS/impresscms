<?php

use Phoenix\Migration\AbstractMigration;

class ConvertAllTextsanitizersContentsToHTML extends AbstractMigration
{
	protected function up(): void
	{
		foreach ($this->fetchNextColumnToChange() as [$table, $column, $indexColumn, $id, $originalVal]) {
			$val = $this->replaceYoutubeTags(
				$this->replaceWindowsMediaPlayerTags(
					$this->replaceHashtags(
						$this->replaceWikiTags(
							$this->replaceHiddenContentTags(
								$this->replaceMentionsTags(
									$this->replaceSyntaxHighlightCSSTags(
										$this->replaceSyntaxHighlightHTMLTags(
											$this->replaceSyntaxHighlightJSTags(
												$this->replaceSyntaxHighlightPHPTags(
													$originalVal
												)
											)
										)
									)
								)
							)
						)
					)
				)
			);
			if ($val === $originalVal) {
				continue;
			}
			$this->update($table, [$column => $val], [$indexColumn => $id]);
		}
	}

	protected function down(): void
	{
		throw new Exception('down not implemented from this point');
	}

	/**
	 * Replaces code highlighting PHP tags
	 *
	 * @param string $val String where to replace
	 *
	 * @return string
	 */
	private function replaceSyntaxHighlightPHPTags(string $val): string
	{
		return preg_replace_callback("/\[code_php](.*)\[\/code_php\]/sU", function($matches) {
			return '<pre class="icmsCodeCss"><code class="php">'.htmlentities($matches[1]).'</code></pre>';
		}, $val);
	}

	/**
	 * Replaces code highlighting JS tags
	 *
	 * @param string $val String where to replace
	 *
	 * @return string
	 */
	private function replaceSyntaxHighlightJSTags(string $val): string
	{
		return preg_replace_callback("/\[code_js](.*)\[\/code_js\]/sU", function($matches) {
			return '<pre class="icmsCodeCss"><code class="js">'.htmlentities($matches[1]).'</code></pre>';
		}, $val);
	}

	/**
	 * Replaces code highlighting HTML tags
	 *
	 * @param string $val String where to replace
	 *
	 * @return string
	 */
	private function replaceSyntaxHighlightHTMLTags(string $val): string
	{
		return preg_replace_callback("/\[code_html](.*)\[\/code_html\]/sU", function($matches) {
			return '<pre class="icmsCodeCss"><code class="html">'.htmlentities($matches[1]).'</code></pre>';
		}, $val);
	}

	/**
	 * Replaces code highlighting CSS tags
	 *
	 * @param string $val String where to replace
	 *
	 * @return string
	 */
	private function replaceSyntaxHighlightCSSTags(string $val): string
	{
		return preg_replace_callback("/\[code_css](.*)\[\/code_css\]/sU", function($matches) {
			return '<pre class="icmsCodeCss"><code class="css">'.htmlentities($matches[1]).'</code></pre>';
		}, $val);
	}

	/**
	 * Replaces mentions tags
	 *
	 * @param string $val String where to replace
	 *
	 * @return string
	 */
	private function replaceMentionsTags(string $val): string
	{
		icms_loadLanguageFile("core", "user");
		return preg_replace_callback("#([\s*])@(?:([\w\-]+)|\[([\w\s\-]+)\])#", function($matches) {
			$text = $matches[2];
			$prefix = $matches[1];
			if (empty($text)) {
				return $text;
			}
			$userHandler = & icms::handler('icms_member');
			$criteria = new icms_db_criteria_Item('uname', $text);
			$userId = $userHandler->getUsers($criteria);
			if (!$userId) {
				return $prefix . "@" . $text;
			}
			return $prefix . "<a href='" . sprintf(ICMS_URL . '/userinfo.php?uid=%u', $userId[0]->getVar('uid')) . "' title='" . sprintf(_US_ALLABOUT, $text) . "'>@" . $text . "</a>";
		}, $val);
	}

	/**
	 * Replaces hidden content tags
	 *
	 * @param string $val String where to replace
	 *
	 * @return string
	 */
	private function replaceHiddenContentTags(string $val): string
	{
		// we can't do anything because this is dynamic tag
		return $val;
	}

	/**
	 * Replaces wiki tags
	 *
	 * @param string $val String where to replace
	 *
	 * @return string
	 */
	private function replaceWikiTags(string $val): string
	{
		$wikiTagLink = 'https://' . _LANGCODE . '.wikipedia.org/wiki/%s';
		return preg_replace_callback("/\[\[([^\]]*)\]\]/sU", function ($matches) use ($wikiTagLink) {
			if (empty($matches[1])) {
				return $matches[1];
			}
			return '<a href="' . sprintf($wikiTagLink, $matches[1]) . '" target="_blank" title="">' . $matches[1] . '</a>';
		}, $val);
	}

	/**
	 * Replaces hash tags
	 *
	 * @param string $val String where to replace
	 *
	 * @return string
	 */
	private function replaceHashtags(string $val): string
	{
		$hashTagLink = ICMS_URL . '/search.php?query=%s&amp;action=results';
		icms_loadLanguageFile('core', 'search');
		return preg_replace_callback("#([\s\r])\#(?:([\w\-]+)|\[([\w\s\-]+)\])#", function ($matches) use ($hashTagLink) {
			$text = $matches[2];
			$prefix = $matches[1];
			if (empty($text)) {
				return $text;
			}
			return $prefix . "<a href='" . sprintf($hashTagLink, urlencode($text)) . "' title='" . _SR_SEARCHRESULTS . ": " . $text . "'>#" . $text . "</a>";
		}, $val);
	}

	/**
	 * Replaces windows media player tags
	 *
	 * @param string $val String where to replace
	 *
	 * @return string
	 */
	private function replaceWindowsMediaPlayerTags(string $val): string
	{
		$pattern = "/\[wmp=(['\"]?)([^\"']*),([^\"']*)\\1]([^\"]*)\[\/wmp\]/sU";
		$replacement = "<object classid=\"clsid:6BF52A52-394A-11D3-B153-00C04F79FAA6\" id=\"WindowsMediaPlayer\" width=\"\\2\" height=\"\\3\">\n";
		$replacement .= "<param name=\"URL\" value=\"\\4\">\n";
		$replacement .= "<param name=\"AutoStart\" value=\"0\">\n";
		$replacement .= "<embed autostart=\"0\" src=\"\\4\" type=\"video/x-ms-wmv\" width=\"\\2\" height=\"\\3\" controls=\"ImageWindow\" console=\"cons\"> </embed>";
		$replacement .= "</object>\n";
		return preg_replace($pattern, $replacement, $val);
	}

	/**
	 * Replaces youtube tags
	 *
	 * @param string $val String where to replace
	 *
	 * @return string
	 */
	private function replaceYoutubeTags(string $val): string
	{
		return preg_replace_callback("/\[youtube=(['\"]?)([^\"']*),([^\"']*)\\1]([^\"]*)\[\/youtube\]/sU", function ($matches) {
			$url = $matches[4];
			$width = $matches[2];
			$height = $matches[3];
			if (!preg_match("/^http:\/\/(www\.)?youtube\.com\/watch\?v=(.*)/i", $url, $matches)) {
				//trigger_error("Not matched: {$url} {$width} {$height}", E_USER_WARNING);
				return $matches[0];
			}
			$src = "http://www.youtube.com/v/" . $matches[2];
			if (empty($width) || empty($height)) {
				if (!$dimension = @getimagesize($src)) {
					return "";
				}
				if (!empty($width)) {
					$height = $dimension[1] * $width / $dimension[0];
				} elseif (!empty($height)) {
					$width = $dimension[0] * $height / $dimension[1];
				} else {
					list($width, $height) = array($dimension[0], $dimension[1]);
				}
			}
			$code = "<object width='{$width}' height='{$height}'><param name='movie' value='{$src}'></param>" .
				"<param name='wmode' value='transparent'></param>" .
				"<embed src='{$src}' type='application/x-shockwave-flash' wmode='transparent' width='425' height='350'></embed>" .
				"</object>";
			return $code;
		}, $val);
	}

	private function fetchNextColumnToChange()
	{
		foreach ($this->getNextColumnToChange() as [$table, $column, $indexColumn]) {
			$start = 0;
			$batch = 1000;
			while (true) {
				$items = $this->select(
					'SELECT `' . $indexColumn . '` item_index, `' . $column . '` item_val FROM `' . $table . '` WHERE `' . $column . '` IS NOT NULL AND `' . $column . '` != \'\' LIMIT ' . $start . ',' . $batch
				);
				if (empty($items)) {
					break;
				}
				$start += $batch;
				foreach ($items as $row) {
					yield [
						$table, //0
						$column, //1
						$indexColumn, //2
						$row['item_index'], //3
						$row['item_val'] //4
					];
				}
			}
		}
	}

	/**
	 * Gets next column to change
	 *
	 * @return Generator|null
	 */
	private function getNextColumnToChange(): ?\Generator
	{
		foreach ($this->getAllTablesNames() as $tableName) {
			$columns = $this->getTable($tableName)->getColumns();
			$autoIncColumn = null;
			foreach ($columns as $column) {
				if ($column->getSettings()->isAutoincrement()) {
					$autoIncColumn = $column;
					break;
				}
			}
			if ($autoIncColumn === null) {
				continue;
			}
			foreach ($columns as $column) {
				if ($column->getType() === 'string' || $column->getType() === 'text') {
					yield [
						$tableName,
						$column->getName(),
						$autoIncColumn->getName()
					];
				}
			}
		}
	}

	/**
	 * Gets all tables names
	 *
	 * @return string[]
	 */
	private function getAllTablesNames(): array
	{
		/**
		 * @var \icms_db_Connection $db
		 */
		$db = \icms::getInstance()->get('db-connection-1');

		$query = $db->prepareWithValues('SHOW TABLES LIKE :rule;', [
			'rule' => $db->prefix('') . '%'
		]);
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_COLUMN);
	}

}
