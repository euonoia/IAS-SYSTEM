@extends('layouts.app')

@section('content')
<div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 shadow-2xl p-8 mb-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div>
            <h2 class="text-3xl font-bold text-white">Health Incident Reports</h2>
            <p class="text-sm text-slate-200 font-medium">Logs accidents, injuries, and emergencies</p>
        </div>
    
        <a href="{{ route('clinic.incidents.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl shadow-sm flex items-center gap-2 transition-all active:scale-95">
            <i class="fas fa-plus"></i> Record New Incident
        </a>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <div class="flex gap-4">
            <div class="flex-1">
                <input type="text" id="search-input" value="{{ $search ?? '' }}" 
                       placeholder="Search incidents by type, description, location, first aid, action, reporter, or student name/ID..." 
                       class="w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
            <button type="button" id="clear-search" class="bg-slate-600 hover:bg-slate-700 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition-all flex items-center gap-2 {{ empty($search) ? 'hidden' : '' }}">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>
        <div id="search-results" class="text-slate-300 text-sm mt-2 {{ empty($search) ? 'hidden' : '' }}">
            <i class="fas fa-info-circle mr-1"></i>
            Showing results for: <strong id="search-term">"{{ $search ?? '' }}"</strong> (<span id="total-results">{{ $incidents->total() }}</span> results)
        </div>
    </div>

    <div class="bg-slate-950/80 rounded-2xl border border-slate-800 shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-blue-900/90 text-slate-100 text-xs uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-6 py-4">Date & Time</th>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Type & Description</th>
                        <th class="px-6 py-4">Location</th>
                        <th class="px-6 py-4">Action/First Aid</th>
                        <th class="px-6 py-4">Reported By</th>
                    </tr>
                </thead>
                <tbody id="incidents-table-body" class="divide-y divide-slate-800">
                    @forelse($incidents as $i)
                    <tr class="bg-slate-900 even:bg-slate-800/60 hover:bg-slate-800/70 transition-colors">
                        {{-- Date & Time --}}
                        <td class="px-6 py-4 text-sm font-medium text-slate-200">
                            {{ \Carbon\Carbon::parse($i->incident_date)->format('M d, Y') }}
                            <div class="text-[10px] text-slate-400 uppercase tracking-tighter">
                                {{ \Carbon\Carbon::parse($i->incident_date)->format('h:i A') }}
                            </div>
                        </td>

                        {{-- Student Name --}}
                        <td class="px-6 py-4 font-bold text-slate-100">
                            {{ $i->student_medical_record->name ?? 'N/A' }}
                        </td>

                        {{-- Type & Description --}}
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-red-100/10 text-red-300 rounded text-[10px] font-bold border border-red-700/10 uppercase block w-fit mb-1">
                                {{ $i->incident_type }}
                            </span>
                            <p class="text-xs text-slate-400 line-clamp-2 max-w-xs">
                                {{ $i->description }}
                            </p>
                        </td>

                        {{-- Location --}}
                        <td class="px-6 py-4 text-sm text-slate-300">
                            <i class="fas fa-map-marker-alt text-slate-500 mr-1"></i> {{ $i->location }}
                        </td>

                        {{-- Action Taken & First Aid --}}
                        <td class="px-6 py-4">
                            <div class="text-xs text-blue-300 font-semibold mb-1">
                                <i class="fas fa-kit-medical mr-1"></i> {{ $i->first_aid_given }}
                            </div>
                            <span class="text-[10px] text-slate-400 italic">
                                Result: {{ $i->action_taken ?? 'No action specified' }}
                            </span>
                        </td>

                        {{-- Reported By --}}
                        <td class="px-6 py-4 text-sm text-slate-300 font-medium">
                            {{ $i->reported_by }}
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 italic">No incident reports recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($incidents->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $incidents->appends(request()->query())->links() }}
    </div>
    @endif
</div>

{{-- Real-time Search JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const clearButton = document.getElementById('clear-search');
    const searchResults = document.getElementById('search-results');
    const searchTerm = document.getElementById('search-term');
    const totalResults = document.getElementById('total-results');
    const tableBody = document.getElementById('incidents-table-body');
    const paginationContainer = document.querySelector('.mt-6.flex.justify-center');

    let searchTimeout;

    function performSearch(searchValue) {
        tableBody.innerHTML = '<tr><td colspan="7" class="px-6 py-12 text-center text-slate-400"><div class="flex flex-col items-center"><i class="fas fa-spinner fa-spin text-2xl mb-4"></i><p class="text-sm">Searching...</p></div></td></tr>';

        fetch(`{{ route('clinic.incidents.index') }}?search=${encodeURIComponent(searchValue)}`, {
            headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}
        })
        .then(response => response.json())
        .then(data => {
            tableBody.innerHTML = data.html;
            if (paginationContainer) paginationContainer.innerHTML = data.pagination;
            if (searchValue.trim()) {
                searchTerm.textContent = `"${searchValue}"`;
                totalResults.textContent = data.total;
                searchResults.classList.remove('hidden');
                clearButton.classList.remove('hidden');
            } else {
                searchResults.classList.add('hidden');
                clearButton.classList.add('hidden');
            }
            bindPaginationLinks();
        })
        .catch(error => {
            tableBody.innerHTML = '<tr><td colspan="7" class="px-6 py-12 text-center text-red-400"><div class="flex flex-col items-center"><i class="fas fa-exclamation-triangle text-2xl mb-4"></i><p class="text-sm">Search failed. Please try again.</p></div></td></tr>';
        });
    }

    function bindPaginationLinks() {
        document.querySelectorAll('.mt-6.flex.justify-center a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const searchValue = searchInput.value;
                if (searchValue) url.searchParams.set('search', searchValue);
                fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}})
                .then(response => response.json())
                .then(data => {
                    tableBody.innerHTML = data.html;
                    if (paginationContainer) paginationContainer.innerHTML = data.pagination;
                    bindPaginationLinks();
                });
            });
        });
    }

    searchInput.addEventListener('input', function() {
        const searchValue = this.value.trim();
        clearTimeout(searchTimeout);
        if (searchValue) {
            clearButton.classList.remove('hidden');
        } else {
            clearButton.classList.add('hidden');
            searchResults.classList.add('hidden');
        }
        searchTimeout = setTimeout(() => performSearch(searchValue), 300);
    });

    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        clearButton.classList.add('hidden');
        searchResults.classList.add('hidden');
        performSearch('');
    });

    bindPaginationLinks();
});
</script>
@endsection