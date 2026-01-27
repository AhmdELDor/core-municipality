<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCircularRequest;
use App\Http\Requests\UpdateCircularRequest;
use App\Http\Resources\CircularResource;
use App\Models\Circular;
use App\Traits\ApiResponse;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

class CircularController extends Controller
{
    use ApiResponse, FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Circular::query();

        // Search by title if search parameter is provided
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $circulars = $query->latest()->paginate(10);
        return $this->paginatedResponse($circulars, CircularResource::collection($circulars), 'Circulars retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCircularRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = $request->user()->id;

        // Handle file upload for image
        if ($request->hasFile('image')) {
            $validated['image_url'] = $this->uploadSingleFile($request->file('image'), 'circulars/images');
        }

        $circular = Circular::create($validated);
        return $this->successResponse(new CircularResource($circular), 'Circular created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Circular $circular)
    {
        return $this->successResponse(new CircularResource($circular), 'Circular retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCircularRequest $request, Circular $circular)
    {
        $validated = $request->validated();

        // Handle file upload for image and delete old image
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($circular->image_url) {
                $this->deleteFile($circular->image_url);
            }
            $validated['image_url'] = $this->uploadSingleFile($request->file('image'), 'circulars/images');
        }

        $circular->update($validated);
        return $this->successResponse(new CircularResource($circular), 'Circular updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Circular $circular)
    {
        // Delete image file if exists
        if ($circular->image_url) {
            $this->deleteFile($circular->image_url);
        }

        $circular->delete();
        return $this->successResponse(null, 'Circular deleted successfully');
    }
}
