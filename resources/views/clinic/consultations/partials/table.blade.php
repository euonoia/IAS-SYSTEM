                <tbody class="divide-y divide-slate-800">
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