<?php

namespace App\Actions\Note;

use App\Http\Resources\NoteResource;
use App\Models\Note;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateNoteAction
{
    use AsAction;

    public function handle($user, $data)
    {
       return Note::create([
            'user_id' => $user->id,
            'details' => $data['details'],
            'completed_at' => $data['completed'] === 1 ? now() : null
        ]);
    }
}
