<?php

namespace App\Traits;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Exceptions\HttpResponseException;

trait AuthTrait
{
    public function checkOwnership($model, $modelType, $action)
    {
        if ($model->user_id !== auth()->id()) {
            throw new HttpResponseException(ResponseHelper::jsonResponse([],
                "You are not authorized to {$action} this {$modelType}.",
                403, false));
        }
    }
}
