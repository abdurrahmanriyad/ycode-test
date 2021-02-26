<?php


namespace App\Exceptions;


use Exception;

class InvalidFile extends Exception
{
    protected $message = 'The given file is invalid';
}
