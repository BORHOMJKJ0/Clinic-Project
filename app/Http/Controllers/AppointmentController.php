<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->middleware('auth:api');
        $this->appointmentService = $appointmentService;
    }

    public function index(Request $request)
    {
        return $this->appointmentService->getAllAppointments($request);
    }

    public function store(Request $request)
    {
        return $this->appointmentService->createAppointment($request->all());
    }

    public function show(Appointment $appointment)
    {
        return $this->appointmentService->getAppointmentById($appointment);
    }

    public function update(Request $request, Appointment $appointment)
    {
        return $this->appointmentService->updateAppointment($appointment, $request->all());
    }

    public function orderBy($column, $direction, Request $request): JsonResponse
    {
        return $this->appointmentService->getAppointmentsOrderedBy($column, $direction, $request);
    }

    public function destroy(Appointment $appointment)
    {
        return $this->appointmentService->deleteAppointment($appointment);
    }
}
