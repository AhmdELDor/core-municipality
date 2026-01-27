<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComplaintRequest;
use App\Http\Requests\UpdateComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Models\Complaint;
use App\Traits\ApiResponse;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    use ApiResponse, FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Complaint::with('user');

        // Search by title, user name, or phone number
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('full_name', 'like', '%' . $search . '%')
                        ->orWhere('phonenumber', 'like', '%' . $search . '%');
                  });
            });
        }

        $complaints = $query->latest()->paginate(10);
        return $this->paginatedResponse($complaints, ComplaintResource::collection($complaints), 'Complaints retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreComplaintRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        // Handle file uploads for images
        if ($request->hasFile('images')) {
            $validated['images_url'] = $this->uploadMultipleFiles($request->file('images'), 'complaints/images');
        }

        $complaint = Complaint::create($validated);
        return $this->successResponse(new ComplaintResource($complaint), 'Complaint created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Complaint $complaint)
    {
        return $this->successResponse(new ComplaintResource($complaint), 'Complaint retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateComplaintRequest $request, Complaint $complaint)
    {
        $validated = $request->validated();

        // Handle file uploads for images and delete old images
        if ($request->hasFile('images')) {
            // Delete old images if exists
            if ($complaint->images_url) {
                $this->deleteMultipleFiles($complaint->images_url);
            }
            $validated['images_url'] = $this->uploadMultipleFiles($request->file('images'), 'complaints/images');
        }

        $complaint->update($validated);
        return $this->successResponse(new ComplaintResource($complaint), 'Complaint updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Complaint $complaint)
    {
        // Delete image files if exists
        if ($complaint->images_url) {
            $this->deleteMultipleFiles($complaint->images_url);
        }

        $complaint->delete();
        return $this->successResponse(null, 'Complaint deleted successfully');
    }
}
