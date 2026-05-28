<x-admin-layout>
    <x-slot name="header">Platform Overview</x-slot>

    @php
        $maxRevenue = max(collect($analytics['revenue_trend'])->max('revenue'), 1);
        $maxOrders = max(collect($analytics['orders_trend'])->max('count'), 1);
        $maxRating = max(collect($analytics['rating_distribution'])->max('count'), 1);
        $ordersTotal = collect($analytics['orders_by_status'])->sum('value');
        $revChange = $analytics['week_comparison']['revenue_change'];
        $ordChange = $analytics['week_comparison']['orders_change'];
    @endphp

    <style>
        .admin-dash-hero {
            background: linear-gradient(135deg, rgba(140, 113, 246, 0.15) 0%, rgba(109, 82, 232, 0.08) 45%, rgba(15, 10, 30, 0.95) 100%);
            border: 1px solid rgba(140, 113, 246, 0.25);
        }
        .admin-stat-glow-violet { box-shadow: 0 0 40px -12px rgba(140, 113, 246, 0.55); }
        .admin-stat-glow-cyan { box-shadow: 0 0 40px -12px rgba(109, 82, 232, 0.5); }
        .admin-stat-glow-emerald { box-shadow: 0 0 40px -12px rgba(16, 185, 129, 0.45); }
        .admin-bar-revenue {
            background: linear-gradient(to top, #4c1d95, #6D52E8 40%, #6D52E8 100%);
            border-radius: 8px 8px 0 0;
            box-shadow: 0 -6px 20px rgba(140, 113, 246, 0.35);
            transition: transform 0.25s ease, filter 0.25s ease;
        }
        .admin-bar-revenue:hover { transform: scaleY(1.02); filter: brightness(1.15); }
        .admin-bar-orders {
            background: linear-gradient(to top, #0e7490, #6D52E8 50%, #67e8f9 100%);
            border-radius: 8px 8px 0 0;
            box-shadow: 0 -6px 18px rgba(109, 82, 232, 0.35);
        }
        .admin-ring-track { stroke: rgba(255,255,255,0.06); }
        @keyframes admin-bar-grow { from { transform: scaleY(0); } to { transform: scaleY(1); } }
        .admin-bar-animate { transform-origin: bottom; animation: admin-bar-grow 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    </style>

    {{-- Hero --}}
    <div class="admin-dash-hero rounded-3xl p-6 md:p-8 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-72 h-72 bg-violet-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-56 h-56 bg-cyan-500/15 rounded-full blur-3xl translate-y-1/2 -translate-x-1/4 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            <div>
                <p class="text-[10px] font-black text-cyan-400 uppercase tracking-[0.3em] mb-2">System intelligence</p>
                <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight">Platform command center</h2>
                <p class="text-sm text-white/50 mt-2 max-w-xl">Real-time analytics across all restaurants — revenue, orders, feedback &amp; venue health.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <div class="px-4 py-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                    <p class="text-[9px] font-bold text-white/40 uppercase">This week revenue</p>
                    <p class="text-lg font-black text-white mt-0.5">{{ $currencySymbol }} {{ number_format($analytics['week_comparison']['revenue_this_week']) }}</p>
                    <p class="text-[10px] font-bold mt-1 {{ $revChange >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                        {{ $revChange >= 0 ? '↑' : '↓' }} {{ abs($revChange) }}% vs last week
                    </p>
                </div>
                <div class="px-4 py-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm flex items-center gap-3">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <div>
                        <p class="text-[9px] font-bold text-white/40 uppercase">Live orders</p>
                        <p class="text-lg font-black text-emerald-400" id="stat-active-orders">{{ $stats['active_orders'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KPI row --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
        <a href="{{ route('admin.restaurants.index') }}" class="glass-card rounded-2xl p-5 border border-violet-500/20 admin-stat-glow-violet card-hover block">
            <p class="text-[9px] font-black text-white/40 uppercase tracking-wider">Restaurants</p>
            <p class="text-xl font-black text-violet-400 mt-2 tabular-nums" id="stat-total-restaurants">{{ $stats['total_restaurants'] }}</p>
            <p class="text-[10px] text-white/40 mt-1">{{ $stats['active_restaurants'] }} active</p>
        </a>
        <a href="{{ route('admin.waiters.index') }}" class="glass-card rounded-2xl p-5 border border-amber-500/20 card-hover block">
            <p class="text-[9px] font-black text-white/40 uppercase tracking-wider">Waiters</p>
            <p class="text-xl font-black text-amber-400 mt-2 tabular-nums" id="stat-total-waiters">{{ $stats['total_waiters'] }}</p>
            <p class="text-[10px] text-white/40 mt-1">Platform-wide</p>
        </a>
        <a href="{{ route('admin.users.index') }}" class="glass-card rounded-2xl p-5 border border-blue-500/20 card-hover block">
            <p class="text-[9px] font-black text-white/40 uppercase tracking-wider">Managers</p>
            <p class="text-xl font-black text-blue-400 mt-2 tabular-nums">{{ $stats['total_managers'] }}</p>
            <p class="text-[10px] text-white/40 mt-1">Venue leads</p>
        </a>
        <a href="{{ route('admin.payments.index') }}" class="glass-card rounded-2xl p-5 border border-cyan-500/20 admin-stat-glow-cyan card-hover block">
            <p class="text-[9px] font-black text-white/40 uppercase tracking-wider">Revenue</p>
            <p class="text-xl font-black text-cyan-400 mt-2 tabular-nums" id="stat-total-revenue">{{ $currencySymbol }} {{ number_format($stats['total_revenue'] / 1000, 1) }}K</p>
            <p class="text-[10px] text-white/40 mt-1">All time</p>
        </a>
        <div class="glass-card rounded-2xl p-5 border border-emerald-500/20 admin-stat-glow-emerald">
            <p class="text-[9px] font-black text-white/40 uppercase tracking-wider">Today</p>
            <p class="text-xl font-black text-emerald-400 mt-2 tabular-nums">{{ $currencySymbol }} {{ number_format($stats['revenue_today']) }}</p>
            <p class="text-[10px] text-white/40 mt-1">Revenue</p>
        </div>
        <a href="{{ route('admin.tips.index') }}" class="glass-card rounded-2xl p-5 border border-pink-500/20 card-hover block">
            <p class="text-[9px] font-black text-white/40 uppercase tracking-wider">Tips</p>
            <p class="text-xl font-black text-pink-400 mt-2 tabular-nums">{{ $currencySymbol }} {{ number_format($stats['total_tips'] / 1000, 1) }}K</p>
            <p class="text-[10px] text-white/40 mt-1">Collected</p>
        </a>
    </div>

    {{-- Charts row: histograms + donuts --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mb-8">
        {{-- Revenue histogram --}}
        <div class="xl:col-span-5 glass-card rounded-2xl p-6 border border-violet-500/20 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-600/5 to-transparent pointer-events-none"></div>
            <div class="relative">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-black text-white">Revenue histogram</h3>
                        <p class="text-[10px] text-violet-400/80 uppercase tracking-widest mt-1">Last 7 days · {{ config('tiptap.currency_code') }}</p>
                    </div>
                    <span class="text-xs font-bold text-emerald-400 bg-emerald-500/10 px-2 py-1 rounded-lg border border-emerald-500/20">
                        {{ $revChange >= 0 ? '+' : '' }}{{ $revChange }}% WoW
                    </span>
                </div>
                <div class="h-52 flex items-end gap-2 px-1">
                    @foreach($analytics['revenue_trend'] as $i => $day)
                        @php $h = max(($day['revenue'] / $maxRevenue) * 100, $day['revenue'] > 0 ? 6 : 2); @endphp
                        <div class="flex-1 flex flex-col items-center justify-end h-full group" style="min-width:28px">
                            <div class="admin-bar-revenue admin-bar-animate w-full relative" style="height:{{ $h }}%; animation-delay:{{ $i * 0.06 }}s">
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 rounded-lg bg-black/90 border border-violet-500/40 text-[10px] text-white whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10 pointer-events-none">
                                    {{ $currencySymbol }} {{ number_format($day['revenue']) }}
                                </div>
                            </div>
                            <p class="text-[9px] font-bold text-white/50 mt-2">{{ $day['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Orders histogram --}}
        <div class="xl:col-span-4 glass-card rounded-2xl p-6 border border-cyan-500/20 relative overflow-hidden">
            <div class="relative">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-black text-white">Orders histogram</h3>
                        <p class="text-[10px] text-cyan-400/80 uppercase tracking-widest mt-1">Daily volume</p>
                    </div>
                    <span class="text-xs font-bold {{ $ordChange >= 0 ? 'text-cyan-400 bg-cyan-500/10 border-cyan-500/20' : 'text-rose-400 bg-rose-500/10 border-rose-500/20' }} px-2 py-1 rounded-lg border">
                        {{ $ordChange >= 0 ? '+' : '' }}{{ $ordChange }}% WoW
                    </span>
                </div>
                <div class="h-52 flex items-end gap-2">
                    @foreach($analytics['orders_trend'] as $i => $day)
                        @php $h = max(($day['count'] / $maxOrders) * 100, $day['count'] > 0 ? 6 : 2); @endphp
                        <div class="flex-1 flex flex-col items-center justify-end h-full group">
                            <div class="admin-bar-orders admin-bar-animate w-full" style="height:{{ $h }}%; animation-delay:{{ $i * 0.06 }}s"></div>
                            <p class="text-[9px] font-bold text-white/50 mt-2">{{ $day['label'] }}</p>
                            <p class="text-[8px] text-cyan-400/70">{{ $day['count'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Order status donut --}}
        <div class="xl:col-span-3 glass-card rounded-2xl p-6 border border-white/10">
            <h3 class="text-lg font-black text-white mb-1">Order pipeline</h3>
            <p class="text-[10px] text-white/40 uppercase tracking-widest mb-4">Circular breakdown</p>
            @include('admin.partials.donut-chart', [
                'segments' => $analytics['orders_by_status'],
                'centerLabel' => $ordersTotal,
                'centerSub' => 'Orders',
                'size' => '10rem',
            ])
        </div>
    </div>

    {{-- Second analytics row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="glass-card rounded-2xl p-6 border border-emerald-500/15">
            <h3 class="text-sm font-black text-white uppercase tracking-wider mb-4">Venue health</h3>
            @include('admin.partials.donut-chart', [
                'segments' => $analytics['restaurant_split']['segments'],
                'centerLabel' => $stats['total_restaurants'],
                'centerSub' => 'Venues',
                'size' => '9rem',
            ])
        </div>

        <div class="glass-card rounded-2xl p-6 border border-pink-500/15">
            <h3 class="text-sm font-black text-white uppercase tracking-wider mb-4">Payment mix</h3>
            @if(count($analytics['payment_methods']) > 0)
                @include('admin.partials.donut-chart', [
                    'segments' => $analytics['payment_methods'],
                    'centerLabel' => collect($analytics['payment_methods'])->sum('value'),
                    'centerSub' => 'Payments',
                    'size' => '9rem',
                ])
            @else
                <p class="text-center text-white/40 text-sm py-12">No payment data yet</p>
            @endif
        </div>

        <div class="glass-card rounded-2xl p-6 border border-amber-500/15 md:col-span-2">
            <h3 class="text-sm font-black text-white uppercase tracking-wider mb-4">Feedback distribution</h3>
            <div class="space-y-3">
                @foreach($analytics['rating_distribution'] as $row)
                    @php $w = $maxRating > 0 ? ($row['count'] / $maxRating) * 100 : 0; @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-amber-400 w-12">{{ $row['stars'] }} ★</span>
                        <div class="flex-1 h-3 rounded-full bg-white/5 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-amber-600 via-amber-400 to-yellow-300 transition-all duration-700" style="width:{{ max($w, $row['count'] > 0 ? 4 : 0) }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-white/60 w-8 text-right">{{ $row['count'] }}</span>
                    </div>
                @endforeach
            </div>
            <p class="text-center mt-4 text-2xl font-black text-amber-400">{{ $stats['avg_feedback_rating'] }} <span class="text-sm text-white/40 font-semibold">avg rating</span></p>
        </div>
    </div>

    {{-- Quick metrics strip --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('admin.withdrawals.index') }}" class="glass-card rounded-xl p-4 border border-amber-500/20 hover:border-amber-500/40 transition-all">
            <p class="text-[9px] font-bold text-white/40 uppercase">Pending withdrawals</p>
            <p class="text-2xl font-black text-amber-400 mt-1" id="stat-pending-withdrawals">{{ $stats['pending_withdrawals'] }}</p>
        </a>
        <a href="{{ route('admin.customer-requests.index') }}" class="glass-card rounded-xl p-4 border border-rose-500/20 hover:border-rose-500/40 transition-all">
            <p class="text-[9px] font-bold text-white/40 uppercase">Customer requests</p>
            <p class="text-2xl font-black text-rose-400 mt-1">{{ $stats['pending_customer_requests'] }}</p>
        </a>
        <a href="{{ route('admin.live-orders.index') }}" class="glass-card rounded-xl p-4 border border-emerald-500/20 hover:border-emerald-500/40 transition-all">
            <p class="text-[9px] font-bold text-white/40 uppercase">Orders today</p>
            <p class="text-2xl font-black text-emerald-400 mt-1">{{ $stats['orders_today'] }}</p>
        </a>
        <a href="{{ route('admin.reports.index') }}" class="glass-card rounded-xl p-4 border border-violet-500/20 hover:border-violet-500/40 transition-all">
            <p class="text-[9px] font-bold text-white/40 uppercase">Full reports</p>
            <p class="text-sm font-bold text-violet-400 mt-2">Open analytics →</p>
        </a>
    </div>

    {{-- Top restaurants + activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 glass-card rounded-2xl overflow-hidden border border-white/10">
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-gradient-to-r from-violet-600/10 to-transparent">
                <div>
                    <h3 class="text-xl font-bold text-white">Top venues by revenue</h3>
                    <p class="text-[10px] text-white/40 uppercase tracking-widest mt-1">Leaderboard</p>
                </div>
                <a href="{{ route('admin.restaurants.index') }}" class="text-xs font-bold text-violet-400 hover:text-violet-300">All venues →</a>
            </div>
            <div class="divide-y divide-white/5">
                @forelse($analytics['top_restaurants'] as $i => $venue)
                    <a href="{{ route('admin.restaurants.show', $venue['id']) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-white/5 transition-all group">
                        <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-600/30 to-cyan-600/30 flex items-center justify-center text-xs font-black text-white border border-white/10">{{ $i + 1 }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-white truncate group-hover:text-violet-300">{{ $venue['name'] }}</p>
                            <p class="text-[10px] text-white/40">{{ number_format($venue['orders']) }} orders</p>
                        </div>
                        <p class="text-sm font-black text-emerald-400 tabular-nums">{{ $currencySymbol }} {{ number_format($venue['revenue']) }}</p>
                    </a>
                @empty
                    <p class="px-6 py-12 text-center text-white/40">No venue revenue yet</p>
                @endforelse
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 border border-white/10 relative overflow-hidden">
            <div class="absolute -top-16 -right-16 w-40 h-40 bg-cyan-500/10 rounded-full blur-3xl"></div>
            <h3 class="text-lg font-bold text-white mb-4 relative">Live feed</h3>
            <div class="space-y-4 relative max-h-[320px] overflow-y-auto sidebar-nav-scroll">
                @forelse($recent_activities as $activity)
                    <div class="flex gap-3 p-3 rounded-xl bg-white/[0.03] border border-white/5">
                        <div class="w-9 h-9 rounded-lg bg-violet-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-white/90 leading-snug line-clamp-2">{{ $activity->description }}</p>
                            <p class="text-[10px] text-white/35 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-white/40 text-center py-8">No recent activity</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Newest partners --}}
    <div class="glass-card rounded-2xl overflow-hidden border border-white/10 mt-8">
        <div class="p-6 border-b border-white/5 flex justify-between items-center">
            <h3 class="text-lg font-bold text-white">Newest partners</h3>
            <a href="{{ route('admin.restaurants.create') }}" class="px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold rounded-xl transition-all">+ Add venue</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="bg-white/[0.02]">
                    <th class="px-6 py-3 text-left text-[10px] font-bold text-white/40 uppercase">Restaurant</th>
                    <th class="px-6 py-3 text-left text-[10px] font-bold text-white/40 uppercase">Location</th>
                    <th class="px-6 py-3 text-left text-[10px] font-bold text-white/40 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-[10px] font-bold text-white/40 uppercase"></th>
                </tr></thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($recent_restaurants as $restaurant)
                        <tr class="hover:bg-white/[0.02]">
                            <td class="px-6 py-4 font-semibold text-white">{{ $restaurant->name }}</td>
                            <td class="px-6 py-4 text-white/50 text-sm">{{ $restaurant->location }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-[10px] font-bold rounded-full {{ $restaurant->is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }}">{{ $restaurant->is_active ? 'Active' : 'Blocked' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.restaurants.show', $restaurant) }}" class="text-violet-400 text-sm font-semibold">View →</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        setInterval(function() {
            fetch('{{ route("admin.dashboard.stats") }}', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            })
                .then(r => r.ok ? r.json() : Promise.reject())
                .then(data => {
                    const set = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
                    set('stat-total-restaurants', data.total_restaurants);
                    set('stat-total-waiters', data.total_waiters ?? 0);
                    set('stat-active-orders', data.active_orders);
                    set('stat-pending-withdrawals', data.pending_withdrawals);
                })
                .catch(() => {});
        }, 30000);
    </script>
</x-admin-layout>
