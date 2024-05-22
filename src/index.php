<?php
namespace Vita\FileExtract;
require_once __DIR__ . '/../vendor/autoload.php';
use Vita\FileExtract\Extract;

 $testCases = [
    "C:/apps/text-extractor/tests/files/drylab.pdf" => "Sales: Return customer rate is now 80%,
    proving value and willingness to pay. ",
];

$extract = new Extract();
foreach ($testCases as $file => $expected) {
    $text = $extract->extract($file);
    echo $text;
}
