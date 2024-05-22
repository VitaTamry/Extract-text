<?php

namespace Vita\FileExtract;

class DocsExtract
{
    private $filePath;
    private $fileExtension;
    private $supportedFileTypes = array('doc', 'docx', 'xlsx', 'pptx', 'txt');
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->fileExtension = pathinfo($this->filePath, PATHINFO_EXTENSION);
    }

    public function extract(){
        if (in_array($this->fileExtension, $this->supportedFileTypes)) {
            if ($this->fileExtension == "doc") {
                return $this->doc();
            } elseif ($this->fileExtension == "docx") {
                return $this->docx();
            } elseif ($this->fileExtension == "xlsx") {
                return $this->xlsx();
            } elseif ($this->fileExtension == "pptx") {
                return $this->pptx();
            }elseif ($this->fileExtension == "txt") {
                return file_get_contents($this->filePath);
            } else {
                return "Invalid File Type";
            }
        } else {
            return "Invalid File Type";
        }
    }
    private function doc()
    {
        $fileHandle = fopen($this->filePath, "r");
        $line = @fread($fileHandle, filesize($this->filePath));
        $lines = explode(chr(0x0D), $line);
        $outtext = "";
        foreach ($lines as $thisline) {
            $pos = strpos($thisline, chr(0x00));
            if (($pos !== FALSE) || (strlen($thisline) == 0)) {
            } else {
                $outtext .= $thisline . " ";
            }
        }
        $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/", "", $outtext);
        return $outtext;
    }

    private function docx()
    {

        $striped_content = '';
        $content = '';

        $zip = new \ZipArchive();
        if ($zip->open($this->filePath) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entryName = $zip->getNameIndex($i);
                if ($entryName === "word/document.xml") {
                    $content .= $zip->getFromIndex($i);
                }
            }
            $zip->close();
        }

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);

        return $striped_content;
    }

    /************************excel sheet************************************/

    function xlsx()
    {
        $xml_filePath = "xl/sharedStrings.xml"; //content file name
        $zip_handle = new \ZipArchive;
        $output_text = "";
        if (true === $zip_handle->open($this->filePath)) {
            if (($xml_index = $zip_handle->locateName($xml_filePath)) !== false) {
                $xml_datas = $zip_handle->getFromIndex($xml_index);
                $doc = new \DOMDocument();
                $xml_handle = $doc->loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                if ($xml_handle !== FALSE)
                    $output_text = strip_tags($doc->saveXML());
            } else {
                $output_text .= "";
            }
            $zip_handle->close();
        } else {
            $output_text .= "";
        }
        return $output_text;
    }

    /*************************power point files*****************************/
    function pptx()
    {
        $zip_handle = new \ZipArchive;
        $output_text = "";
        if (true === $zip_handle->open($this->filePath)) {
            $slide_number = 1; //loop through slide files
            while (($xml_index = $zip_handle->locateName("ppt/slides/slide" . $slide_number . ".xml")) !== false) {
                $xml_datas = $zip_handle->getFromIndex($xml_index);
                $doc = new \DOMDocument();
                $xml_handle = $doc->loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                if ($xml_handle !== FALSE)
                    continue;
                $output_text .= strip_tags($doc->saveXML());
                $slide_number++;
            }
            if ($slide_number == 1) {
                $output_text .= "";
            }
            $zip_handle->close();
        } else {
            $output_text .= "";
        }
        return $output_text;
    }
}
