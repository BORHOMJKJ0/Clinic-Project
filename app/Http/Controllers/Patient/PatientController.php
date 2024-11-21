<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    protected $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->middleware('auth:user-api');
        $this->patientService = $patientService;
    }

    public function index(Request $request): JsonResponse
    {
        return $this->patientService->getAllPatients($request);
    }

    public function store(Request $request): JsonResponse
    {
        return $this->patientService->createPatient($request->all());
    }

    public function show(Patient $patient): JsonResponse
    {
        return $this->patientService->getPatientById($patient);
    }

    public function update(Request $request, Patient $patient): JsonResponse
    {
        return $this->patientService->updatePatient($patient, $request->all());
    }

    public function orderBy($column, $direction, Request $request): JsonResponse
    {
        return $this->patientService->getPatientsOrderedBy($column, $direction, $request);
    }

    public function destroy(Patient $patient): JsonResponse
    {
        return $this->patientService->deletePatient($patient);
    }
}
