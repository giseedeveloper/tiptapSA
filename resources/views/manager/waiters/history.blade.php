<x-manager-layout>
    <x-slot name="header">Waiter history (Link / Unlink)</x-slot>

    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Waiter history</h2>
            <p class="text-sm font-medium text-white/40 uppercase tracking-wider mt-0.5">Waiters you linked and unlinked at your restaurant</p>
        </div>
        <a href="{{ route('manager.waiters.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 glass rounded-xl text-white hover:text-white hover:bg-white/10 transition-all text-sm font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            Back to waiters
        </a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('manager.waiters.history') }}" class="glass-card rounded-2xl p-6 mb-8 border border-white/10">
        <h3 class="text-sm font-bold text-white/60 uppercase tracking-wider mb-4">Filter</h3>
        <div class="flex flex-wrap gap-4 items-end">
            <div class="min-w-[140px]">
                <label for="filter_status" class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-1 block">Status</label>
                <select name="status" id="filter_status" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:ring-2 focus:ring-fin-primary focus:border-transparent">
                    <option value="" {{ ($filters['status'] ?? '') === '' ? 'selected' : '' }}>All</option>
                    <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Active only</option>
                    <option value="unlinked" {{ ($filters['status'] ?? '') === 'unlinked' ? 'selected' : '' }}>Unlinked only</option>
                </select>
            </div>
            <div class="flex-1 min-w-[180px]">
                <label for="filter_q" class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-1 block">Search (name or number)</label>
                <input type="text" name="q" id="filter_q" value="{{ old('q', $filters['q'] ?? '') }}" placeholder="Name or TIPTAP-W-xxxxx"
                       class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/30 text-sm focus:ring-2 focus:ring-fin-primary focus:border-transparent">
            </div>
            <div class="min-w-[140px]">
                <label for="filter_date_from" class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-1 block">Date from</label>
                <input type="date" name="date_from" id="filter_date_from" value="{{ $filters['date_from'] ?? '' }}"
                       class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:ring-2 focus:ring-fin-primary focus:border-transparent">
            </div>
            <div class="min-w-[140px]">
                <label for="filter_date_to" class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-1 block">Date to</label>
                <input type="date" name="date_to" id="filter_date_to" value="{{ $filters['date_to'] ?? '' }}"
                       class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:ring-2 focus:ring-fin-primary focus:border-transparent">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-linear-to-r from-fin-primary to-fin-primary-dark text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-fin-primary/25 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                Search
            </button>
        </div>
    </form>

    @if($assignmentHistory->isEmpty())
        <div class="glass-card py-16 text-center rounded-2xl border border-white/10">
            <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/5">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-white/20">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0 11 18 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">No results</h3>
            <p class="text-white/40">Change filters or go back to waiters for the full history.</p>
            <a href="{{ route('manager.waiters.history') }}" class="inline-block mt-4 px-5 py-2.5 bg-fin-mist text-white rounded-xl font-semibold hover:bg-white/20 transition-all">Clear filters</a>
        </div>
    @else
        <div class="glass-card rounded-2xl overflow-hidden border border-white/10">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50">Waiter</th>
                            <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50">Number</th>
                            <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50">Aina</th>
                            <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50">Linked at</th>
                            <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-white/50">Unlinked at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignmentHistory as $a)
                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                <td class="px-4 py-3 font-medium text-white">{{ $a->user?->name ?? '—' }}</td>
                                <td class="px-4 py-3 font-mono text-sm text-cyan-600">{{ $a->user?->global_waiter_number ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    @if($a->employment_type === 'temporary')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-amber-500/20 text-amber-600 border border-amber-500/30">Show-time</span>
                                        @if($a->linked_until)
                                            <span class="text-white/40 text-xs ml-1">until {{ $a->linked_until->format('d/m/Y') }}</span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-fin-mist text-white/60 border border-white/10">Long-term</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-white/60">{{ $a->linked_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    @if($a->unlinked_at)
                                        <span class="text-sm text-white/60">{{ $a->unlinked_at->format('d/m/Y H:i') }}</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-emerald-600 text-sm font-medium">
                                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                                            Active
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-manager-layout>
