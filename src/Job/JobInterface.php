<?php
namespace Salesforce\Job;

Interface JobInterface
{
    public function validate(): bool;
}
