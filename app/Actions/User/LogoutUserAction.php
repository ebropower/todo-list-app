<?php

namespace App\Actions\User;

use Lorisleiva\Actions\Concerns\AsAction;

class LogoutUserAction
{
    use AsAction;

    public function handle($user)
    {
        $user->tokens()->delete();
    }
}
