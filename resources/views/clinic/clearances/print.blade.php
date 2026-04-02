<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Certificate - {{ $clearance->clearance_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap');
        
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; padding: 0 !important; }
            .print-container { border: none !important; shadow: none !important; margin: 0 !important; width: 100% !important; }
        }

        .cert-border {
            border: 10px double #1e293b;
            padding: 40px;
        }

        body {
            font-family: 'Times New Roman', serif;
        }
    </style>
</head>
<body class="bg-slate-100 p-10">

    <div class="max-w-4xl mx-auto mb-6 no-print flex justify-between items-center">
        <a href="{{ route('clinic.clearances.index') }}" class="text-slate-600 font-bold flex items-center gap-2 hover:text-indigo-600 transition-colors">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <button onclick="window.print()" class="bg-indigo-600 text-white px-6 py-2 rounded-full shadow-lg hover:bg-indigo-700 font-bold transition-all flex items-center gap-2">
            <i class="fas fa-print"></i> Print Certificate
        </button>
    </div>

    <div class="print-container max-w-4xl mx-auto bg-white shadow-2xl overflow-hidden relative cert-border">
        
        <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none">
            <i class="fas fa-file-medical fa-[20rem]"></i>
        </div>

        <div class="relative z-10">
            <div class="text-center mb-10 border-b-2 border-slate-800 pb-6">
                <h1 class="text-2xl font-bold uppercase tracking-widest text-slate-900">Republic of the Philippines</h1>
                <h2 class="text-xl font-semibold uppercase text-slate-800">Your School Name Here</h2>
                <p class="text-sm italic text-slate-600">Health and Medical Services Department</p>
                <p class="text-xs mt-1 text-slate-500">School Street Address, City, Province</p>
            </div>

            <div class="text-center mb-12">
                <h3 class="text-4xl font-bold uppercase underline tracking-tighter text-slate-900">Medical Certificate</h3>
                <p class="mt-4 text-slate-500 font-mono font-bold">Clearance No: <span class="text-indigo-600">{{ $clearance->clearance_number }}</span></p>
            </div>

            <div class="flex justify-end mb-8">
                <p class="font-bold text-slate-800">Date: <span class="underline">{{ $clearance->issued_date ? \Carbon\Carbon::parse($clearance->issued_date)->format('F d, Y') : now()->format('F d, Y') }}</span></p>
            </div>

            <div class="text-lg text-slate-800 leading-loose mb-16 px-6 text-justify">
                <p>To whom it may concern,</p>
                <br>
                <p>
                    This is to certify that <strong>{{ $clearance->student_medical_record->name ?? '____________________' }}</strong>, 
                    with Student ID No. <strong>{{ $clearance->student_medical_record->student_id ?? '__________' }}</strong>, 
                    has undergone medical evaluation in this clinic.
                </p>
                <p>
                    Based on the physical examination and medical history provided, the patient is found to be in stable condition and is cleared for:
                </p>
                <div class="my-6 p-4 bg-slate-50 border-l-4 border-indigo-600 font-bold italic text-center text-xl">
                    "{{ $clearance->purpose }}"
                </div>
                
                <p class="mt-8 font-bold">Medical Remarks:</p>
                <p class="italic text-slate-700">
                    {{ $clearance->remarks ?? 'The patient is fit for the above-mentioned purpose. No significant findings noted at the time of examination.' }}
                </p>
            </div>

            <div class="mt-24 grid grid-cols-2 gap-12">
                <div class="text-center pt-10">
                    <div class="w-48 border-b border-slate-900 mx-auto"></div>
                    <p class="text-xs mt-1 font-bold">Student Signature</p>
                </div>
                <div class="text-center pt-10">
                    <div class="w-64 border-b-2 border-slate-900 mx-auto font-bold text-slate-900 uppercase">
                        Dr. Aris
                    </div>
                    <p class="text-sm font-bold uppercase mt-1">School Physician</p>
                    <p class="text-xs text-slate-500 italic">License No. 123456789</p>
                </div>
            </div>

            <div class="mt-20 flex justify-between items-end border-t border-slate-100 pt-6">
                <p class="text-[10px] text-slate-400 font-mono">Verified by: RXCEL CLINIC SYSTEM</p>
                <p class="text-[10px] text-slate-400 font-mono uppercase">This is a system-generated document.</p>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Optional: window.print();
        }
    </script>
</body>
</html>