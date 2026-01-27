<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestFormRequest;
use App\Http\Requests\UpdateRequestFormRequest;
use App\Http\Resources\RequestFormResource;
use App\Models\RequestForm;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RequestFormController extends Controller
{
    use ApiResponse;

    public function index()
    {
        // Usually users only see active forms
        $query = RequestForm::where('status', 'active');

        // Search by title
        if (request()->has('search')) {
            $search = request()->input('search');
            $query->where('title', 'like', '%' . $search . '%');
        }

        $forms = $query->latest()->paginate(10);
        return $this->paginatedResponse($forms, RequestFormResource::collection($forms), 'Request forms retrieved successfully');
    }

    public function store(StoreRequestFormRequest $request)
    {
        $form = RequestForm::create($request->validated());
        return $this->successResponse(new RequestFormResource($form), 'Request form created successfully', 201);
    }

    public function show(RequestForm $requestForm)
    {
        return $this->successResponse(new RequestFormResource($requestForm), 'Request form retrieved successfully');
    }

    public function update(UpdateRequestFormRequest $request, RequestForm $requestForm)
    {
        $requestForm->update($request->validated());
        return $this->successResponse(new RequestFormResource($requestForm), 'Request form updated successfully');
    }

    public function destroy(RequestForm $requestForm)
    {
        $requestForm->delete();
        return $this->successResponse(null, 'Request form deleted successfully');
    }
}
