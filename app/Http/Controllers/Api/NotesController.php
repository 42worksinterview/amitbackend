<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Note;

class NotesController extends Controller
{
    public function index() {
        return response()->json(Note::all(), 200);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);

        $note = Note::create($validated);
        return response()->json($note, 201);
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $note = Note::findOrFail($id);
        $note->update($validated);

        return response()->json($note, 200);
    }

    public function destroy($id) {
        Note::findOrFail($id)->delete();
        return response()->json(['message' => 'Note deleted'], 200);
    }

    // Bonus – mock AI summarizer
    public function summarize($id) {
        $note = Note::findOrFail($id);

        $summary = Str::words($note->description, 15, '...'); // mock “AI”
        // If using a real AI API: call OpenAI/GPT/etc here.

        return response()->json([
            'id' => $id,
            'summary' => $summary
        ], 200);
    }
}
