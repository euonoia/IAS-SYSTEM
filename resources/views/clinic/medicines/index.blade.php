@extends('layouts.app')

@section('content')
<div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 shadow-2xl p-8 mb-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div>
            <h2 class="text-3xl font-bold text-white">Medicine Inventory</h2>
            <p class="text-sm text-slate-200 font-medium">Manage clinic supplies and monitor stock levels</p>
        </div>
        <a href="{{ route('clinic.medicines.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition-all flex items-center gap-2">
            <i class="fas fa-plus"></i> Add Medicine
        </a>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <div class="flex gap-4">
            <div class="flex-1">
                <input type="text" id="search-input" value="{{ $search ?? '' }}" 
                       placeholder="Search medicines by name, batch number, or expiration date..." 
                       class="w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <button type="button" id="clear-search" class="bg-slate-600 hover:bg-slate-700 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition-all flex items-center gap-2 {{ empty($search) ? 'hidden' : '' }}">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>
        <div id="search-results" class="text-slate-300 text-sm mt-2 {{ empty($search) ? 'hidden' : '' }}">
            <i class="fas fa-info-circle mr-1"></i>
            Showing results for: <strong id="search-term">"{{ $search ?? '' }}"</strong> (<span id="total-results">{{ $medicines->total() }}</span> results)
        </div>
    </div>

    <div class="bg-slate-950/80 rounded-2xl border border-slate-800 shadow-lg overflow-hidden text-nowrap overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-blue-900/90 text-slate-100 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">Medicine Name</th>
                    <th class="px-6 py-4">Medicine ID</th> {{-- PINALITAN: Mula Batch No. --}}
                    <th class="px-6 py-4">Current Stock</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="medicines-table-body" class="divide-y divide-slate-800">
                @forelse($medicines as $med)
                <tr class="bg-slate-900 even:bg-slate-800/60 hover:bg-slate-800/70 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-100">{{ $med->name }}</td>
                    <td class="px-6 py-4 font-mono text-blue-300 text-sm">{{ $med->batch_number }}</td> {{-- Eto yung auto-generated --}}
                    <td class="px-6 py-4 text-slate-200">{{ $med->stock_quantity }} units</td>
                    <td class="px-6 py-4">
                        @if($med->stock_quantity <= $med->low_stock_threshold)
                            <span class="px-3 py-1 bg-red-100/10 text-red-300 text-[10px] font-bold rounded-lg border border-red-700/10 uppercase">Low Stock</span>
                        @else
                            <span class="px-3 py-1 bg-emerald-100/10 text-emerald-300 text-[10px] font-bold rounded-lg border border-emerald-700/10 uppercase">Available</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end items-center gap-2">
                        <a href="{{ route('clinic.medicines.edit', $med->id) }}" class="text-slate-400 hover:text-amber-300 transition-colors p-2">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">No medicines in inventory yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="pagination-container" class="mt-6 flex justify-center">
        @if($medicines->hasPages())
            {{ $medicines->appends(request()->query())->links() }}
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
    const tableBody = document.getElementById('medicines-table-body');
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
        fetch(`{{ route('clinic.medicines.index') }}?search=${encodeURIComponent(searchValue)}`, {
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