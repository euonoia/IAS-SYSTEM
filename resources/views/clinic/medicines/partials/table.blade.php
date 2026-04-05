            <tbody class="divide-y divide-slate-800">
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