<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        if (Auth::id() !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        // to get user's note $user->notes 
        return response()->json($user->notes);
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $note = new Note();
        $note->title = $request->title;
        $note->content = $request->content;
        $note->user_id = Auth::id();
        $note->save();

        return response()->json($note);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, Note $note)
    {
        if (Auth::id() !== $note->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($note);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user, Note $note)
    {
        if (Auth::id() !== $note->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $note->title = $request->title;
        $note->content = $request->content;
        $note->save();

        return response()->json($note);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Note $note)
    {
        if (Auth::id() !== $note->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $note->delete();

        return response()->json($note);
    }
}
