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
function relation($relation, &$data, $parent)
{
    $container = getContainer($relation, $parent);
    if (empty($container)) {
        $result = [];
        $i = 0;
        foreach ($data as $key=>$row) {
            if ($row['parent'] == $relation) {
                $result[$i]['ItemName'] = $row['name'];
                $result[$i]['parent'] = $relation;
                $result[$i]['children'] = child($row['name'], $data);
                $i++;
            }
        }
        write_file($result, $relation);
        if (!empty($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['parent'] = $parent;
            }
        }
        return $result;
    }
    return $container;
}

function getContainer($relation, $parent)
{
    $container = @file_get_contents('containers/' . $relation . '.json');
    $container = json_decode($container, true);
    if (!empty($container)) {
        for ($i = 0; $i < count($container); $i++) {
            $container[$i]['parent'] = $parent;
        }
    }
    return $container;
}
function write_file($container, $relation)
{
    $file = 'containers/' . $relation . '.json';
    file_put_contents($file, json_encode($container, JSON_UNESCAPED_UNICODE));
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
function myscandir($dir)
{
    $list = scandir($dir);
    unset($list[0], $list[1]);
    return array_values($list);
}

// функция очищения папки
function clear_dir($dir)
{
    $list = myscandir($dir);

    foreach ($list as $file) {
        if (is_dir($dir . $file)) {
            clear_dir($dir . $file . '/');
            rmdir($dir . $file);
        } else {
            unlink($dir . $file);
        }
    }
}
$result = title($data);
clear_dir('containers/');
die(json_encode($result, JSON_UNESCAPED_UNICODE));