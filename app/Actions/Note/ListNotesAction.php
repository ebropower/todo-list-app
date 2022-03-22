<?php

namespace App\Actions\Note;

use App\Http\Resources\NoteResource;
use Lorisleiva\Actions\Concerns\AsAction;

class ListNotesAction
{
    use AsAction;

    public function handle($user)
    {
        // for consistency- can add other things later if logic changes
        return $user->notes()->cursorPaginate();
    }
}
