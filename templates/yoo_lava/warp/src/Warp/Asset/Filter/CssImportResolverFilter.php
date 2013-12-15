<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

namespace Warp\Asset\Filter;

/**
 * Stylesheet import resolver, replaces @imports with it's content.
 */
class CssImportResolverFilter implements FilterInterface
{
    /**
     * On load filter callback.
     *
     * @param object $asset
     */
    public function filterLoad($asset)
    {
        // is file asset?
        if (!is_a($asset, 'Warp\Asset\FileAsset')) {
            return;
        }

        // resolve @import rules
        $content = $this->load($asset->getPath(), $asset->getContent());

        $asset->setContent($content);
    }

    /**
     * On content filter callback.
     *
     * @param object $asset
     */
    public function filterContent($asset)
    {
        $content = $asset->getContent();

        // move unresolved @import rules to the top
        $regexp = '/@import[^;]+;/i';
        if (preg_match_all($regexp, $content, $matches)) {
            $content = preg_replace($regexp, '', $content);
            $content = implode("\n", $matches[0])."\n".$content;
        }

        $asset->setContent($content);
    }

    /**
     * Load file and get it's content.
     *
     * @param string $file
     * @param string $content
     *
     * @return string
     */
    protected function load($file, $content = '')
    {
        static $path;

        $oldpath = $path;

        if ($path && !strpos($file, '://')) {
            $file = realpath($path.'/'.$file);
        }

        $path = dirname($file);

        // get content from file, if not already set
        if (!$content && file_exists($file)) {
            $content = @file_get_contents($file);
        }

        // remove multiple charset declarations and resolve @imports to its actual content
        if ($content) {
            $content = preg_replace('#/\*.*?\*/#s', '', $content);
            $content = preg_replace('/^@charset\s+[\'"](\S*)\b[\'"];/i', '', $content);
            $content = preg_replace_callback('/@import\s*(?:url\(\s*)?[\'"]?(?![a-z]+:)([^\'"\()]+)[\'"]?\s*\)?\s*;/', array($this, '_load'), $content);
        }

        $path = $oldpath;

        return $content;
    }

    /**
     * Load file recursively and fix url paths.
     *
     * @param array $matches
     *
     * @return string
     */
    protected function _load($matches)
    {
        // resolve @import rules recursively
        $file = $this->load($matches[1]);

        // get file's directory remove '.' if its the current directory
        $directory = dirname($matches[1]);
        $directory = $directory == '.' ? '' : $directory . '/';

        // add directory file's to urls paths
        return preg_replace('/url\s*\(([\'"]?)(?![a-z]+:|\/+)/i', 'url(\1' . $directory, $file);
    }
}
