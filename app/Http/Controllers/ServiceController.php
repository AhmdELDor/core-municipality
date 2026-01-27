<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Traits\ApiResponse;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use ApiResponse, FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Service::query();

        // Search by name if search parameter is provided
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Order by priority (true first) then by latest
        $services = $query->orderBy('priority', 'desc')
                         ->latest()
                         ->paginate(20);

        return $this->paginatedResponse($services, ServiceResource::collection($services), 'Services retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        $validated = $request->validated();

        // Handle file upload for logo
        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->uploadSingleFile($request->file('logo'), 'services/logos');
        }

        $service = Service::create($validated);
        return $this->successResponse(new ServiceResource($service), 'Service created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return $this->successResponse(new ServiceResource($service), 'Service retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $validated = $request->validated();

        // Handle file upload for logo and delete old logo
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($service->logo) {
                $this->deleteFile($service->logo);
            }
            $validated['logo'] = $this->uploadSingleFile($request->file('logo'), 'services/logos');
        }

        $service->update($validated);
        return $this->successResponse(new ServiceResource($service), 'Service updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        // Delete logo file if exists
        if ($service->logo) {
            $this->deleteFile($service->logo);
        }

        $service->delete();
        return $this->successResponse(null, 'Service deleted successfully');
    }
}
