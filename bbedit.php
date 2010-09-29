<?php
/**
 * jQuery bbcode editor plugin
 *
 * Copyright (C) 2010 Joe Dotoff
 *
 * @link http://www.w3theme.com/jquery-bbedit/
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

define('BBEDIT_TYPE_BOTH', 0);
define('BBEDIT_TYPE_TAG', 1);
define('BBEDIT_TYPE_SMILEY', 2);

/**
 * Convert bbcode to HTML
 *
 * @param string $text  The text to convert.
 * @param int $type  The type of bbcode.
 * @param string $smileyUrl  The URL path of smiley icons.
 * @return string
 */
function bbedit($text, $type = BBEDIT_TYPE_BOTH, $smileyUrl = 'smiley')
{
    $search = array(
        '/\[b\](.+?)\[\/b\]/is',
        '/\[i\](.+?)\[\/i\]/is',
        '/\[u\](.+?)\[\/u\]/is',
        '/\[s\](.+?)\[\/s\]/is',
        '/\[code\](.+?)\[\/code\]/is',
        '/\[quote\](.+?)\[\/quote\]/is',
        '/\[url=([^\[]*)\](.+?)\[\/url\]/ise',
        '/\[url\](.+?)\[\/url\]/ise',
        //'/\[img\](.+?)\[\/img\]/ise',
    );
    $replace = array(
        '<strong>$1</strong>',
        '<em>$1</em>',
        '<span class="underline">$1</span>',
        '<span class="line-through">$1</span>',
        '<code>$1</code>',
        '<blockquote>$1</blockquote>',
        "'<a href=\"'. bbedit_escape('$1') .'\">$2</a>'",
        "'<a href=\"'. bbedit_strip_escape('$1') .'\">$1</a>'",
        //"'<img src=\"'. bbedit_escape('$1') .'\" alt=\"'. basename('$1') .'\" />'",
    );
    $smilies = array('biggrin', 'cry', 'dizzy', 'funk', 'huffy', 'lol', 'loveliness', 'mad', 'sad', 'shocked', 'shy', 'sleepy', 'smile', 'sweat', 'titter', 'tongue');
    if ($type != BBEDIT_TYPE_SMILEY) {
        $text = preg_replace($search, $replace, $text);
    }
    if ($type != BBEDIT_TYPE_TAG) {
        $search2 = $replace2 = array();
        for ($i = 0, $len = count($smilies); $i < $len; $i++) {
            $search2[$i] = sprintf('[:Q%s]', $smilies[$i]);
            $replace2[$i] = sprintf('<img src="%s/%s.gif" alt="%s" />', $smileyUrl, $smilies[$i], $smilies[$i]);
        }
        $text = str_replace($search2, $replace2, $text);
    }
    $text = preg_replace('/\[[^\]]*\]/', '', $text);

    return $text;
}

/**
 * Strip HTML and bbcode tags
 *
 * @param string $text  The string to strip.
 * @return string
 */
function bbedit_strip_tags($text)
{
    return preg_replace('/\[[^\]]*\]/', '', strip_tags($text));
}

/**
 * Escape special characters
 *
 * @param string $text  The string to escape.
 * @return string
 */
function bbedit_escape($text)
{
    return htmlspecialchars(trim($text));
}

/**
 * Strip HTML and bbcode tags, and escape special characters
 *
 * @param string $text  The string to strip and escape.
 * @return string
 */
function bbedit_strip_escape($text)
{
    return bbedit_escape(bbedit_strip_tags($text));
}
?>