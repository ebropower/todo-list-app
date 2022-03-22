<?php

namespace App\Actions\Note;

use App\Models\User;
use App\Notifications\AdminDeletedNoteNotification;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteNoteAction
{
    use AsAction;

    public function handle($note, $currentUser)
    {
        $note->delete();

        if ($currentUser->hasRole('admin') && ($currentUser->id !== $note->user_id)) {
            $note->user->notify(new AdminDeletedNoteNotification($note));
        }
    }
}
