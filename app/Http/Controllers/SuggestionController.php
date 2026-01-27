<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuggestionRequest;
use App\Http\Requests\UpdateSuggestionRequest;
use App\Http\Resources\SuggestionResource;
use App\Models\Suggestion;
use App\Traits\ApiResponse;

class SuggestionController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Suggestion::with('citizen');

        // Search by citizen name
        if (request()->has('search')) {
            $search = request()->input('search');
            $query->whereHas('citizen', function ($q) use ($search) {
                $q->where('full_name', 'like', '%' . $search . '%');
            });
        }

        $suggestions = $query->latest()->paginate(10);
        return $this->paginatedResponse($suggestions, SuggestionResource::collection($suggestions), 'Suggestions retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSuggestionRequest $request)
    {
        $validated = $request->validated();

        $validated['citizen_id'] = $request->user()->id;

        $suggestion = Suggestion::create($validated);
        return $this->successResponse(new SuggestionResource($suggestion), 'Suggestion created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Suggestion $suggestion)
    {
        $suggestion->load('citizen');
        return $this->successResponse(new SuggestionResource($suggestion), 'Suggestion retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSuggestionRequest $request, Suggestion $suggestion)
    {
        $validated = $request->validated();

        $suggestion->update($validated);
        return $this->successResponse(new SuggestionResource($suggestion), 'Suggestion updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Suggestion $suggestion)
    {
        $suggestion->delete();
        return $this->successResponse(null, 'Suggestion deleted successfully');
    }
}
