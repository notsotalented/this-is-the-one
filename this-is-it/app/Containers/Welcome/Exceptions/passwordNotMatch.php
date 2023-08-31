<?php

namespace App\Containers\Welcome\Exceptions;

use App\Ship\Parents\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response;

class passwordNotMatch extends Exception
{
    public $httpStatusCode = Response::HTTP_BAD_REQUEST;

    public $message = 'Password Not Match!';

    public $code = 0;
}
