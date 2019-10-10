<?php
namespace Salesforce\Job;

Interface BulkImportInterface
{
    public function getCsvData(): array;
    public function setCsvData(array $csvData);
}