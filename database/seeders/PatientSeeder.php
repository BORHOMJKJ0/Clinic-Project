<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            Patient::factory(20)->create();
        });
    }
}
