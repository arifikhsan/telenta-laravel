<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ManagerCandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $manager = auth()->user();
        $candidates = Candidate
            ::with(['position', 'manager'])->
            where('manager_id', $manager->id)->get()->map(function ($candidate) {
                // Generate the full URL for the CV, if it exists
                $candidate->cv_url = $candidate->cv_path
                    ? Storage::disk('public')->url($candidate->cv_path)
                    : null; // If no CV exists, set null

                return $candidate;
            });

        return Inertia::render('ManagerCandidates', ['candidates' => $candidates]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
