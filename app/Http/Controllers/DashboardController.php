<?php

namespace App\Http\Controllers;

use App\Models\StudentMedicalRecordClinic;

class DashboardController extends Controller
{
    public function index()
    {
        $recentAppointments = collect();
        $recentRecords = StudentMedicalRecordClinic::latest()->take(5)->get();

        $stats = [
            'total_patients' => number_format(StudentMedicalRecordClinic::count()),
            'today_appointments' => $recentAppointments->count(),
            'pending_bills' => '12',
            'available_doctors' => '8',
        ];

        return view('dashboard', compact('stats', 'recentAppointments', 'recentRecords'));
    }
}