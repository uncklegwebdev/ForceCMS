<?php

class File
{
    /**
     * Get sub-directori(es) of a directory, not including the directory which
     * its name is one of ".", ".." or ".svn"
     *
     * @param string $dir Path to the directory
     * @return array
     */
    public static function getSubDir($dir)
    {
        if (!file_exists($dir)) {
            return [];
        }

        $subDirs = [];
        $dirIterator = new DirectoryIterator($dir);
        foreach ($dirIterator as $dir) {
            if ($dir->isDot() || !$dir->isDir()) {
                continue;
            }
            $dir = $dir->getFilename();
            if ($dir == '.svn') {
                continue;
            }
            $subDirs[] = $dir;
        }
        return $subDirs;
    }

    /**
     * @param string $dir
     * @return boolean
     */
    public static function deleteRescursiveDir($dir)
    {
        if (is_dir($dir)) {
            $dir = (substr($dir, -1) != DS) ? $dir.DS : $dir;
            $openDir = opendir($dir);
            while ($file = readdir($openDir)) {
                if (!in_array($file, [".", ".."])) {
                    if (!is_dir($dir.$file)) {
                        @unlink($dir.$file);
                    } else {
                        self::deleteRescursiveDir($dir.$file);
                    }
                }
            }
            closedir($openDir);
            @rmdir($dir);
        }

        return true;
    }

    /**
     * @param string $source
     * @param string $dest
     * @return boolean
     */
    public static function copyRescursiveDir($source, $dest)
    {
        $openDir = opendir($source);
        if (!file_exists($dest)) {
            @mkdir($dest);
        }
        while ($file = readdir($openDir)) {
            if (!in_array($file, [".", ".."])) {
                if (is_dir($source.DS.$file)) {
                    self::copyRescursiveDir($source.DS.$file, $dest.DS.$file);
                } else {
                    copy($source.DS.$file, $dest.DS.$file);
                }
            }
        }
        closedir($openDir);

        return true;
    }

    /**
     * Create sub-directories of given directory
     *
     * @param string $root Path to root directory
     * @param string $path Relative path to new created directory in format a/b/c (on Linux)
     * or a\b\c (on Windows)
     */
    public static function createDirs($root, $path)
    {
        $root = rtrim($root, DS);
        $subDirs = explode(DS, $path);
        if ($subDirs == null) {
            return;
        }
        $currDir = $root;
        foreach ($subDirs as $dir) {
            $currDir = $currDir.DS.$dir;
            if (!file_exists($currDir)) {
                mkdir($currDir);
            }
        }
    }
}