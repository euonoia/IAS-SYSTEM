<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        // Copy data from sqlite to current connection
        $this->copyDataFromSqlite();
    }

    private function copyDataFromSqlite()
    {
        // Copy consultations
        $consultations = DB::connection('sqlite')->table('consultations')->get();
        foreach ($consultations as $consultation) {
            DB::table('consultations')->insert((array) $consultation);
        }

        // Copy medicines
        $medicines = DB::connection('sqlite')->table('medicines')->get();
        foreach ($medicines as $medicine) {
            DB::table('medicines')->insert((array) $medicine);
        }
    }
}
