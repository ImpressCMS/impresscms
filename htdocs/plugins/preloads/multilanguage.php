<?php
/**
 * Defines the Multilanguage tag handler and attaches it to the event handler during preload
 * 
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		:ibraries
 * @since		1.3
 * @version		$Id: multilanguage.php 11194 2011-04-20 23:49:33Z skenow $
 */

/**
 * Handles selection and display of multilanguage texts
 * 
 * @category	ICMS
 * @package		Libraries
 */
class icms_MultilanguageEventHandler {

	/**
	 * Attaches the multilanguage handler to the event handler
	 * 
	 */
	static public function setup() {
		icms_Event::attach('icms', 'loadService-session', array(__CLASS__, 'initMultilang'));
	}

	/**
	 * Initializes the multilanguage process
	 * 
	 */
	static public function initMultilang() {
		global $icmsConfigMultilang, $icmsConfig;
		if ($icmsConfigMultilang['ml_enable']) {
			require_once ICMS_INCLUDE_PATH . '/im_multilanguage.php' ;
			$easiestml_langs = explode(',', $icmsConfigMultilang['ml_tags']);

			$easiestml_langpaths = icms_core_Filesystem::getDirList(ICMS_ROOT_PATH . "/language/");
			$langs = array_combine($easiestml_langs, explode(',', $icmsConfigMultilang['ml_names']));

			if ($icmsConfigMultilang['ml_autoselect_enabled']
				&& isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])
				&& $_SERVER['HTTP_ACCEPT_LANGUAGE'] != ""
			) {
				$autolang = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
				if (in_array($autolang, $easiestml_langs)) {
					$icmsConfig['language'] = $langs[$autolang];
				}
			}

			if (isset($_GET['lang']) && isset($_COOKIE['lang'])) {
				if (in_array($_GET['lang'], $easiestml_langs)) {
					$icmsConfig['language'] = $langs[$_GET['lang']];
					if (isset($_SESSION['UserLanguage'])) {
						$_SESSION['UserLanguage'] = $langs[$_GET['lang']];
					}
				}
			} elseif (isset($_COOKIE['lang']) && isset($_SESSION['UserLanguage'])) {
				if ($_COOKIE['lang'] != $_SESSION['UserLanguage']) {
					if (in_array($_SESSION['UserLanguage'], $langs)) {
						$icmsConfig['language'] = $_SESSION['UserLanguage'];
					}
				} else {
					if (in_array($_COOKIE['lang'], $easiestml_langs)) {
						$icmsConfig['language'] = $langs[$_COOKIE['lang']];
					}
				}
			} elseif (isset($_COOKIE['lang'])) {
				if (in_array($_COOKIE['lang'], $easiestml_langs)) {
					$icmsConfig['language'] = $langs[$_COOKIE['lang']];
					if (isset( $_SESSION['UserLanguage'] )) {
						$_SESSION['UserLanguage'] = $langs[$_GET['lang']];
					}
				}
			} elseif (isset($_GET['lang'])) {
				if (in_array($_GET['lang'], $easiestml_langs)) {
					$icmsConfig['language'] = $langs[$_GET['lang']];
				}
			}
		}
		self::applyLocale($icmsConfig['language']);
	}

	/**
	 * Maps an ImpressCMS language folder name to a locale identifier
	 *
	 * @param string $language  ImpressCMS language name (e.g. 'english', 'francais')
	 * @return string           ICU/POSIX locale identifier (e.g. 'en_US')
	 */
	private static function getLocaleForLanguage(string $language): string
	{
		$localeMap = [
			'arabic'      => 'ar_SA',
			'belarusian'  => 'be_BY',
			'bulgarian'   => 'bg_BG',
			'catalan'     => 'ca_ES',
			'chinese'     => 'zh_CN',
			'schinese'    => 'zh_CN',
			'tchinese'    => 'zh_TW',
			'croatian'    => 'hr_HR',
			'czech'       => 'cs_CZ',
			'danish'      => 'da_DK',
			'dutch'       => 'nl_NL',
			'nederlands'  => 'nl_NL',
			'english'     => 'en_US',
			'estonian'    => 'et_EE',
			'finnish'     => 'fi_FI',
			'french'      => 'fr_FR',
			'francais'    => 'fr_FR',
			'german'      => 'de_DE',
			'greek'       => 'el_GR',
			'hebrew'      => 'he_IL',
			'hungarian'   => 'hu_HU',
			'icelandic'   => 'is_IS',
			'indonesian'  => 'id_ID',
			'italian'     => 'it_IT',
			'italiano'    => 'it_IT',
			'japanese'    => 'ja_JP',
			'korean'      => 'ko_KR',
			'latvian'     => 'lv_LV',
			'lithuanian'  => 'lt_LT',
			'norwegian'   => 'nb_NO',
			'persian'     => 'fa_IR',
			'polish'      => 'pl_PL',
			'portuguese'  => 'pt_BR',
			'romanian'    => 'ro_RO',
			'russian'     => 'ru_RU',
			'serbian'     => 'sr_RS',
			'slovak'      => 'sk_SK',
			'slovenian'   => 'sl_SI',
			'spanish'     => 'es_ES',
			'swedish'     => 'sv_SE',
			'thai'        => 'th_TH',
			'turkish'     => 'tr_TR',
			'ukrainian'   => 'uk_UA',
			'vietnamese'  => 'vi_VN',
		];
		return $localeMap[$language] ?? 'en_US';
	}

	/**
	 * Applies PHP and Intl locales for the active ImpressCMS language
	 *
	 * Sets the process locale via setlocale() so that locale-aware C-library
	 * functions (e.g. strftime, number_format wrappers) use the correct locale.
	 * When the PHP intl extension is available, Locale::setDefault() is also
	 * called so that Intl formatters (NumberFormatter, DateTimeFormatter, etc.)
	 * use the same locale.  Intl formatters are recommended for new code.
	 *
	 * @param string $language  ImpressCMS language name (e.g. 'english', 'francais')
	 */
	private static function applyLocale(string $language): void
	{
		$locale = self::getLocaleForLanguage($language);
		// Provide UTF-8 variants as fallbacks for portability across platforms
		setlocale(LC_ALL, $locale . '.UTF-8', $locale . '.utf8', $locale);
		if (extension_loaded('intl')) {
			\Locale::setDefault($locale);
		}
	}
}

icms_MultilanguageEventHandler::setup();

