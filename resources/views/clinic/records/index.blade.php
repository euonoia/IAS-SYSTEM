@extends('layouts.app')

@section('content')
<div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 shadow-2xl p-8 mb-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div>
            <h2 class="text-3xl font-bold text-white">Patient Medical Records</h2>
            <p class="text-sm text-slate-200 font-medium mt-1">Manage and view all student health profiles</p>
        </div>
        <a href="{{ route('clinic.records.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition-all flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Add New Patient
        </a>
    </div>

    {{-- Search Bar --}}
    <div class="mb-6">
        <div class="flex gap-4">
            <div class="flex-1">
                <input type="text" id="search-input" value="{{ $search ?? '' }}" placeholder="Search by student ID, name, blood type, allergies, chronic illness, medical history..." class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all" />
            </div>
            <button type="button" id="clear-search" class="bg-slate-600 hover:bg-slate-500 text-white px-6 py-3 rounded-xl font-bold transition-all duration-200 shadow-sm flex items-center gap-2 {{ empty($search) ? 'hidden' : '' }}">
                <i class="fas fa-times"></i>
                <span>Clear</span>
            </button>
        </div>
        <div id="search-results" class="text-slate-300 text-sm mt-2 {{ empty($search) ? 'hidden' : '' }}">
            <i class="fas fa-info-circle mr-1"></i>
            Showing results for: <strong id="search-term">"{{ $search ?? '' }}"</strong> (<span id="total-results">{{ $records->total() }}</span> results)
        </div>
    </div>

    <div class="bg-slate-950/80 rounded-2xl border border-slate-800 shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-blue-900/90 text-slate-100 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Student ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Blood Type</th>
                        <th class="px-6 py-4">Allergies</th>
                        <th class="px-6 py-4">Chronic Illness</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="records-table-body" class="divide-y divide-slate-800">
                    @forelse($records as $record)
                        <tr class="bg-slate-900 even:bg-slate-800/60 hover:bg-slate-800/70 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-slate-100">{{ $record->student_id }}</td>
                            <td class="px-6 py-4 text-sm text-slate-100">{{ $record->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-200">{{ $record->blood_type ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-rose-200">{{ Str::limit($record->allergies ?? 'None', 30) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-200">{{ Str::limit($record->chronic_illness ?? 'None', 30) }}</td>
                            <td class="px-6 py-4 text-right flex justify-end items-center gap-2">
                    <a href="{{ route('clinic.records.show', $record->id) }}" class="text-slate-400 hover:text-blue-600 transition-colors p-2">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('clinic.records.edit', $record->id) }}" class="text-slate-400 hover:text-amber-600 transition-colors p-2">
                        <i class="fas fa-edit"></i>
                    </a>
                    
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center text-slate-400">
                        <i class="fas fa-folder-open text-4xl mb-4"></i>
                        <p class="italic text-sm">No medical records found yet.</p>
                        <a href="{{ route('clinic.records.create') }}" class="mt-4 text-blue-600 font-bold hover:underline">Create the first record</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div id="pagination-container" class="px-6 py-4 bg-slate-900/50 border-t border-slate-800">
        @if($records->hasPages())
            {{ $records->appends(request()->query())->links() }}
        @endif
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
    const tableBody = document.getElementById('records-table-body');
    const paginationContainer = document.getElementById('pagination-container');

    let searchTimeout;

    // Function to perform search
    function performSearch(searchValue) {
        // Show loading state
        tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-spinner fa-spin text-2xl mb-4"></i>
                        <p class="text-sm">Searching...</p>
                    </div>
                </td>
            </tr>
        `;

        // Make AJAX request
        fetch(`{{ route('clinic.records.index') }}?search=${encodeURIComponent(searchValue)}`, {
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
                    <td colspan="5" class="px-6 py-12 text-center text-red-400">
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