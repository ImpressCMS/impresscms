<?php

namespace Icms\Tests\Support;

class ProviderFactory
{
    /**
     * @param string $prefix
     * @return iterable<string>
     */
    public static function forAliasPrefix(string $prefix): iterable
    {
        $files = new \DirectoryIterator(\ICMS_ROOT_PATH . 'libraries/icms/');
        foreach ($files as $file) {
            if ($file->isDir()) {
                continue;
            }
            $phpFile = $file->getPathname();
            $content = \file_get_contents($phpFile);
            if ($content === false) {
                continue;
            }
            
            // Handle Icms\Db\* files specially
            if (\strpos($phpFile, '/icms/Db/') !== false) {
                // Extract class name using a simpler regex
                if (\preg_match('/class\s+(\S+)/', $content, $matches)) {
                    $className = \trim($matches[1]);
                    if (\strpos(\str_replace('\\', '', $className), 'Db') !== false) {
                        // Generate legacy alias
                        $legacyAlias = 'icms_db_' . \ucfirst(\substr(\str_replace('\\', ' ', $className), 0, \strpos(\str_replace('\\', ' ', $className), ' ')));
                        yield $legacyAlias => $className;
                    }
                }
            }
            
            // Handle other namespaces
            $contentCleaned = \preg_replace('/\s+/', '', $content);
            if (\strpos($contentCleaned, \preg_quote($prefix, '/') . 'class ') !== false) {
                $match = \preg_match('/class\s+(' . \preg_quote($prefix, '/') . '\S*)/', $content, $m);
                if ($match) {
                    yield $m[1] => 'icms_' . $m[1];
                }
            }
        }
    }
}
