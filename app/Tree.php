<?php

namespace App;
use App\Dir;
class Tree
{
    public function createJson(&$data, $path) : array
    {
        foreach ($data as $row) {
            if ($row['parent'] == null) {
                $itemname_first = [
                    'ItemName' => $row['name'],
                    'parent' => null,
                    'children' => $this->getChildren($row['name'], $data),
                ];
                $result[] = $itemname_first;
            }
        }
        Dir::clearDir('containers/');
        if(pathinfo($path)['extension'] == null)
        {
            $path .= '.json';
        }
        if(pathinfo($path)['extension'] != 'json')
        {
            $path = preg_replace("/\..*/",'.json',$path);
        }
        file_put_contents($path, json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return $result;
    }

    public function getChildren($name, &$data)
    {
        $i = 0;
        foreach ($data as $key => $row) {
            if ($row['parent'] == $name) {
                $result[$i]['ItemName'] = $row['name'];
                $result[$i]['parent'] = $row['parent'];
                if (empty($row['relation'])) {
                    $result[$i]['children'] = $this->getChildren($row['name'], $data);
                    if(empty($result[$i]['children']))
                    {
                        $result[$i]['children'] == null;
                    }
                    unset($data[$key]);
                } else {
                    $result[$i]['children'] = $this->getRelationGroup($row['relation'], $data, $row['name']);
                }
                $i++;
            }
        }
        return $result;
    }

    public function getRelationGroup($relation, &$data, $parent)
    {
        $container = $this->getContainer($relation, $parent);
        if (empty($container)) {
            $result = [];
            $i = 0;
            foreach ($data as $key => $row) {
                if ($row['parent'] == $relation) {
                    $result[$i]['ItemName'] = $row['name'];
                    $result[$i]['parent'] = $relation;
                    $result[$i]['children'] = $this->getChildren($row['name'], $data);
                    $i++;
                }
            }
            Dir::writeFile($result, $relation);
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
}
