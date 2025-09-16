<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeEntryController extends Controller
{
    public function index(Project $project)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
    
        $query = $project->timeEntries()->with('user');
    
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }
    
        $entries = $query->orderBy('day', 'desc')->get();
    
        return response()->json($entries);
    }

    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'day' => 'required|date',
            'time_input' => 'required|string',
            'hours' => 'required|numeric',
            'note' => 'nullable|string',
        ]);

        $entry = $project->timeEntries()->create([
            'day' => $data['day'],
            'time_input' => $data['time_input'],
            'hours' => $data['hours'],
            'note' => $data['note'] ?? null,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($entry, 201);
    }

    public function destroy(TimeEntry $entry)
    {
        $entry->delete();

        return response()->json(['message' => 'Entry deleted'], 200);
    }

    public function update(Request $request, $id)
{
    $entry = TimeEntry::findOrFail($id);

    /*  if ($request->user()->id !== $entry->user_id && $request->user()->role !== 'admin') {
        return response()->json(['error' => 'Forbidden'], 403);
    } */

    $validated = $request->validate([
        'day' => 'sometimes|date',
        'hours' => 'sometimes|numeric|min:0',
        'note' => 'nullable|string|max:255',
    ]);

    $entry->update($validated);

    return response()->json($entry);
}
}
