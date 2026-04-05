@extends('layouts.app')

@section('content')
<div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 shadow-2xl p-8 mb-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Consultation History</h1>
            <p class="text-slate-200 font-medium text-sm flex items-center gap-2">
                <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                View and manage student check-up records
            </p>
        </div>
        <a href="{{ route('clinic.consultations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold transition-all duration-200 shadow-sm flex items-center gap-2 active:scale-95">
            <i class="fas fa-plus"></i>
            <span>New Consultation</span>
        </a>
    </div>

    {{-- Search Bar --}}
    <div class="mb-6">
        <div class="flex gap-4">
            <div class="flex-1">
                <input type="text" id="search-input" value="{{ $search ?? '' }}" placeholder="Search by student ID, name, symptoms, diagnosis, treatment, or medicine..." class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all" />
            </div>
            <button type="button" id="clear-search" class="bg-slate-600 hover:bg-slate-500 text-white px-6 py-3 rounded-xl font-bold transition-all duration-200 shadow-sm flex items-center gap-2 {{ empty($search) ? 'hidden' : '' }}">
                <i class="fas fa-times"></i>
                <span>Clear</span>
            </button>
        </div>
        <div id="search-results" class="text-slate-300 text-sm mt-2 {{ empty($search) ? 'hidden' : '' }}">
            <i class="fas fa-info-circle mr-1"></i>
            Showing results for: <strong id="search-term">"{{ $search ?? '' }}"</strong> (<span id="total-results">{{ $consultations->total() }}</span> results)
        </div>
    </div>

    <div class="bg-slate-950/80 rounded-2xl border border-slate-800 shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-900/90 text-slate-100">
                        <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest">Date & Time</th>
                        <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest">Student Info</th>
                        <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest">Diagnosis</th>
                        <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-center">Medicines</th>
                        <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="consultations-table-body" class="divide-y divide-slate-800">
                    @forelse($consultations as $item)
                    <tr class="bg-slate-900 even:bg-slate-800/60 hover:bg-slate-800/70 transition-colors group">
                        {{-- Date & Time --}}
                        <td class="px-8 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-100">{{ $item->created_at->format('M d, Y') }}</span>
                                <span class="text-[11px] text-slate-400 font-medium">{{ $item->created_at->format('h:i A') }}</span>
                            </div>
                        </td>

                        {{-- Student Name & ID --}}
                        <td class="px-8 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-white uppercase tracking-tight">
                                    {{ $item->student_medical_record->name ?? 'Unknown Student' }}
                                </span>
                                <span class="text-[11px] font-bold text-blue-400">
                                    ID: {{ $item->student_medical_record->student_id ?? 'N/A' }}
                                </span>
                            </div>
                        </td>

                        {{-- Diagnosis --}}
                        <td class="px-8 py-5 text-sm text-slate-300 font-medium">
                            {{ Str::limit($item->diagnosis, 50) }}
                        </td>

                        {{-- Medicines Used --}}
                        <td class="px-8 py-5 text-center">
                            @if($item->medicine)
                                <span class="px-3 py-1 bg-blue-100/10 text-blue-300 text-[10px] font-bold rounded-full border border-blue-700/10">
                                    <i class="fas fa-pills mr-1"></i> {{ $item->medicine->name }} ({{ $item->quantity_used }})
                                </span>
                            @else
                                <span class="text-slate-400 text-xs italic">None</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('clinic.consultations.show', $item->id) }}" class="p-2 text-slate-400 hover:text-blue-300 hover:bg-blue-900/40 rounded-xl transition-all" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                {{-- Added Delete Button for functionality --}}
                                <form action="{{ route('clinic.consultations.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-900/40 rounded-xl transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center text-slate-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-notes-medical text-5xl mb-4 opacity-20"></i>
                                <p class="italic text-sm">No consultation records found yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div id="pagination-container" class="px-8 py-4 bg-slate-900/50 border-t border-slate-800">
            @if($consultations->hasPages())
                {{ $consultations->appends(request()->query())->links() }}
            @endif
        </div>
    </div>
</div>

{{-- Real-time Search JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const clearButton = document.getElementById('clear-search');
    const searchResults = document.getElementById('search-results');
    const searchTerm = document.getElementById('search-term');
    const totalResults = document.getElementById('total-results');
    const tableBody = document.getElementById('consultations-table-body');
    const paginationContainer = document.getElementById('pagination-container');

    let searchTimeout;

    // Function to perform search
    function performSearch(searchValue) {
        // Show loading state
        tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="px-8 py-20 text-center text-slate-400">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-spinner fa-spin text-2xl mb-4"></i>
                        <p class="text-sm">Searching...</p>
                    </div>
                </td>
            </tr>
        `;

        // Make AJAX request
        fetch(`{{ route('clinic.consultations.index') }}?search=${encodeURIComponent(searchValue)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update table content
            tableBody.innerHTML = data.html;

            // Update pagination
            paginationContainer.innerHTML = data.pagination;

            // Update search results info
            if (searchValue.trim()) {
                searchTerm.textContent = `"${searchValue}"`;
                totalResults.textContent = data.total;
                searchResults.classList.remove('hidden');
                clearButton.classList.remove('hidden');
            } else {
                searchResults.classList.add('hidden');
                clearButton.classList.add('hidden');
            }

            // Re-bind pagination links for AJAX
            bindPaginationLinks();
        })
        .catch(error => {
            console.error('Search error:', error);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center text-red-400">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-exclamation-triangle text-2xl mb-4"></i>
                            <p class="text-sm">Search failed. Please try again.</p>
                        </div>
                    </td>
                </tr>
            `;
        });
    }

    // Function to bind pagination links for AJAX
    function bindPaginationLinks() {
        const paginationLinks = paginationContainer.querySelectorAll('a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const searchValue = searchInput.value;
                if (searchValue) {
                    url.searchParams.set('search', searchValue);
                }
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    tableBody.innerHTML = data.html;
                    paginationContainer.innerHTML = data.pagination;
                    bindPaginationLinks(); // Re-bind for new pagination
                });
            });
        });
    }

    // Search input event listener with debounce
    searchInput.addEventListener('input', function() {
        const searchValue = this.value.trim();

        // Clear previous timeout
        clearTimeout(searchTimeout);

        // Show/hide clear button
        if (searchValue) {
            clearButton.classList.remove('hidden');
        } else {
            clearButton.classList.add('hidden');
            searchResults.classList.add('hidden');
        }

        // Debounce search requests
        searchTimeout = setTimeout(() => {
            performSearch(searchValue);
        }, 300); // 300ms delay
    });

    // Clear search button
    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        clearButton.classList.add('hidden');
        searchResults.classList.add('hidden');
        performSearch('');
    });

    // Initial binding of pagination links
    bindPaginationLinks();
});
</script>
@endsection