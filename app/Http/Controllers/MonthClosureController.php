<?php
namespace App\Http\Controllers;

use App\Models\TimeEntry;
use App\Models\MonthClosure;
use Illuminate\Http\Request;

class MonthClosureController extends Controller
{
    public function index()
    {
        $user = request()->user();
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $closures = MonthClosure::with('user:id,name')
            ->orderBy('year','desc')
            ->orderBy('month','desc')
            ->get();

        // Map project_ids and user_ids to names
        $closures->transform(function ($closure) {
            $summary = $closure->summary;

            // Replace project ids with names
            if (isset($summary['projects'])) {
                $projectNames = \App\Models\Project::whereIn('id', array_keys($summary['projects']))
                    ->pluck('name','id');
                $summary['projects'] = collect($summary['projects'])->mapWithKeys(function ($hours, $id) use ($projectNames) {
                    return [$projectNames[$id] ?? "Project #$id" => $hours];
                });
            }

            // Replace user ids with names
            if (isset($summary['users'])) {
                $userNames = \App\Models\User::whereIn('id', array_keys($summary['users']))
                    ->pluck('name','id');
                $summary['users'] = collect($summary['users'])->mapWithKeys(function ($hours, $id) use ($userNames) {
                    return [$userNames[$id] ?? "User #$id" => $hours];
                });
            }

            $closure->summary = $summary;
            return $closure;
        });

        return $closures;
    }

    public function show($id)
    {
        $closure = MonthClosure::with('user:id,name')->findOrFail($id);
        return $closure;
    }

    public function store(Request $req)
    {
        $req->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12'
        ]);

        $user = $req->user();
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $year = $req->year;
        $month = $req->month;

        // get not locked entries
        $entries = TimeEntry::whereYear('day', $year)
            ->whereMonth('day', $month)
            ->where('locked', false)
            ->get();

        if ($entries->isEmpty()) {
            return response()->json([
                'message' => 'No entries to close for this month',
                'closure' => null
            ], 200);
        }

        // compute summary
        $summary = ['projects' => [], 'users' => []];
        foreach ($entries as $e) {
            $summary['projects'][$e->project_id] = ($summary['projects'][$e->project_id] ?? 0) + $e->hours;
            $summary['users'][$e->user_id] = ($summary['users'][$e->user_id] ?? 0) + $e->hours;
        }

        // lock entries
        TimeEntry::whereYear('day', $year)
            ->whereMonth('day', $month)
            ->where('locked', false)
            ->update(['locked' => true]);

        // allow multiple closures: create new record
        $closure = MonthClosure::create([
            'year' => $year,
            'month' => $month,
            'summary' => $summary,
            'closed_by' => $user->id,
        ]);

        return response()->json([
            'message' => 'Month closed successfully',
            'closure' => $closure
        ], 201);
    }
}
