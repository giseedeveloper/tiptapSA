<x-waiter-layout>
    <x-slot name="header">Profile</x-slot>

    <div class="max-w-xl mx-auto py-8">
        <div class="glass-card rounded-2xl p-8 text-center border border-white/10">
            <div class="w-20 h-20 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-violet-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-violet-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Not linked to a restaurant</h2>
            <p class="text-white/60 mb-6">Your restaurant manager will link you using your unique number. After linking you will see your dashboard and QR code.</p>

            <div class="bg-white/5 rounded-xl p-6 border border-white/10 mb-6">
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider mb-2">Your unique number (Waiter Code)</p>
                <p class="text-2xl font-mono font-bold text-cyan-400 tracking-wide">{{ Auth::user()->global_waiter_number }}</p>
                <p class="text-xs text-white/40 mt-2">Give this number to a manager so they can link you to their restaurant.</p>
                <button type="button" onclick="navigator.clipboard.writeText('{{ Auth::user()->global_waiter_number }}'); this.textContent='Imeigwa!'; this.classList.add('!bg-emerald-600'); setTimeout(() => { this.textContent='Copy'; this.classList.remove('!bg-emerald-600'); }, 2000)"
                        class="mt-4 px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold rounded-xl transition-all duration-200">
                    Copy
                </button>
            </div>

            <div class="text-left bg-white/5 rounded-xl p-4 border border-white/5 text-sm text-white/70">
                <p class="font-semibold text-white/90 mb-2">Hatua zinazofuata:</p>
                <ol class="list-decimal list-inside space-y-1">
                    <li>Go to the manager at the restaurant where you want to work.</li>
                    <li>Give them your unique number: <strong class="text-cyan-400">{{ Auth::user()->global_waiter_number }}</strong></li>
                    <li>The manager will link you in the system (Search → Link waiter).</li>
                    <li>Refresh this page (F5) or sign in again – you will see your dashboard and QR code.</li>
                </ol>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-8">
                @csrf
                <button type="submit" class="text-white/50 hover:text-rose-400 text-sm font-medium transition-colors">
                    Sign out
                </button>
            </form>
        </div>
    </div>
</x-waiter-layout>
