<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttachBillRequest;
use App\Http\Requests\UpdateAttachBillRequest;
use App\Http\Requests\BulkAttachBillRequest;
use App\Http\Resources\AttachBillResource;
use App\Models\AttachBill;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AttachBillController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'paid' => 'nullable|in:0,1',
        ]);

        $paidFilter = $validated['paid'] ?? null;

        $attachBills = AttachBill::with('citizen')
            ->when($paidFilter !== null, function ($query) use ($paidFilter) {
                return $paidFilter === '1'
                    ? $query->whereNotNull('paid_date')
                    : $query->whereNull('paid_date');
            })
            ->latest()
            ->paginate(10);
        return $this->paginatedResponse($attachBills, AttachBillResource::collection($attachBills), 'Attached bills retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttachBillRequest $request)
    {
        $attachBill = AttachBill::create($request->validated());
        $attachBill->load('citizen');
        return $this->successResponse(new AttachBillResource($attachBill), 'Bill attached successfully', 201);
    }

    /**
     * Bulk attach bills to users by explicit list or by role segment.
     */
    public function bulkAttach(BulkAttachBillRequest $request)
    {
        $validated = $request->validated();

        // Resolve recipients from role if provided
        $roleUserIds = collect();
        if (!empty($validated['target_role'])) {
            $role = $validated['target_role'];
            $roleUserIds = User::query()
                ->when($role === 'citizen', fn ($q) => $q->where('role', 'citizen'))
                ->when($role === 'admin', fn ($q) => $q->where('role', 'admin'))
                ->pluck('id');
        }

        $explicitUserIds = collect($validated['user_ids'] ?? []);
        $targetUserIds = $explicitUserIds->merge($roleUserIds)->unique()->values();

        if ($targetUserIds->isEmpty()) {
            return $this->errorResponse('Please provide user_ids or target_role to attach bills', 422);
        }

        $created = [];
        foreach ($targetUserIds as $userId) {
            $bill = AttachBill::create([
                'citizen_id' => $userId,
                'title' => $validated['title'],
                'desc' => $validated['desc'] ?? null,
                'amount' => $validated['amount'],
                'due_date' => $validated['due_date'],
                'paid_date' => $validated['paid_date'] ?? null,
                'note' => $validated['note'] ?? null,
            ]);

            $created[] = new AttachBillResource($bill->load('citizen'));
        }

        return $this->successResponse($created, 'Bills attached successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(AttachBill $attachBill)
    {
        $attachBill->loadMissing('citizen');
        return $this->successResponse(new AttachBillResource($attachBill), 'Attached bill retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttachBillRequest $request, AttachBill $attachBill)
    {
        $attachBill->update($request->validated());
        $attachBill->loadMissing('citizen');
        return $this->successResponse(new AttachBillResource($attachBill), 'Attached bill updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttachBill $attachBill)
    {
        $attachBill->delete();
        return $this->successResponse(null, 'Attached bill deleted successfully');
    }

    /**
     * Mark the bill as paid.
     */
    public function pay(AttachBill $attachBill)
    {
        $attachBill->update(['paid_date' => now()]);
        $attachBill->loadMissing('citizen');
        return $this->successResponse(new AttachBillResource($attachBill), 'Bill paid successfully');
    }

    /**
     * Mark the bill as unpaid.
     */
    public function unpay(AttachBill $attachBill)
    {
        $attachBill->update(['paid_date' => null]);
        $attachBill->loadMissing('citizen');
        return $this->successResponse(new AttachBillResource($attachBill), 'Bill unpaid successfully');
    }
}
