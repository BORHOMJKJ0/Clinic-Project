<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Repositories\DoctorRepository;
use App\Traits\AuthTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DoctorService
{
    use AuthTrait;

    protected $doctorRepository;

    public function __construct(DoctorRepository $doctorRepository)
    {
        $this->doctorRepository = $doctorRepository;
    }

    public function getAllDoctors(Request $request)
    {
        $page = $request->query('page', 1);
        $items = $request->query('items', 20);

        $doctors = $this->doctorRepository->getAll($items, $page);
        $hasMorePages = $doctors->hasMorePages();

        $data = [
            'Doctors' => DoctorResource::collection($doctors),
            'hasMorePages' => $hasMorePages,
        ];

        return ResponseHelper::jsonResponse($data, 'Doctors retrieved successfully!');
    }

    public function getDoctorById(Doctor $doctor)
    {
        $data = ['Doctor' => DoctorResource::make($doctor)];

        return ResponseHelper::jsonResponse($data, 'Doctor retrieved successfully!');
    }

    public function createDoctor(array $data)
    {
        $this->validateDoctorData($data);
        $doctor = $this->doctorRepository->create($data);
        $data = [
            'Doctor' => DoctorResource::make($doctor),
        ];

        return ResponseHelper::jsonResponse($data, 'Doctor created successfully!', 201);
    }

    public function getDoctorsOrderedBy($column, $direction, Request $request)
    {
        $validColumns = ['first_name', 'last_name', 'phone', 'specialization', 'created_at', 'updated_at'];
        $validDirections = ['asc', 'desc'];

        if (! in_array($column, $validColumns) || ! in_array($direction, $validDirections)) {
            return ResponseHelper::jsonResponse([], 'Invalid column or direction', 400, false);
        }

        $page = $request->query('page', 1);
        $items = $request->query('items', 20);
        $doctors = $this->doctorRepository->orderBy($column, $direction, $page, $items);
        $hasMorePages = $doctors->hasMorePages();

        $data = [
            'Doctors' => DoctorResource::collection($doctors),
            'hasMorePages' => $hasMorePages,
        ];

        return ResponseHelper::jsonResponse($data, 'Doctors ordered successfully');

    }

    public function updateDoctor(Doctor $doctor, array $data)
    {
        try {
            $this->validateDoctorData($data, 'sometimes');
            $this->checkOwnership($doctor, 'Doctor', 'update');
            $doctor = $this->doctorRepository->update($doctor, $data);
            $data = ['Doctor' => DoctorResource::make($doctor)];
            $response = ResponseHelper::jsonResponse($data, 'Doctor updated successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function deleteDoctor(Doctor $doctor)
    {

        try {
            $this->checkOwnership($doctor, 'Doctor', 'delete');
            $this->doctorRepository->delete($doctor);
            $response = ResponseHelper::jsonResponse([], 'Doctor deleted successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    protected function validateDoctorData(array $data, $rule = 'required')
    {
        $validator = Validator::make($data, [
            'specialization' => "$rule",
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
