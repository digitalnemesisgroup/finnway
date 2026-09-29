@extends('hub.portal.layout')

@section('title', 'Generate Custom Report')
@section('header_title', 'Payment Hub — Custom Report Generator')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto" x-data="reportForm()">

    <!-- ── Page Banner Header ─────────────────────────────────────────────────── -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 rounded-2xl p-6 md:p-8 text-white shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border border-slate-700/60">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wider uppercase bg-blue-500/20 text-blue-300 border border-blue-500/30">
                    System Control Panel
                </span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">Custom Report Generator</h1>
            <p class="text-xs md:text-sm text-slate-300 mt-1">Configure and generate printable PDFs, summary breakdowns, or multi-sheet Excel reports.</p>
        </div>
        <a href="{{ route('admin.payment-analytics.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition flex items-center gap-2 border border-slate-600">
            <i class="ri-arrow-left-line"></i> Back to Analytics
        </a>
    </div>

    <!-- ── Report Configuration Card ─────────────────────────────────────────── -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="ri-settings-4-line text-blue-600 text-lg"></i>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Report Configuration Parameters</h2>
            </div>
            <span class="text-xs text-slate-400 font-medium">* Required Fields</span>
        </div>
        
        <form action="{{ route('admin.payment-analytics.report.generate') }}" method="POST" target="_blank" class="p-6 md:p-8 space-y-6">
            @csrf
            
            <!-- Date & Filter Controls Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- From Date -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        From Date <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="date" name="from_date" required value="{{ now()->subMonth()->toDateString() }}"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 font-medium focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition outline-none">
                    </div>
                </div>

                <!-- To Date -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        To Date <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="date" name="to_date" required value="{{ now()->toDateString() }}"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 font-medium focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition outline-none">
                    </div>
                </div>

                <!-- Payment Status Multi-Select -->
                <div class="relative">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Payment Status <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative" @click.away="statusOpen = false">
                        <button type="button" @click="statusOpen = !statusOpen" 
                            class="w-full text-left bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 flex justify-between items-center transition">
                            <span x-text="selectedStatuses.length ? selectedStatuses.length + ' Statuses Selected' : 'Select Statuses'" class="truncate"></span>
                            <i class="ri-arrow-down-s-line text-slate-400 text-base"></i>
                        </button>
                        
                        <div x-show="statusOpen" class="absolute z-20 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto p-2" x-cloak x-transition>
                            <input type="text" x-model="statusSearch" placeholder="Search status..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 mb-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                            
                            <template x-for="status in filteredStatuses" :key="status.value">
                                <label class="flex items-center gap-2.5 px-2.5 py-2 hover:bg-slate-50 rounded-lg cursor-pointer transition">
                                    <input type="checkbox" name="payment_status[]" :value="status.value" x-model="selectedStatuses" class="text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                                    <span class="text-xs font-semibold text-slate-700" x-text="status.label"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Business Clients Multi-Select -->
                <div class="relative">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Target Businesses / Clients <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative" @click.away="clientOpen = false">
                        <button type="button" @click="clientOpen = !clientOpen" 
                            class="w-full text-left bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 flex justify-between items-center transition">
                            <span x-text="selectedClients.length ? selectedClients.length + ' Businesses Selected' : 'Select Businesses'" class="truncate"></span>
                            <i class="ri-arrow-down-s-line text-slate-400 text-base"></i>
                        </button>
                        
                        <div x-show="clientOpen" class="absolute z-20 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto p-2" x-cloak x-transition>
                            <input type="text" x-model="clientSearch" placeholder="Search business..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 mb-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                            
                            <div class="flex items-center justify-between px-2 py-1 border-b border-slate-100 mb-1">
                                <button type="button" @click="selectAllClients()" class="text-xs font-bold text-blue-600 hover:underline">Select All</button>
                                <button type="button" @click="selectedClients = []" class="text-xs font-bold text-rose-600 hover:underline">Clear All</button>
                            </div>

                            <template x-for="client in filteredClients" :key="client.id">
                                <label class="flex items-center gap-2.5 px-2.5 py-2 hover:bg-slate-50 rounded-lg cursor-pointer transition">
                                    <input type="checkbox" name="clients[]" :value="client.id" x-model="selectedClients" class="text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                                    <span class="text-xs font-semibold text-slate-700" x-text="client.name"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Optional Additional Metrics Banner -->
            <div class="p-4 bg-emerald-50/80 border border-emerald-200/80 rounded-xl" x-show="hasSuccessSelected" x-transition>
                <p class="text-xs font-bold text-emerald-900 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                    <i class="ri-checkbox-circle-line text-emerald-600 text-sm"></i> Metrics Inclusion (For Successful Payments)
                </p>
                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="include_gst" value="1" checked class="text-emerald-600 rounded border-slate-300 w-4 h-4 focus:ring-emerald-500">
                        <span class="text-xs font-semibold text-slate-700">Include GST Collected (18%)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="include_charge" value="1" checked class="text-emerald-600 rounded border-slate-300 w-4 h-4 focus:ring-emerald-500">
                        <span class="text-xs font-semibold text-slate-700">Include Platform Gateway Charge %</span>
                    </label>
                </div>
            </div>

            <!-- Report Format / Type Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">
                    Report Display Type <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="relative flex items-start p-4 bg-slate-50 hover:bg-slate-100/70 border border-slate-200 rounded-xl cursor-pointer transition">
                        <input type="radio" name="report_type" value="summary" required checked class="mt-0.5 text-blue-600 border-slate-300 w-4 h-4 focus:ring-blue-500">
                        <div class="ml-3">
                            <span class="block text-xs font-bold text-slate-800">Summary Breakdown</span>
                            <span class="block text-[11px] text-slate-500 mt-0.5">Aggregated financial metrics, transaction totals, and GST per business entity in a single matrix table.</span>
                        </div>
                    </label>
                    <label class="relative flex items-start p-4 bg-slate-50 hover:bg-slate-100/70 border border-slate-200 rounded-xl cursor-pointer transition">
                        <input type="radio" name="report_type" value="detailed" required class="mt-0.5 text-blue-600 border-slate-300 w-4 h-4 focus:ring-blue-500">
                        <div class="ml-3">
                            <span class="block text-xs font-bold text-slate-800">Detailed Transaction Ledger</span>
                            <span class="block text-[11px] text-slate-500 mt-0.5">Full order-level details with printable per-company page breaks and multi-tab Excel export options.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit Button Footer -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-blue-600/30 cursor-pointer">
                    <i class="ri-file-chart-line text-sm"></i> Generate & View Report
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('reportForm', () => ({
        statusOpen: false,
        statusSearch: '',
        statuses: [
            { value: 'success', label: 'Success' },
            { value: 'pending', label: 'Pending' },
            { value: 'failed', label: 'Failed' },
            { value: 'user_dropped', label: 'User Dropped' }
        ],
        selectedStatuses: ['success'],

        clientOpen: false,
        clientSearch: '',
        clients: @json($clients->map(fn($c) => ['id' => (string)$c->id, 'name' => $c->name])),
        selectedClients: @json($clients->pluck('id')->map(fn($id) => (string)$id)),

        get filteredStatuses() {
            return this.statuses.filter(s => s.label.toLowerCase().includes(this.statusSearch.toLowerCase()));
        },

        get filteredClients() {
            return this.clients.filter(c => c.name.toLowerCase().includes(this.clientSearch.toLowerCase()));
        },

        get hasSuccessSelected() {
            return this.selectedStatuses.includes('success');
        },

        selectAllClients() {
            this.selectedClients = this.clients.map(c => c.id);
        }
    }));
});
</script>
@endsection

