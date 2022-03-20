<?php

namespace App\Http\Controllers;

use App\Actions\Note\CreateNoteAction;
use App\Actions\Note\DeleteNoteAction;
use App\Actions\Note\GetNoteAction;
use App\Actions\Note\ListNotesAction;
use App\Actions\Note\UpdateNoteAction;
use App\Http\Requests\CreateNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{
    public function index()
    {
        return NoteResource::collection(ListNotesAction::run(auth()->user()));
    }

    public function store(CreateNoteRequest $request)
    {
        $data = $request->validated();

        $note = CreateNoteAction::run(auth()->user(), $data);

        return (new NoteResource($note))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Note $note)
    {
        return new NoteResource(GetNoteAction::run($note));
    }

    public function update(UpdateNoteRequest $request, Note $note)
    {
        $this->authorize('update', $note);

        $data = $request->validated();

        UpdateNoteAction::run($note, $data);

        return new NoteResource($note->refresh());
    }

    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);

        DeleteNoteAction::run($note);

        return response()->json([
            'message' => 'Successfully deleted the note'
        ]);
    }
}
