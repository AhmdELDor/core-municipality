<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExploreRequest;
use App\Http\Requests\UpdateExploreRequest;
use App\Http\Requests\ApplyExploreRequest;
use App\Http\Requests\ApproveExploreRequest;
use App\Http\Resources\ExploreResource;
use App\Models\Explore;
use App\Traits\ApiResponse;
use App\Traits\FileUploadTrait;

class ExploreController extends Controller
{
    use ApiResponse, FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Explore::with('citizen')->latest();

        if (request()->has('type')) {
            $query->where('type', request('type'));
        }

        if (request()->has('status')) {
            $query->where('status', request('status'));
        }

        $explores = $query->paginate(13);
        return $this->paginatedResponse($explores, ExploreResource::collection($explores), 'Explores retrieved successfully');
    }

    /**
     * Get explores grouped by users.
     */
    public function groupedByUsers()
    {
        $users = Explore::with('citizen:id,full_name')
            ->where('status', 'approved')
            ->get()
            ->groupBy('citizen_id')
            ->map(function ($userExplores) {
                $citizen = $userExplores->first()->citizen;
                return [
                    'id' => $citizen->id,
                    'name' => $citizen->full_name,
                ];
            })->values();

        return $this->successResponse($users, 'Users with explores retrieved successfully');
    }

    /**
     * Get explores by user id.
     */
    public function byUserId($userId)
    {
        $explores = Explore::with('citizen')
            ->where('citizen_id', $userId)
            ->where('status', 'approved')
            ->latest()
            ->get();

        return $this->successResponse(ExploreResource::collection($explores), 'User explores retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExploreRequest $request)
    {
        $validated = $request->validated();

        // Handle file uploads for images
        if ($request->hasFile('images')) {
            $validated['images_url'] = $this->uploadMultipleFiles($request->file('images'), 'explore/images');
        }

        $explore = Explore::create($validated);
        return $this->successResponse(new ExploreResource($explore), 'Explore created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Explore $explore)
    {
        $explore->load('citizen');
        return $this->successResponse(new ExploreResource($explore), 'Explore retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExploreRequest $request, Explore $explore)
    {
        $validated = $request->validated();

        // Handle file uploads for images and delete old images
        if ($request->hasFile('images')) {
            // Delete old images if exists
            if ($explore->images_url) {
                $this->deleteMultipleFiles($explore->images_url);
            }
            $validated['images_url'] = $this->uploadMultipleFiles($request->file('images'), 'explore/images');
        }

        $explore->update($validated);
        return $this->successResponse(new ExploreResource($explore), 'Explore updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Explore $explore)
    {
        // Delete image files if exists
        if ($explore->images_url) {
            $this->deleteMultipleFiles($explore->images_url);
        }

        $explore->delete();
        return $this->successResponse(null, 'Explore deleted successfully');
    }

    /**
     * Allow citizen to apply for explore.
     */
    public function apply(ApplyExploreRequest $request)
    {
        $validated = $request->validated();

        // Handle file uploads for images
        if ($request->hasFile('images')) {
            $validated['images_url'] = $this->uploadMultipleFiles($request->file('images'), 'explore/images');
        }

        $validated['citizen_id'] = $request->user()->id;
        $validated['status'] = 'pending';

        $explore = Explore::create($validated);
        return $this->successResponse(new ExploreResource($explore), 'Explore application submitted successfully', 201);
    }

    /**
     * Approve an explore application.
     */
    public function approve(ApproveExploreRequest $request, Explore $explore)
    {
        $validated = $request->validated();

        $validated['status'] = 'approved';

        $explore->update($validated);
        return $this->successResponse(new ExploreResource($explore), 'Explore approved successfully');
    }
}
