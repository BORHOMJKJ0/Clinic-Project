<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Repositories\appointmentRepository;
use App\Traits\AuthTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AppointmentService
{
    use AuthTrait;

    protected $appointmentRepository;

    public function __construct(appointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function getAllAppointments(Request $request)
    {
        $page = $request->query('page', 1);
        $items = $request->query('items', 20);

        $appointments = $this->appointmentRepository->getAll($items, $page);
        $hasMorePages = $appointments->hasMorePages();

        $data = [
            'Appointments' => AppointmentResource::collection($appointments),
            'hasMorePages' => $hasMorePages,
        ];

        return ResponseHelper::jsonResponse($data, 'Appointments retrieved successfully!');
    }

    public function getAppointmentById(Appointment $appointment)
    {
        $data = ['Appointment' => AppointmentResource::make($appointment)];

        return ResponseHelper::jsonResponse($data, 'Appointment retrieved successfully!');
    }

    public function createAppointment(array $data)
    {
        $this->validateAppointmentData($data);
        $this->checkOwnership('app', 'Appointment', 'create');
        $data['status'] = 'reserved';
        $appointment = $this->appointmentRepository->create($data);
        $data = [
            'Appointment' => AppointmentResource::make($appointment),
        ];

        return ResponseHelper::jsonResponse($data, 'Appointment created successfully!', 201);
    }

    public function getAppointmentsOrderedBy($column, $direction, Request $request)
    {
        $validColumns = ['status', 'appointment_date', 'appointment_time', 'created_at', 'updated_at'];
        $validDirections = ['asc', 'desc'];

        if (! in_array($column, $validColumns) || ! in_array($direction, $validDirections)) {
            return ResponseHelper::jsonResponse([], 'Invalid column or direction', 400, false);
        }

        $page = $request->query('page', 1);
        $items = $request->query('items', 20);
        $appointments = $this->appointmentRepository->orderBy($column, $direction, $page, $items);
        $hasMorePages = $appointments->hasMorePages();

        $data = [
            'Appointments' => AppointmentResource::collection($appointments),
            'hasMorePages' => $hasMorePages,
        ];

        return ResponseHelper::jsonResponse($data, 'Appointments ordered successfully');

    }

    public function updateAppointment(Appointment $appointment, array $data)
    {
        try {
            $this->validateAppointmentData($data, 'sometimes');
            $this->checkOwnership($appointment, 'Appointment', 'update');
            $appointment = $this->appointmentRepository->update($appointment, $data);
            $data = ['Appointment' => AppointmentResource::make($appointment)];
            $response = ResponseHelper::jsonResponse($data, 'Appointment updated successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function deleteAppointment(Appointment $appointment)
    {

        try {
            $this->checkOwnership($appointment, 'Appointment', 'delete');
            $this->appointmentRepository->delete($appointment);
            $response = ResponseHelper::jsonResponse([], 'Appointment deleted successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    protected function validateAppointmentData(array $data, $rule = 'required')
    {
        $validator = Validator::make($data, [
            'appointment_time' => "$rule|date_format:H:i",
            'appointment_date' => "$rule|date",
            'doctor_id' => "$rule|exists:doctors,id",
            'patient_id' => "$rule|exists:patients,id",
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
