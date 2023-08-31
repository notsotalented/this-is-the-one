<?php

namespace App\Containers\Welcome\Tests\Unit;

use App\Containers\User\Models\User;
use App\Containers\Welcome\Tests\TestCase;
use App\Containers\Welcome\Tasks\displayUsers;

/**
 * Class testAllCustomFx.
 *
 * @group welcome
 * @group unit
 */
class allCustomFxTest extends TestCase
{

    /**
     * Test the checkPasswordSyntax function.
     */
    public function testActionCheckPasswordSyntax()
    {
        $password_input = [
            'noUpper' => 'asasasas',
            'noLower' => 'ASASASAS',
            'noNumber' => 'asasASAS',
            'noSpecial' => 'asasASAS1234',
            'lessThan8' => 'aA1@',
            'moreThan20' => 'aA1@123421321313323212321332313',
            'valid' => 'Admin@1234',
        ];

        foreach ($password_input as $key => $value) {
            $check = $this->app->make('App\Containers\Welcome\Tasks\checkPasswordSyntaxTask')->run($value);
            
            if($key != 'valid') {
                $this->assertTrue($check);
            }
            else {
                $this->assertFalse($check);
            }
        }
    }

    /**
     * Test the displayUsers function.
     *
     * @return void
     */
    public function test_display_Users()
    {
        $user_check = User::find(1);
        $check = $this->app->make(displayUsers::class)->run();

        $this->assertJson($check);

        //dd($check->toArray());

        $this->assertJsonStringEqualsJsonString($user_check, $check->first());
    }
}
