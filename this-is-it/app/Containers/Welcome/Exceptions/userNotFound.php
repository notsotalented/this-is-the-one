<?php

namespace App\Containers\Welcome\Exceptions;

use App\Ship\Parents\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response;

class userNotFound extends Exception
{
    public $httpStatusCode = Response::HTTP_BAD_REQUEST;

    public $message = 'User Not Found!';

    public $code = 0;
}
