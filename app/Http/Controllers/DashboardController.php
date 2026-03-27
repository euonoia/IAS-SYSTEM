<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hardcoded Stats
        $stats = [
            'total_patients' => '1,284',
            'today_appointments' => '42',
            'pending_bills' => '12',
            'available_doctors' => '8'
        ];

        // Hardcoded Appointments Collection
        $recentAppointments = collect([
            (object)[
                'id' => 1,
                'patient_name' => 'Juan Dela Cruz',
                'service' => 'Dental Cleaning',
                'status' => 'waiting',
                'time' => '09:00 AM'
            ],
            (object)[
                'id' => 2,
                'patient_name' => 'Maria Santos',
                'service' => 'General Checkup',
                'status' => 'in-progress',
                'time' => '10:30 AM'
            ],
            (object)[
                'id' => 3,
                'patient_name' => 'Antonio Luna',
                'service' => 'X-Ray Review',
                'status' => 'completed',
                'time' => '08:15 AM'
            ],
            (object)[
                'id' => 4,
                'patient_name' => 'Elena Reyes',
                'service' => 'Pediatric Consultation',
                'status' => 'waiting',
                'time' => '11:00 AM'
            ]
        ]);

        return view('dashboard', compact('stats', 'recentAppointments'));
    }
}