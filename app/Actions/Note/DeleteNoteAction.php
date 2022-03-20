<?php

namespace App\Actions\Note;

use Lorisleiva\Actions\Concerns\AsAction;

class DeleteNoteAction
{
    use AsAction;

    public function handle($note)
    {
        $note->delete();
    }
}
