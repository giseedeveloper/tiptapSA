<x-manager-layout>
    <x-slot name="header">Help & Documentation</x-slot>

    <div class="mb-8">
        <h2 class="text-3xl font-bold text-white tracking-tight">User Guide</h2>
        <p class="text-white/50 font-medium mt-1">How to use the Payroll system and confirm waiter payments.</p>
    </div>

    <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-6">
        <section>
            <h3 class="text-lg font-bold text-white mb-3">Payroll Management</h3>
            <ol class="list-decimal list-inside space-y-2 text-white text-sm">
                <li>Go to the <strong class="text-white">Payroll</strong> menu in the sidebar (under Finance).</li>
                <li>Select the <strong class="text-white">month</strong> you want to process (dropdown at the top right).</li>
                <li>For each waiter, fill in the amounts: <strong class="text-white">Basic Salary</strong>, <strong class="text-white">Allowances</strong>, <strong class="text-white">PAYE</strong>, <strong class="text-white">NSSF</strong>.</li>
                <li>Click <strong class="text-white">Confirm Payment</strong> after paying the waiter (cash).</li>
                <li>The waiter will receive a notification on their dashboard and can view and download their salary slip for that month.</li>
            </ol>
        </section>
        <section>
            <h3 class="text-lg font-bold text-white mb-3">Payment History</h3>
            <p class="text-white text-sm">The <strong class="text-white">Payment History</strong> page shows all payments you've confirmed for each month, total gross and net amounts, and you can print or export (CSV) for records and tax purposes.</p>
        </section>
    </div>
</x-manager-layout>
