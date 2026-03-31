<?php

namespace App\Http\Controllers;

use App\Models\StudentMedicalRecordClinic;
use App\Models\Consultation;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Kunin ang mga konsultasyon na ginawa ngayong araw (Module 2)
        $todayConsultations = Consultation::with('student_medical_record')
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->get();

        // Kunin ang 5 pinakabagong medical records (Module 1)
        $recentRecords = StudentMedicalRecordClinic::latest()->take(5)->get();

        $stats = [
            'total_patients' => number_format(StudentMedicalRecordClinic::count()),
            'today_appointments' => $todayConsultations->count(),
            'pending_bills' => '12', // Static muna ito o pwedeng dagdagan sa future
            'available_doctors' => '8',
        ];

        return view('dashboard', compact('stats', 'todayConsultations', 'recentRecords'));
    }
}