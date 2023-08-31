<?php

namespace App\Containers\Welcome\Tasks;

use App\Ship\Parents\Tasks\Task;

class checkPasswordSyntaxTask extends Task
{
    public function run(string $password)
    {
        //TRUE = not validated | FALSE = validated
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);
        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8 || strlen($password) > 20) {
            //Failed to validated
            
            return true;
        }

        return false;
    }
}
