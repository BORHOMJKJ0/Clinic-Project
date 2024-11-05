<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Repositories\PatientRepository;
use App\Traits\AuthTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PatientService
{
    use AuthTrait;

    protected $patientRepository;

    public function __construct(PatientRepository $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    public function getAllPatients(Request $request)
    {
        $page = $request->query('page', 1);
        $items = $request->query('items', 20);

        $patients = $this->patientRepository->getAll($items, $page);
        $hasMorePages = $patients->hasMorePages();

        $data = [
            'Patients' => PatientResource::collection($patients),
            'hasMorePages' => $hasMorePages,
        ];

        return ResponseHelper::jsonResponse($data, 'Patients retrieved successfully!');
    }

    public function getPatientById(Patient $patient)
    {
        $data = ['Patient' => PatientResource::make($patient)];

        return ResponseHelper::jsonResponse($data, 'Patient retrieved successfully!');
    }

    public function createPatient(array $data)
    {
        $this->validatePatientData($data);
        $patient = $this->patientRepository->create($data);
        $data = [
            'Patient' => PatientResource::make($patient),
        ];

        return ResponseHelper::jsonResponse($data, 'Patient created successfully!', 201);
    }

    public function getPatientsOrderedBy($column, $direction, Request $request)
    {
        $validColumns = ['age', 'first_name', 'last_name', 'phone', 'created_at', 'updated_at'];
        $validDirections = ['asc', 'desc'];

        if (! in_array($column, $validColumns) || ! in_array($direction, $validDirections)) {
            return ResponseHelper::jsonResponse([], 'Invalid column or direction', 400, false);
        }

        $page = $request->query('page', 1);
        $items = $request->query('items', 20);
        $patients = $this->patientRepository->orderBy($column, $direction, $page, $items);
        $hasMorePages = $patients->hasMorePages();

        $data = [
            'Patients' => PatientResource::collection($patients),
            'hasMorePages' => $hasMorePages,
        ];

        return ResponseHelper::jsonResponse($data, 'Patients ordered successfully');

    }

    public function updatePatient(Patient $patient, array $data)
    {
        try {
            $this->validatePatientData($data, 'sometimes');
            //$this->checkOwnership($patient, 'Patient', 'update');
            $patient = $this->patientRepository->update($patient, $data);
            $data = ['Patient' => PatientResource::make($patient)];
            $response = ResponseHelper::jsonResponse($data, 'Patient updated successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function deletePatient(Patient $patient)
    {

        try {
            // $this->checkOwnership($patient, 'Patient', 'delete');
            $this->patientRepository->delete($patient);
            $response = ResponseHelper::jsonResponse([], 'Patient deleted successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    protected function validatePatientData(array $data, $rule = 'required')
    {
        $validator = Validator::make($data, [
            'age' => "$rule",
            'user_id' => "$rule|exists:users,id",
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
