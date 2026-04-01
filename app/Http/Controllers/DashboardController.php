<?php

namespace App\Http\Controllers;

use App\Models\StudentMedicalRecordClinic;
use App\Models\Consultation;
use App\Models\Medicine; 
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // MODULE 2: Kunin ang mga konsultasyon ngayong araw
        $todayConsultations = Consultation::with('student_medical_record')
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->get();

        // MODULE 1: Kunin ang 5 pinakabagong medical records
        $recentRecords = StudentMedicalRecordClinic::latest()->take(5)->get();

        // MODULE 3: Kunin ang mga gamot para sa Stock Monitor table
        // Kukunin natin ang mga paubos na o kahit anong 5 items para may laman ang maliit na table
        $lowStockMedicines = Medicine::orderBy('stock_quantity', 'asc')
            ->take(5)
            ->get();

        $stats = [
            'total_patients'     => number_format(StudentMedicalRecordClinic::count()),
            'today_appointments' => $todayConsultations->count(),
            
            // ETO YUNG DAGDAG: Kabuuang bilang ng lahat ng gamot (units)
            'medicine_stocks'    => number_format(Medicine::sum('stock_quantity')), 
        ];

        return view('dashboard', compact(
            'stats', 
            'todayConsultations', 
            'recentRecords', 
            'lowStockMedicines'
        ));
    }
}