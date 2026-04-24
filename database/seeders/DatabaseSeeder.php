<?php


namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminDoctor = User::factory()->doctor()->create([
            'name' => 'Dr. Admin',
            'email' => 'doctor@example.com',
        ]);

        $testPatient = User::factory()->patient()->create([
            'name' => 'Test Patient',
            'email' => 'patient@example.com',
        ]);

        $doctors = User::factory(2)->doctor()->create();
        $doctors->push($adminDoctor); 

        $patients = User::factory(8)->patient()->create();
        $patients->push($testPatient); 

        $services = Service::factory(6)->create();

        for ($i = 0; $i < 20; $i++) {
            Appointment::factory()->create([
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'service_id' => $services->random()->id,
            ]);
        }
    }
}