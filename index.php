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
function child($name, &$data)
{
    $i = 0;
    foreach ($data as $key=>$row) {
        if ($row['parent'] == $name) {
            $result[$i]['ItemName'] = $row['name'];
            $result[$i]['parent'] = $row['parent'];
            if (empty($row['relation'])) {
                $result[$i]['children'] = child($row['name'], $data);
                unset($data[$key]);
            } else {
                $result[$i]['children'] = relation($row['relation'], $data, $row['name']);
            }
            $i++;
        }
    }
    return $result;
}

$result = title($data);
die(json_encode($result, JSON_UNESCAPED_UNICODE));