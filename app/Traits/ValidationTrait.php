<?php

namespace App\Traits;

use App\Helpers\ResponseHelper;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ValidationTrait
{
    public function checkDate(array $data, string $name, string $condition)
    {
        $dateTime = Carbon::parse($data[$name]);

        if ($condition === 'future' && $dateTime->isPast()) {
            throw new HttpResponseException(ResponseHelper::jsonResponse([],
                "The {$name} must be in the future.",
                400, false));
        }
    }
}
