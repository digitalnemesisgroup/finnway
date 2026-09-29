<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentClient;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Get overall stats
        $totalClients = PaymentClient::where('is_active', true)->where('approval_status', 'approved')->count();
        $totalVolume = PaymentRequest::where('payment_status', 'SUCCESS')->sum('amount');
        $totalTransactions = PaymentRequest::where('payment_status', 'SUCCESS')->count();

        // Get analytics per client
        $clientStats = PaymentClient::with(['paymentRequests' => function($q) {
                $q->where('payment_status', 'SUCCESS');
            }])
            ->where('approval_status', 'approved')
            ->get()
            ->map(function ($client) {
                $salesVolume = $client->paymentRequests->sum('amount');
                $transactionCount = $client->paymentRequests->count();
                
                $gatewayCharge = ($salesVolume * $client->gateway_charge_percent) / 100;
                $gstCollected = ($gatewayCharge * $client->gst_on_charge_percent) / 100;
                $totalRevenue = $gatewayCharge + $gstCollected;

                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'legal_name' => $client->legal_name,
                    'sales_volume' => $salesVolume,
                    'transaction_count' => $transactionCount,
                    'gateway_charge' => $gatewayCharge,
                    'gst_collected' => $gstCollected,
                    'total_revenue' => $totalRevenue,
                ];
            })
            ->sortByDesc('sales_volume');

        return view('admin.payment_clients.analytics', compact('totalClients', 'totalVolume', 'totalTransactions', 'clientStats'));
    }

    public function report()
    {
        $clients = PaymentClient::where('is_active', true)->where('approval_status', 'approved')->get();
        return view('admin.payment_clients.report_form', compact('clients'));
    }

    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'payment_status' => 'required|array',
            'clients' => 'required|array',
            'report_type' => 'required|in:summary,detailed',
        ]);

        $fromDate = \Carbon\Carbon::parse($validated['from_date'])->startOfDay();
        $toDate = \Carbon\Carbon::parse($validated['to_date'])->endOfDay();

        $query = PaymentRequest::with('client')
            ->whereIn('payment_client_id', $validated['clients'])
            ->whereIn('payment_status', $validated['payment_status'])
            ->whereBetween('created_at', [$fromDate, $toDate]);

        $transactions = $query->orderBy('payment_client_id')->orderBy('created_at')->get();

        return view('admin.payment_clients.report_output', [
            'transactions' => $transactions,
            'clients' => PaymentClient::whereIn('id', $validated['clients'])->get(),
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'paymentStatus' => $validated['payment_status'],
            'includeGst' => $request->has('include_gst'),
            'includeCharge' => $request->has('include_charge'),
            'reportType' => $validated['report_type']
        ]);
    }
}
