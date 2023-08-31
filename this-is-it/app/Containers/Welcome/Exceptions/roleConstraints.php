<?php

namespace App\Containers\Welcome\Exceptions;

use App\Ship\Parents\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response;

class roleConstraints extends Exception
{
    public $httpStatusCode = Response::HTTP_BAD_REQUEST;

    public $message = 'Role should be an integer, 0 for Admin, 1 for Leader, 2 for Staff!';

    public $code = 0;
}
