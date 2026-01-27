<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitServiceRequest;
use App\Http\Resources\UserRequestResource;
use App\Models\UserRequest;
use App\Traits\ApiResponse;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

class UserRequestController extends Controller
{
    use ApiResponse, FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Admin sees all requests, users see only their own
        if (in_array($request->user()->role, ['admin', 'superadmin'])) {
            $query = UserRequest::with(['requestForm', 'user']);
        } else {
            $query = $request->user()->requests()->with(['requestForm', 'user']);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Search by citizen name or request form title
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                // Search by user full name
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('full_name', 'like', '%' . $search . '%');
                })
                // Or search by request form title
                ->orWhereHas('requestForm', function ($formQuery) use ($search) {
                    $formQuery->where('title', 'like', '%' . $search . '%');
                });
            });
        }

        $requests = $query->latest()->paginate(10);
        return $this->paginatedResponse($requests, UserRequestResource::collection($requests), 'User requests retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubmitServiceRequest $request)
    {
        $validated = $request->validated();

        // Check if user already applied to this request form
        $existingRequest = UserRequest::where('user_id', $request->user()->id)
            ->where('request_form_id', $validated['request_form_id'])
            ->exists();

        if ($existingRequest) {
            return $this->errorResponse('You have already applied for this service.', 409);
        }

        $attachments = [];

        // Handle 'data' field (decode if sent as JSON string in multipart form)
        $formData = $validated['data'];
        if (is_string($formData)) {
            $formData = json_decode($formData, true);
        }

        // Get request form to identify file fields
        $requestForm = \App\Models\RequestForm::findOrFail($validated['request_form_id']);
        $fileFields = collect($requestForm->fields)->filter(function ($field) {
            return isset($field['type']) && $field['type'] === 'file';
        });

        // Handle file uploads for each field
        foreach ($fileFields as $field) {
            $fieldName = $field['name'] ?? null;
            if (!$fieldName) continue;

            // Check if file was uploaded for this field
            if ($request->hasFile($fieldName)) {
                $files = $request->file($fieldName);

                // Handle multiple files for a single field
                if (is_array($files)) {
                    $uploadedUrls = $this->uploadMultipleFiles($files, 'requests/attachments');
                    $attachments[$fieldName] = $uploadedUrls;
                } else {
                    // Single file
                    $uploadedUrls = $this->uploadMultipleFiles([$files], 'requests/attachments');
                    $attachments[$fieldName] = $uploadedUrls[0] ?? null;
                }
            }
        }

        // Create record
        $userRequest = UserRequest::create([
            'user_id' => $request->user()->id,
            'request_form_id' => $validated['request_form_id'],
            'data' => $formData,
            'attachments' => $attachments,
            'status' => 'pending',
        ]);

        return $this->successResponse(new UserRequestResource($userRequest), 'Request submitted successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, UserRequest $userRequest)
    {
        // Ensure user owns the request
        if ($request->user()->id !== $userRequest->user_id) {
            return $this->errorResponse('Unauthorized', 403);
        }
        return $this->successResponse(new UserRequestResource($userRequest), 'Request details retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserRequest $userRequest)
    {
        // Only admin should be able to update status/note
        if (!in_array($request->user()->role, ['admin', 'superadmin'])) {
            return $this->errorResponse('Unauthorized. Only admins can update requests.', 403);
        }

        $validated = $request->validate([
            'status' => 'sometimes|string',
            'admin_note' => 'nullable|string',
        ]);

        $userRequest->update($validated);
        return $this->successResponse(new UserRequestResource($userRequest), 'Request updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, UserRequest $userRequest)
    {
        if ($request->user()->id !== $userRequest->user_id) {
            return $this->errorResponse('Unauthorized', 403);
        }

        if ($userRequest->status !== 'pending') {
            return $this->errorResponse('Cannot delete a request that is being processed', 400);
        }

        // Delete attachment files if exists
        if ($userRequest->attachments) {
            // Attachments are now stored as field_name => url(s)
            foreach ($userRequest->attachments as $fieldName => $files) {
                if (is_array($files)) {
                    $this->deleteMultipleFiles($files);
                } else {
                    $this->deleteMultipleFiles([$files]);
                }
            }
        }

        $userRequest->delete();
        return $this->successResponse(null, 'Request deleted successfully');
    }
}
