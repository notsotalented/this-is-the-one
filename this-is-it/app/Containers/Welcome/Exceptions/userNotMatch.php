<?php

namespace App\Containers\Welcome\Exceptions;

use App\Ship\Parents\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response;

class userNotMatch extends Exception
{
    public $httpStatusCode = Response::HTTP_BAD_REQUEST;

    public $message = 'Input credential does not match the current user!';

    public $code = 0;
}
