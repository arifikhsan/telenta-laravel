<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidates = Candidate::with(['position', 'manager'])
            ->orderBy('id', 'desc') // Order by 'id' in descending order
            ->get()
            ->map(function ($candidate) {
                // Generate the full URL for the CV, if it exists
                $candidate->cv_url = $candidate->cv_path
                    ? Storage::disk('public')->url($candidate->cv_path)
                    : null; // If no CV exists, set null

                return $candidate;
            });

        return Inertia::render('admin/candidates/Candidates', ['candidates' => $candidates]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $managers = User::whereHas('role', function ($query) {
            $query->where('name', 'manager');  // Make sure the role is 'manager'
        })->get();

        $positions = Position::all();

        return Inertia::render('admin/candidates/CreateCandidate', [
            'managers' => $managers,  // Pass the managers to the view
            'positions' => $positions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:50',
            'manager_id' => 'required|exists:users,id',
            'position_id' => 'required|exists:positions,id',
            'status' => 'required|string',
            'proposed_date' => 'nullable|date',
            'days_required' => 'nullable|integer|min:1',
            'cv_review_date' => 'nullable|date',
            'hr_interview_date' => 'nullable|date',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB max
        ]);

        $cvPath = null;

        // Handle CV upload if provided
        if ($request->hasFile('cv')) {
            $cvFile = $request->file('cv');
            $cvFileName = Str::random(10) . '-' . $cvFile->getClientOriginalName();
            $cvPath = 'cv/' . $cvFileName;

            // Store the file in storage/app/public/cv
            Storage::disk('public')->put($cvPath, file_get_contents($cvFile));
        }

        // Convert dates to the correct format using Carbon
        $proposedDate = isset($validated['proposed_date']) ? Carbon::parse($validated['proposed_date'])->toDateString() : null;
        $cvReviewDate = isset($validated['cv_review_date']) ? Carbon::parse($validated['cv_review_date'])->toDateString() : null;
        $hrInterviewDate = isset($validated['hr_interview_date']) ? Carbon::parse($validated['hr_interview_date'])->toDateString() : null;


        // Prepare the candidate data, with null defaults for optional fields
        $candidate = [
            'name' => $validated['name'],
            'manager_id' => $validated['manager_id'],
            'position_id' => $validated['position_id'],
            'status' => $validated['status'],
            'days_required' => $validated['days_required'] ?? null, // Set to null if not provided
            'proposed_date' => $proposedDate,
            'cv_review_date' => $cvReviewDate,
            'hr_interview_date' => $hrInterviewDate,
            'cv_path' => $cvPath, // If no CV, this will be null
        ];

        // Insert the candidate data
        $candidate = Candidate::create($candidate);

        return response()->json([
            'message' => 'Candidate created successfully!',
            'candidate' => $candidate, // Optionally return the created candidate data
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $candidate = Candidate::findOrFail($id);
        $managers = User::whereHas('role', function ($query) {
            $query->where('name', 'manager');  // Make sure the role is 'manager'
        })->get();

        return Inertia::render('admin/candidates/EditCandidate', [
            'candidate' => $candidate,
            'managers' => $managers,
            'positions' => Position::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $candidate = Candidate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|min:2|max:50',
            'manager_id' => 'required|exists:managers,id',
            'position_id' => 'required|exists:positions,id',
            'status' => 'required|string',
            'days_required' => 'nullable|integer|min:1',
            'proposed_date' => 'nullable|date',
            'cv_review_date' => 'nullable|date',
            'hr_interview_date' => 'nullable|date',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($request->hasFile('cv')) {
            $validated['cv_url'] = $request->file('cv')->store('cvs', 'public');
        }

        $candidate->update($validated);

        return redirect()->route('dashboard.candidates.index')->with('message', 'Candidate updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
