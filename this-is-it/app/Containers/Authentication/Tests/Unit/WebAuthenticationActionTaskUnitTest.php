<?php

namespace App\Containers\Authentication\Tests\Unit;

use App\Containers\Authentication\Actions\WebLoginAction;
use App\Containers\Authentication\Exceptions\LoginFailedException;
use App\Containers\Authentication\Tasks\CheckIfUserIsConfirmedTask;
use App\Containers\Authentication\Tests\TestCase;
use App\Containers\User\Models\User;
use App\Ship\Transporters\DataTransporter;

/**
 * Class WebAuthenticationActionTaskUnitTest.
 *
 * @group authentication
 * @group unit
 */
class WebAuthenticationActionTaskUnitTest extends TestCase
{
    public function testCheckIfUserIsConfirmedTask()
    {
        $this->expectException(LoginFailedException::class);

        //Assert no logged user
        $this->assertFalse(\Auth::check());

        factory(User::class)->create([
            'email' => 'user@user.com',
            'password' => bcrypt('admin'),
        ]);

        $action = \App::make(WebLoginAction::class);
        $response = $action->run(new DataTransporter([
            'email' => 'user@user.com',
            'password' => 'admin'
        ]));

    }
}
