<x-admin-layout>
    <x-slot name="header">Live Orders</x-slot>

    @include('admin.partials.page-styles')
    @include('admin.partials.flash')

    @include('admin.partials.page-hero', [
        'eyebrow' => 'Operations',
        'title' => 'Live Orders Board',
        'subtitle' => 'Real-time kitchen pipeline across all venues. Filter by restaurant to focus.',
        'accent' => 'cyan',
    ])

    @php
        $totalLive = collect($counts)->sum();
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
        @include('admin.partials.stat-chip', ['label' => 'Active now', 'value' => number_format($totalLive), 'tone' => 'cyan'])
        @foreach($counts as $status => $count)
            @include('admin.partials.stat-chip', ['label' => ucfirst($status), 'value' => $count, 'tone' => match($status) {
                'pending' => 'amber',
                'preparing' => 'blue',
                'ready' => 'emerald',
                'served' => 'violet',
                default => 'white',
            }])
        @endforeach
    </div>

    <div class="glass-card admin-data-panel rounded-3xl p-6 mb-6 border border-cyan-500/15">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[200px] flex-1">
                <label class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-1 block">Restaurant</label>
                <select name="restaurant_id" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white [&>option]:bg-gray-900">
                    <option value="">All venues</option>
                    @foreach($restaurants as $r)
                        <option value="{{ $r->id }}" @selected($restaurantId == $r->id)>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-fin-primary to-fin-primary-dark text-white rounded-xl font-semibold text-sm">Filter</button>
            <a href="{{ route('admin.live-orders.index') }}" class="px-5 py-2.5 bg-white/10 text-white rounded-xl text-sm border border-white/10">Clear</a>
        </form>
    </div>

    @php
        $columns = [
            'pending' => ['label' => 'Pending', 'orders' => $pendingOrders, 'titleClass' => 'text-amber-400', 'colClass' => 'admin-kanban-col--pending'],
            'preparing' => ['label' => 'Preparing', 'orders' => $preparingOrders, 'titleClass' => 'text-blue-400', 'colClass' => 'admin-kanban-col--preparing'],
            'ready' => ['label' => 'Ready', 'orders' => $readyOrders, 'titleClass' => 'text-emerald-400', 'colClass' => 'admin-kanban-col--ready'],
            'served' => ['label' => 'Served', 'orders' => $servedOrders, 'titleClass' => 'text-violet-400', 'colClass' => 'admin-kanban-col--served'],
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        @foreach($columns as $key => $col)
            <div class="glass-card admin-data-panel rounded-3xl border border-white/10 min-h-[220px] flex flex-col {{ $col['colClass'] }}">
                <div class="px-4 py-3 border-b border-white/5 flex justify-between items-center">
                    <h3 class="text-xs font-black uppercase tracking-widest {{ $col['titleClass'] }}">{{ $col['label'] }}</h3>
                    <span class="text-xs font-bold text-white/40 tabular-nums">{{ $col['orders']->count() }}</span>
                </div>
                <div class="p-3 space-y-3 flex-1 overflow-y-auto max-h-[70vh] custom-scrollbar">
                    @forelse($col['orders'] as $order)
                        <a href="{{ route('admin.orders.show', $order) }}" class="block p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 transition-all group">
                            <p class="text-sm font-bold text-white group-hover:text-violet-200">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} · Table {{ $order->table_number }}</p>
                            <p class="text-[10px] text-white/50 mt-1 truncate">{{ $order->restaurant?->name }}</p>
                            <p class="text-xs text-cyan-300 mt-2 font-semibold tabular-nums">{{ $currencySymbol }} {{ number_format($order->total_amount) }}</p>
                            @if($order->waiter)
                                <p class="text-[10px] text-white/40 mt-1">{{ $order->waiter->name }}</p>
                            @endif
                        </a>
                    @empty
                        <p class="text-center text-white/30 text-xs py-10">No orders in this stage</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-admin-layout>
