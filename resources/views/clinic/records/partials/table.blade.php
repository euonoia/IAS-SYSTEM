                <tbody class="divide-y divide-slate-800">
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