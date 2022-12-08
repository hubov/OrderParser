<?php

require  "vendor/autoload.php";

use OrderParser\OrderParser;
use OrderParser\CSVExport;

$parser = new OrderParser();
$parser->source('wo_for_parse.html')->parse();

echo '<pre>';
print_r($parser->return());
echo '</pre>';

$export = new CSVExport();
$export->setHeader(array_keys($parser->return()));
$export->setData($parser->return());
$export->save();