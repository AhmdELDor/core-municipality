<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search by name or phone
        if ($request->has('search') && $request->search) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(full_name) LIKE ?', ['%' . $search . '%'])
                  ->orWhereRaw('LOWER(phonenumber) LIKE ?', ['%' . $search . '%']);
            });
        }

        $users = $query->latest()->paginate(13);
        return $this->paginatedResponse($users, UserResource::collection($users), 'Users retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        // Set default role if not provided
        if (!isset($validated['role'])) {
            $validated['role'] = 'citizen';
        }

        $user = User::create($validated);

        return $this->successResponse(new UserResource($user), 'User created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->successResponse(new UserResource($user), 'User retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return $this->successResponse(new UserResource($user), 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->successResponse(null, 'User deleted successfully');
    }

    /**
     * Get authenticated user profile with all related data.
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        // Load all relationships
        $user->load([
            'requests.requestForm',
            'complaints',
            'attachBills',
            'suggestions',
            'explores',
        ]);

        // Calculate statistics
        $statistics = [
            'total_requests' => $user->requests->count(),
            'pending_requests' => $user->requests->where('status', 'pending')->count(),
            'total_complaints' => $user->complaints->count(),
            'pending_complaints' => $user->complaints->where('status', 'pending')->count(),
            'total_bills' => $user->attachBills->count(),
            'unpaid_bills' => $user->attachBills->whereNull('paid_date')->count(),
            'total_unpaid_amount' => $user->attachBills->whereNull('paid_date')->sum('amount'),
            'total_suggestions' => $user->suggestions->count(),
            'total_explores' => $user->explores->count(),
        ];

        // Attach statistics to user object
        $user->statistics = $statistics;

        return $this->successResponse(new UserProfileResource($user), 'Profile retrieved successfully');
    }
}
