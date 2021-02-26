<?php


namespace App\Exceptions;


use Exception;

class FailedToCreateTableData extends Exception
{
    protected $message = 'Failed to create table data. Please try again later.';
}
