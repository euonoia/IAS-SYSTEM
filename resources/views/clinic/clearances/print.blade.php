<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Certificate - {{ $clearance->clearance_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;700&display=swap');
        
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; padding: 0 !important; }
            .print-container { 
                border: none !important; 
                box-shadow: none !important; 
                margin: 0 !important; 
                width: 100% !important;
                padding: 20px !important;
            }
            .cert-border { border: 5px double #1e293b !important; }
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .cert-title {
            font-family: 'Cinzel', serif;
        }

        .cert-body {
            font-family: 'Playfair Display', serif;
            line-height: 2;
        }

        .cert-border {
            border: 12px double #1e293b;
            position: relative;
        }

        /* Watermark style */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 8rem;
            color: rgba(30, 41, 59, 0.03);
            pointer-events: none;
            z-index: 0;
            white-space: nowrap;
            font-weight: 900;
            text-transform: uppercase;
        }
    </style>
</head>
<body class="bg-slate-200 py-12 px-4">

    {{-- NAVIGATION BAR (HIDDEN ON PRINT) --}}
    <div class="max-w-4xl mx-auto mb-8 no-print flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-slate-300">
        <a href="{{ route('clinic.clearances.index') }}" class="text-slate-600 font-bold flex items-center gap-2 hover:text-blue-600 transition-all">
            <i class="fas fa-chevron-left"></i> Return to Records
        </a>
        <div class="flex gap-3">
            <button onclick="window.print()" class="bg-blue-600 text-white px-8 py-2.5 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 font-bold transition-all flex items-center gap-2 active:scale-95">
                <i class="fas fa-print"></i> Print Document
            </button>
        </div>
    </div>

    {{-- CERTIFICATE CONTAINER --}}
    <div class="print-container max-w-4xl mx-auto bg-white shadow-2xl p-12 relative cert-border min-h-[1000px]">
        
        {{-- WATERMARK --}}
        <div class="watermark cert-title">RXCEL CLINIC</div>

        <div class="relative z-10">
            {{-- HEADER --}}
            <div class="text-center mb-10 border-b-4 border-double border-slate-800 pb-8">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 rounded-xl overflow-hidden shadow-lg">
                        <img src="{{ asset('images/cropped.PNG') }}" alt="Rxcel Logo" class="w-full h-full object-cover">
                    </div>
                </div>
                <h1 class="text-xl font-bold uppercase tracking-[0.2em] text-slate-900">Republic of the Philippines</h1>
                <h2 class="text-2xl font-black uppercase text-slate-800 tracking-tight mt-1">Rxcel</h2>
                <p class="text-sm font-bold text-blue-600 uppercase tracking-widest mt-1">Office of the School Clinic</p>
                <p class="text-[10px] mt-2 text-slate-500 uppercase font-medium">123 Education St., Academic City, Philippines 1000</p>
            </div>

            {{-- DOCUMENT TITLE --}}
            <div class="text-center mb-12">
                <h3 class="cert-title text-5xl font-bold text-slate-900 mb-2">Medical Certificate</h3>
                <div class="w-48 h-1 bg-slate-800 mx-auto mb-4"></div>
                <p class="text-slate-500 font-mono text-sm font-bold uppercase">No: <span class="text-blue-600">{{ $clearance->clearance_number }}</span></p>
            </div>

            {{-- DATE --}}
            <div class="flex justify-end mb-12">
                <div class="text-right border-b-2 border-slate-100 pb-2 px-4">
                    <p class="text-xs uppercase font-bold text-slate-400 mb-1">Date of Issuance</p>
                    <p class="font-bold text-slate-900 text-lg italic">
                        {{ $clearance->issued_date ? \Carbon\Carbon::parse($clearance->issued_date)->format('jS \o\f F, Y') : now()->format('jS \o\f F, Y') }}
                    </p>
                </div>
            </div>

            {{-- CONTENT --}}
            <div class="cert-body text-xl text-slate-800 px-8 text-justify mb-20">
                <p class="mb-8 font-bold text-2xl italic">To Whom It May Concern:</p>
                
                <p class="indent-12">
                    This is to officially certify that 
                    <span class="font-bold text-slate-900 border-b border-slate-400 px-2 uppercase tracking-wide">{{ $clearance->student_medical_record->name ?? '____________________' }}</span>, 
                    carrying Student ID No. <span class="font-bold">{{ $clearance->student_medical_record->student_id ?? '__________' }}</span>, 
                    has been thoroughly evaluated by the undersigned school physician.
                </p>

                <p class="mt-8">
                    Following a comprehensive review of the physical examination results and the patient's medical history, the student is found to be in satisfactory health and is hereby <strong>CLEARED</strong> for:
                </p>

                <div class="my-10 p-8 bg-slate-50 border-y-2 border-slate-200 font-black italic text-center text-2xl text-blue-800 uppercase tracking-wide">
                    "{{ $clearance->purpose }}"
                </div>
                
                <div class="mt-12 bg-slate-50/50 p-6 rounded-xl border border-dashed border-slate-300">
                    <p class="text-sm font-bold text-slate-500 uppercase mb-2">Physician's Remarks & Recommendations:</p>
                    <p class="italic text-slate-700 text-lg leading-relaxed">
                        {{ $clearance->remarks ?? 'Patient is fit for the stated activity. No significant medical restrictions are noted at the time of clinical assessment.' }}
                    </p>
                </div>
            </div>

            {{-- SIGNATURES --}}
            <div class="mt-32 grid grid-cols-2 gap-20">
                <div class="text-center">
                    <div class="h-16 flex items-end justify-center">
                        {{-- Placeholder for student signature --}}
                    </div>
                    <div class="w-full border-t-2 border-slate-900 mx-auto"></div>
                    <p class="text-xs font-bold uppercase tracking-widest mt-2">Patient's Signature</p>
                </div>
                
                <div class="text-center">
                    <div class="h-16 flex items-end justify-center">
                         <p class="cert-title text-2xl text-blue-900 italic font-bold">Dr. Aris Rodriguez, MD</p>
                    </div>
                    <div class="w-full border-t-2 border-slate-900 mx-auto"></div>
                    <p class="text-sm font-bold uppercase tracking-widest mt-2">School Physician</p>
                    <p class="text-[10px] text-slate-500 font-medium uppercase mt-1 tracking-tighter">PRC License No. 0012345678</p>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="absolute bottom-4 left-12 right-12 flex justify-between items-end border-t border-slate-100 pt-6 no-print-footer">
                <div class="flex items-center gap-2">
                    <i class="fas fa-shield-alt text-blue-200 text-xl"></i>
                    <p class="text-[9px] text-slate-400 font-mono tracking-tighter uppercase italic">Securely Verified by Rxcel Clinic System</p>
                </div>
                <p class="text-[9px] text-slate-400 font-mono uppercase tracking-tighter">System Generated • Non-transferable Document</p>
            </div>
        </div>
    </div>

    {{-- AUTO PRINT TRIGGER (OPTIONAL) --}}
    <script>
        // Alisin ang comment sa baba kung gusto mong mag-print agad pagka-load
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>