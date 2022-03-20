<?php

namespace App\Actions\User;

use Lorisleiva\Actions\Concerns\AsAction;

class LoginUserAction
{
    use AsAction;

    public function handle($user)
    {
        return $user->createToken('todo-token');
    }
}
