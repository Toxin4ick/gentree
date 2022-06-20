<?php

namespace App;

class Dir
{
    static function scanDir($dir)
    {
        $list = scandir($dir);
        unset($list[0], $list[1]);
        return array_values($list);
    }

    // функция очищения папки
    public static function clearDir($dir)
    {
        $list = self::scanDir($dir);

        foreach ($list as $file) {
            if (is_dir($dir . $file)) {
                self::clearDir($dir . $file . '/');
                rmdir($dir . $file);
            } else {
                unlink($dir . $file);
            }
        }
    }
    public static function writeFile($container, $relation)
    {
        $file = 'containers/' . $relation . '.json';
        file_put_contents($file, json_encode($container, JSON_UNESCAPED_UNICODE));
    }
}
