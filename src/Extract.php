<?php

namespace Vita\FileExtract;

use Spatie\PdfToText\Pdf;
use Vita\FileExtract\DocsExtract;

class Extract
{
    static public function extract($filename)
    {

        if (isset($filename) && !is_readable($filename)) {
            return "File Not Found";
        }

        $file_ext  = pathinfo($filename, PATHINFO_EXTENSION);
        if ($file_ext == "pdf") {
            return  (new Pdf('C:/Program Files/Git/mingw64/bin/pdftotext'))
            ->setPdf($filename)
            ->text();
        } else {
            $docExtract = new DocsExtract($filename);
            $extractedText = $docExtract->extract();
            return $extractedText;
        }
    }
}
