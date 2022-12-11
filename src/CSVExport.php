<?php

namespace OrderParser;

class CSVExport
{
    protected $header;
    protected $data;
    protected $file;

    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    protected function checkHeader()
    {
        if(!fgetcsv($this->file)) {
            return true;
        }

        return false;
    }

    protected function addHeader()
    {
        if (($this->header !== NULL) && ($this->checkHeader())) {
            fputcsv($this->file, $this->header);
        }
    }

    public function save()
    {
        $this->file = fopen('storage/results.csv', 'r+');

        $this->addHeader();
        fputcsv($this->file, $this->data);

        fclose($this->file);
    }
}