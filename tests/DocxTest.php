<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Vita\FileExtract\Extract;

class DocxTest extends TestCase
{
    private $testCases = [
        __DIR__."/files/sample.txt" => "With over 7 years of hands-on experience",
        __DIR__."/files/sample.docx" => "With over 7 years of hands-on experience",
        __DIR__."/files/sample.doc" => "With over 7 years of hands-on experience",

    ];
    public function testPdf()
    {
        $extract = new Extract();
        foreach ($this->testCases as $file => $expected) {
            $this->assertStringContainsString ($expected, $extract->extract($file));
        }
    }

}