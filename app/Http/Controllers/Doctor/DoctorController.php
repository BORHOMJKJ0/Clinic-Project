<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Services\DoctorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    protected $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        //  $this->middleware('auth:api');
        $this->doctorService = $doctorService;
    }

    public function index(Request $request): JsonResponse
    {
        return $this->doctorService->getAllDoctors($request);
    }

    public function store(Request $request): JsonResponse
    {
        return $this->doctorService->createDoctor($request->all());
    }

    public function show(Doctor $doctor): JsonResponse
    {
        return $this->doctorService->getDoctorById($doctor);
    }

    public function update(Request $request, Doctor $doctor): JsonResponse
    {
        return $this->doctorService->updateDoctor($doctor, $request->all());
    }

    public function orderBy($column, $direction, Request $request): JsonResponse
    {
        return $this->doctorService->getDoctorsOrderedBy($column, $direction, $request);
    }

    public function destroy(Doctor $doctor): JsonResponse
    {
        return $this->doctorService->deleteDoctor($doctor);
    }
}
