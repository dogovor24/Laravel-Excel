<?php

namespace Maatwebsite\Excel\Jobs;

use Maatwebsite\Excel\Reader;
use Illuminate\Bus\Queueable;
use Maatwebsite\Excel\Concerns\WithEvents;

class AfterImportJob
{
    use Queueable;

    /**
     * @var WithEvents
     */
    private $import;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param  object  $import
     * @param  Reader  $reader
     */
    public function __construct($import, Reader $reader)
    {
        $this->import = $import;
        $this->reader = $reader;
    }

    public function handle()
    {
        if ($this->import instanceof WithEvents) {
            if (null === $this->reader->getDelegate()) {
                $this->reader->readSpreadsheet();
            }

            $this->reader->registerListeners($this->import->registerEvents());
        }

        $this->reader->afterImport($this->import);
    }
}