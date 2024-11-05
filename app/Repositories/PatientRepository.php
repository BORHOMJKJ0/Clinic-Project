<?php

namespace App\Repositories;

use App\Models\Patient;
use App\Traits\Lockable;

class PatientRepository
{
    use Lockable;

    public function getAll($items, $page)
    {
        return Patient::paginate($items, ['*'], 'page', $page);
    }

    public function orderBy($column, $direction, $page, $items)
    {
        if (in_array($column, ['first_name', 'last_name', 'phone'])) {
            return Patient::join('users', 'Patients.user_id', '=', 'users.id')
                ->orderBy("users.$column", $direction)
                ->paginate($items, ['Patients.*'], 'page', $page);
        } else {
            return Patient::orderBy($column, $direction)->paginate($items, ['*'], 'page', $page);
        }
    }

    public function create(array $data)
    {
        return $this->lockForCreate(function () use ($data) {
            return Patient::create($data);
        });
    }

    public function update(Patient $patient, array $data)
    {
        return $this->lockForUpdate(Patient::class, $patient->id, function ($lockedPatient) use ($data) {
            $lockedPatient->update($data);

            return $lockedPatient;
        });
    }

    public function delete(Patient $patient)
    {
        return $this->lockForDelete(Patient::class, $patient->id, function ($lockedPatient) {
            return $lockedPatient->delete();
        });
    }
}
