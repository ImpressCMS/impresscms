<?php
/**
 * GeSHi Syntax Highlighting via Preload
 *
 * Moves GeSHi integration from textsanitizer plugins into the preload system.
 * Keeps GeSHi library location unchanged and preserves existing markup output
 * for backward compatibility.
 */

class IcmsPreloadSyntaxhighlight extends icms_preload_Item
{
    /**
     * After filtering plain textarea content
     *
     * @param array{0:string} $array [$text, $smiley, $icode, $image, $br]
     * @return void
     */
//    public function eventBeforeDisplayHTMLarea(array $array): void
//    {
//        icms_core_debug::message("filter!");
//		$array[0] = self::applyGeshi($array[0]);
//    }

    /**
     * After filtering HTML content
     *
     * @param array{0:string} $array [$html, 1, $br]
     * @return void
     */
    public function eventAfterFilterHTMLdisplay(array $array): void
    {
		$array[0] = self::applyGeshi($array[0]);
    }

    /**
     * Apply GeSHi highlighting for language-specific [code_*] tags.
     * Supported: [code_php], [code_js], [code_css], [code_html]
     *
     * @param string $text
     * @return string
     */
    private static function applyGeshi(string $text): string
    {
        // Fast bail-out
        if (strpos($text, '[code_') === false) {
            return $text;
        }

        // Ensure GeSHi is available (keep library location unchanged)
        if (!@include_once ICMS_LIBRARIES_PATH . '/geshi/geshi.php') {
            // If GeSHi missing, degrade gracefully by wrapping in <pre><code>
            $text = self::applyFallback($text);
            return $text;
        }

        // Replace each supported language tag
        $text = preg_replace_callback('/\[code_php](.*)\[\/code_php]/sU', function ($m) {
            return self::highlight($m[1], 'php', 'icmsCodePhp', false);
        }, $text);

        $text = preg_replace_callback('/\[code_js](.*)\[\/code_js]/sU', function ($m) {
            return self::highlight($m[1], 'javascript', 'icmsCodeJs', false);
        }, $text);

        $text = preg_replace_callback('/\[code_css](.*)\[\/code_css]/sU', function ($m) {
            return self::highlight($m[1], 'css', 'icmsCodeCss', false);
        }, $text);

        $text = preg_replace_callback('/\[code_html](.*)\[\/code_html]/sU', function ($m) {
            // historically wrapped in <div><pre><code>
            return self::highlight($m[1], 'html4strict', 'icmsCodeHtml', true);
        }, $text);

        return $text;
    }

    /**
     * Highlight a code fragment with GeSHi and wrap for BC with existing CSS classes.
     *
     * @param string $raw
     * @param string $language
     * @param string $wrapperClass
     * @param bool $wrapPre Add a <pre> wrapper (kept for HTML BC)
     * @return string
     */
    private static function highlight(string $raw, string $language, string $wrapperClass, bool $wrapPre): string
    {
        $source = icms_core_DataFilter::undoHtmlSpecialChars($raw);

        $geshi = new GeSHi($source, $language);
        $geshi->set_header_type(GESHI_HEADER_NONE);
        $geshi->set_encoding(_CHARSET);
        $geshi->set_link_target('_blank');
        $code = $geshi->parse_code();

        if ($wrapPre) {
            return '<div class="' . $wrapperClass . '"><pre><code>' . $code . '</code></pre></div>';
        }

        return '<div class="' . $wrapperClass . '"><code>' . $code . '</code></div>';
    }

    /**
     * Fallback when GeSHi is not available.
     *
     * @param string $text
     * @return string
     */
    private static function applyFallback(string $text): string
    {
        $wrap = function (string $raw, string $cls, bool $pre): string {
            $src = icms_core_DataFilter::undoHtmlSpecialChars($raw);
            $inner = htmlspecialchars($src, ENT_QUOTES, _CHARSET);
            if ($pre) {
                return '<div class="' . $cls . '"><pre><code>' . $inner . '</code></pre></div>';
            }
            return '<div class="' . $cls . '"><code>' . $inner . '</code></div>';
        };

        $text = preg_replace_callback('/\[code_php](.*)\[\/code_php]/sU', function ($m) use ($wrap) {
            return $wrap($m[1], 'icmsCodePhp', false);
        }, $text);
        $text = preg_replace_callback('/\[code_js](.*)\[\/code_js]/sU', function ($m) use ($wrap) {
            return $wrap($m[1], 'icmsCodeJs', false);
        }, $text);
        $text = preg_replace_callback('/\[code_css](.*)\[\/code_css]/sU', function ($m) use ($wrap) {
            return $wrap($m[1], 'icmsCodeCss', false);
        }, $text);
        $text = preg_replace_callback('/\[code_html](.*)\[\/code_html]/sU', function ($m) use ($wrap) {
            return $wrap($m[1], 'icmsCodeHtml', true);
        }, $text);

        return $text;
    }
}

