<?php

namespace App\Repositories;

use App\Models\Doctor;
use App\Traits\Lockable;

class DoctorRepository
{
    use Lockable;

    public function getAll($items, $page)
    {
        return Doctor::paginate($items, ['*'], 'page', $page);
    }

    public function orderBy($column, $direction, $page, $items)
    {
        if (in_array($column, ['first_name', 'last_name', 'phone'])) {
            return Doctor::join('users', 'doctors.user_id', '=', 'users.id')
                ->orderBy("users.$column", $direction)
                ->paginate($items, ['doctors.*'], 'page', $page);
        } else {
            return Doctor::orderBy($column, $direction)->paginate($items, ['*'], 'page', $page);
        }
    }

    public function create(array $data)
    {
        return $this->lockForCreate(function () use ($data) {
            return Doctor::create($data);
        });
    }

    public function update(Doctor $doctor, array $data)
    {
        return $this->lockForUpdate(Doctor::class, $doctor->id, function ($lockedDoctor) use ($data) {
            $lockedDoctor->update($data);

            return $lockedDoctor;
        });
    }

    public function delete(Doctor $doctor)
    {
        return $this->lockForDelete(Doctor::class, $doctor->id, function ($lockedDoctor) {
            return $lockedDoctor->delete();
        });
    }
}
