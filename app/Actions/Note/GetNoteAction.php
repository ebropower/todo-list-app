<?php

namespace App\Actions\Note;

use App\Http\Resources\NoteResource;
use Lorisleiva\Actions\Concerns\AsAction;

class GetNoteAction
{
    use AsAction;

    public function handle($note)
    {
        // for consistency- can add other things later if logic changes
        return $note;
    }
}
