<?php

namespace App\Http\Controllers;

use App\Models\StudentMedicalRecordClinic;
use App\Models\Consultation;
use App\Models\Medicine;
use App\Models\MedicalClearance;
use App\Models\HealthIncident;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // MODULE 2
        $todayConsultations = Consultation::with('student_medical_record')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // MODULE 1:
        $recentRecords = StudentMedicalRecordClinic::latest()->take(5)->get();

        /**
         * MODULE 3:   
         */
        $lowStockMedicines = Medicine::orderBy('stock_quantity', 'asc')
            ->take(4) 
            ->get();

        // MODULE 4: Medical Clearances 
        $medicalClearances = MedicalClearance::with('student_medical_record')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // MODULE 5: Health Incidents 
        $healthIncidents = HealthIncident::with('student_medical_record')
            ->orderBy('incident_date', 'desc')
            ->take(5)
            ->get();

        $stats = [
            'total_patients'     => number_format(StudentMedicalRecordClinic::count()),
            'today_appointments' => $todayConsultations->count(),
            
            // Overall medicine stock (sum of all stocks)
            'medicine_stocks'    => number_format(Medicine::sum('stock_quantity')),
            'pending_clearances' => MedicalClearance::where('status', 'pending')->count(),
            'recent_incidents'   => HealthIncident::whereDate('incident_date', '>=', Carbon::today()->subDays(7))->count(),
        ];

        return view('dashboard', compact(
            'stats', 
            'todayConsultations', 
            'recentRecords', 
            'lowStockMedicines',
            'medicalClearances',
            'healthIncidents'
        ));
    }
}