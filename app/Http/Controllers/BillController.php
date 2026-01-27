<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;
use App\Http\Requests\CreateBillForUserRequest;
use App\Http\Resources\BillResource;
use App\Models\Bill;
use App\Traits\ApiResponse;

class BillController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bills = Bill::latest()->paginate(10);
        return $this->paginatedResponse($bills, BillResource::collection($bills), 'Bills retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBillRequest $request)
    {
        $bill = Bill::create($request->validated());
        return $this->successResponse(new BillResource($bill), 'Bill created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $bill)
    {
        return $this->successResponse(new BillResource($bill), 'Bill retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBillRequest $request, Bill $bill)
    {
        $bill->update($request->validated());
        return $this->successResponse(new BillResource($bill), 'Bill updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        $bill->delete();
        return $this->successResponse(null, 'Bill deleted successfully');
    }

    /**
     * Create a bill directly for a specific user.
     */
    public function createForUser(CreateBillForUserRequest $request)
    {
        $validated = $request->validated();

        $attachBill = \App\Models\AttachBill::create($validated);
        return $this->successResponse(new \App\Http\Resources\AttachBillResource($attachBill), 'Bill created for user successfully', 201);
    }
}
