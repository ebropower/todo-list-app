<?php


namespace App\Http\Controllers;


use App\Http\Resources\NoteResource;
use App\Models\User;

class UserNoteController extends Controller
{
    public function __invoke(User $user)
    {
        return NoteResource::collection($user->notes);
    }
}
