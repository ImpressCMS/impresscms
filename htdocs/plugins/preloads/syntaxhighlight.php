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
     * After filtering HTML content
     *
     * @param array{0:string} $array [$html, 1, $br]
     * @return void
     */
    public function eventAfterFilterHTMLdisplay(array &$array): void
    {
		$array[0] = self::applyGeshi($array[0]);
    }

    /**
     * After filtering textarea content (non-HTML flows)
     *
     * @param array{0:string} $array [$text, $smiley, $icode, $image, $br]
     * @return void
     */
    public function eventAfterFilterTextareaDisplay(array $array): void
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
            return self::applyFallback($text);
        }

        // Replace each supported language tag deterministically (no regex backtracking)
        $text = self::replaceTag($text, 'code_php', function (string $raw): string {
            return self::highlight($raw, 'php', 'icmsCodePhp', false);
        });
        $text = self::replaceTag($text, 'code_js', function (string $raw): string {
            return self::highlight($raw, 'javascript', 'icmsCodeJs', false);
        });
        $text = self::replaceTag($text, 'code_css', function (string $raw): string {
            return self::highlight($raw, 'css', 'icmsCodeCss', false);
        });
        $text = self::replaceTag($text, 'code_html', function (string $raw): string {
            // historically wrapped in <div><pre><code>
            return self::highlight($raw, 'html4strict', 'icmsCodeHtml', true);
        });

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

        // Guard against extremely large blocks (200 KB): use safe non-GeSHi wrapper
        if (strlen($source) > 200000) {
            return self::wrapPlainBlock($raw, $wrapperClass, $wrapPre);
        }

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
     * Deterministically replace [tag]...[/tag] occurrences without regex backtracking.
     *
     * @param string $text Input text
     * @param string $tag BBCode tag name (e.g., 'code_php')
     * @param callable(string):string $callback Receives raw inner content and returns replacement
     * @return string
     */
    private static function replaceTag(string $text, string $tag, callable $callback): string
    {
        $open = '[' . $tag . ']';
        $close = '[/' . $tag . ']';
        $pos = 0;

        while (($start = strpos($text, $open, $pos)) !== false) {
            $innerStart = $start + strlen($open);
            $end = strpos($text, $close, $innerStart);
            if ($end === false) {
                break;
            }

            $inner = substr($text, $innerStart, $end - $innerStart);
            $replacement = $callback($inner);

            $text = substr($text, 0, $start)
                . $replacement
                . substr($text, $end + strlen($close));
            $pos = $start + strlen($replacement);
        }

        return $text;
    }

    /**
     * Wrap plain code safely when GeSHi is unavailable or for oversized blocks.
     */
    private static function wrapPlainBlock(string $raw, string $cls, bool $pre): string
    {
        $src = icms_core_DataFilter::undoHtmlSpecialChars($raw);
        $inner = htmlspecialchars($src, ENT_QUOTES, _CHARSET);
        if ($pre) {
            return '<div class="' . $cls . '"><pre><code>' . $inner . '</code></pre></div>';
        }

        return '<div class="' . $cls . '"><code>' . $inner . '</code></div>';
    }


    /**
     * Fallback when GeSHi is not available.
     *
     * @param string $text
     * @return string
     */
    private static function applyFallback(string $text): string
    {
        // Deterministic fallback processing (no regex backtracking)
        $text = self::replaceTag($text, 'code_php', function (string $raw): string {
            return self::wrapPlainBlock($raw, 'icmsCodePhp', false);
        });
        $text = self::replaceTag($text, 'code_js', function (string $raw): string {
            return self::wrapPlainBlock($raw, 'icmsCodeJs', false);
        });
        $text = self::replaceTag($text, 'code_css', function (string $raw): string {
            return self::wrapPlainBlock($raw, 'icmsCodeCss', false);
        });
        $text = self::replaceTag($text, 'code_html', function (string $raw): string {
            return self::wrapPlainBlock($raw, 'icmsCodeHtml', true);
        });

        return $text;
    }
}

