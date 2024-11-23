<?php

namespace App\Traits;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Exceptions\HttpResponseException;

trait AuthTrait
{
    public function checkOwnership($model, $modelType, $action)
    {
        if (auth()->user()->role === 'doctor' && $modelType === 'Appointment') {
            throw new HttpResponseException(ResponseHelper::jsonResponse([],
                "Doctors are not allowed to {$action} appointments",
                403, false));
        }
                if ($model->user_id !== auth()->id()) {
                    throw new HttpResponseException(ResponseHelper::jsonResponse([],
                        "You are not authorized to {$action} this {$modelType}.",
                        403, false));
                }
    }
}
