<?php
namespace Salesforce\Job;

Interface QueryInterface
{
    public function getQuery(): string;
    public function setQuery(string $query);
}