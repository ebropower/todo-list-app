<?php

namespace App\Actions\Note;

use App\Http\Resources\NoteResource;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateNoteAction
{
    use AsAction;

    public function handle($note, $data)
    {
        $note->update([
            'details' => $data['details'],
            'completed_at' => $data['completed'] === 1 ? now() : null
        ]);
    }
}
