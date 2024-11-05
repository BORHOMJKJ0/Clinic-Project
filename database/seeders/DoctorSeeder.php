<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            Doctor::factory(20)->create();
        });
    }
}
