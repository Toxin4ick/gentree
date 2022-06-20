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

function title(&$data)
{
    foreach ($data as $row) {
        if ($row['parent'] == null) {
            $result = [
                'ItemName' => $row['name'],
                'parent' => null,
                'children' => child($row['name'], $data),
            ];
            $final_result[] = $result;
        }
    }
    return $final_result;
}