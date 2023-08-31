<?php

namespace App\Containers\Welcome\Exceptions;

use App\Ship\Parents\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response;

class passwordConstraints extends Exception
{
    public $httpStatusCode = Response::HTTP_BAD_REQUEST;

    public $message = 'Password should be at least [8-20] characters in length and should include at least one upper case letter, and one number.';

    public $code = 0;
}
