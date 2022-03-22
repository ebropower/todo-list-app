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

class NotesController extends Controller
{
    public function index()
    {
        return NoteResource::collection(ListNotesAction::run(auth()->user()));
    }

    public function store(CreateNoteRequest $request)
    {
        $note = CreateNoteAction::run(auth()->user(), $request->validated());

        return (new NoteResource($note))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Note $note)
    {
        $note->load('categories');

        return new NoteResource(GetNoteAction::run($note));
    }

    public function update(UpdateNoteRequest $request, Note $note)
    {
        $this->authorize('update', $note);

        $note->load('categories');

        UpdateNoteAction::run($note, $request->validated());

        return new NoteResource($note->refresh());
    }

    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);

        DeleteNoteAction::run($note, auth()->user());

        return response()->json([
            'message' => 'Successfully deleted the note'
        ]);
    }
}
