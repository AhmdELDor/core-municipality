<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Traits\ApiResponse;
use App\Traits\FileUploadTrait;

class ProjectController extends Controller
{
    use ApiResponse, FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::latest()->paginate(10);
        return $this->paginatedResponse($projects, ProjectResource::collection($projects), 'Projects retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();

        // Handle file uploads for images
        if ($request->hasFile('images')) {
            $validated['image_urls'] = $this->uploadMultipleFiles($request->file('images'), 'projects/images');
        }

        $project = Project::create($validated);
        return $this->successResponse(new ProjectResource($project), 'Project created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return $this->successResponse(new ProjectResource($project), 'Project retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();

        // Handle file uploads for images and delete old images
        if ($request->hasFile('images')) {
            // Delete old images if exists
            if ($project->image_urls) {
                $this->deleteMultipleFiles($project->image_urls);
            }
            $validated['image_urls'] = $this->uploadMultipleFiles($request->file('images'), 'projects/images');
        }

        $project->update($validated);
        return $this->successResponse(new ProjectResource($project), 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // Delete image files if exists
        if ($project->image_urls) {
            $this->deleteMultipleFiles($project->image_urls);
        }

        $project->delete();
        return $this->successResponse(null, 'Project deleted successfully');
    }
}
