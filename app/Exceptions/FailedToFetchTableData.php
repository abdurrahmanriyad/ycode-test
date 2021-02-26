<?php


namespace App\Exceptions;


use Exception;

class FailedToFetchTableData extends Exception
{
    protected $message = 'Failed to fetch table data. Please try again later.';
}
