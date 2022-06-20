<?php

namespace App;

class CSV
{
    public static function openCSV($path)
    {
        if(pathinfo($path)['extension'] == null)
        {
            $path .= '.csv';
        }
        $fh = @fopen($path, "r");
        if($fh == false)
        {
            die('Не правильный путь до файла');
        }
        fgetcsv($fh, 0, ',');

        // массив, в который данные будут сохраняться
        $data = [];
        while (($row = fgetcsv($fh, 0, ';')) !== false) {
            list($name, $type, $parent, $relation) = $row;

            $data[] = [
                'name' => $name,
                'type' => $type,
                'parent' => $parent,
                'relation' => $relation,

            ];
        }
        return $data;
    }
}
