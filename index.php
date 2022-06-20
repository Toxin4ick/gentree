<?php
require __DIR__ . '/vendor/autoload.php';

use App\Tree;
use App\CSV;

$input_path = readline('Введите путь к входящему файлу в формате CSV файла: ');
$output_path = readline('Введите путь для выгружаемого файла: ');
$tree = New Tree;
$tree->createJson(CSV::openCSV($input_path), $output_path);
echo 'Всё прошло успешно';
