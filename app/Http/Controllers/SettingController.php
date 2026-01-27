<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::all();
        return $this->successResponse(SettingResource::collection($settings), 'Settings retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSettingRequest $request)
    {
        $setting = Setting::create($request->validated());
        return $this->successResponse(new SettingResource($setting), 'Setting created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        return $this->successResponse(new SettingResource($setting), 'Setting retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        $setting->update($request->validated());
        return $this->successResponse(new SettingResource($setting), 'Setting updated successfully');
    }
}
