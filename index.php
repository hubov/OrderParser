<?php

require  "vendor/autoload.php";

use OrderParser\OrderParser;
use OrderParser\CSVExport;

$parser = new OrderParser();
if (isset($_GET['file'])) {
    $file = $_GET['file'];
} else {
    $file = 'wo_for_parse.html';
}

$parser->source($file)->parse();

echo '<pre>';
print_r($parser->return());
echo '</pre>';

$export = new CSVExport();
$export->setHeader(array_keys($parser->return()))
        ->setData($parser->return())
        ->save();