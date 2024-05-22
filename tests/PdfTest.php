<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Vita\FileExtract\Extract;

class PdfTest extends TestCase
{
    private $testCases = [
        __DIR__."/files/drylab.pdf" => "Sales: Return customer rate is now 80%",
    ];
    public function testPdf()
    {
        $extract = new Extract();
        foreach ($this->testCases as $file => $expected) {
            $this->assertStringContainsString ($expected, $extract->extract($file));
        }
    }

}