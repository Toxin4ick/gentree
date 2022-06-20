<?php
$fh = fopen('input.csv', "r");
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