<x-waiter-layout>
    <x-slot name="header">Salary Slip</x-slot>

    <div class="mb-8">
        <h2 class="text-3xl font-bold text-white tracking-tight">Salary History</h2>
        <p class="text-white/50 font-medium mt-1">List of payslips by month – view or download PDF.</p>
    </div>

    @if ($payments->isEmpty())
        <div class="glass-card py-16 text-center rounded-2xl border border-white/10">
            <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/5">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-white/20">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">No salary slips yet</h3>
            <p class="text-white/40">When your manager confirms payment for a month, the slip will appear here.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($payments as $payment)
                <div class="glass-card rounded-2xl p-6 border border-white/10 hover:border-white/20 transition-colors flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-white">{{ $payment->period_label }}</h3>
                        <p class="text-sm text-white/50 mt-0.5">Net Pay: {{ number_format($payment->net_pay) }}</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('waiter.salary-slip.show', $payment->period_month) }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl font-semibold text-sm transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            View
                        </a>
                        <a href="{{ route('waiter.salary-slip.download', $payment->period_month) }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-violet-600 to-cyan-600 text-white rounded-xl font-semibold text-sm hover:shadow-lg hover:shadow-violet-500/25 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                            Download
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-waiter-layout>
