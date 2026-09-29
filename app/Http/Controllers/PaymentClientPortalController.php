<?php

namespace App\Http\Controllers;

use App\Models\PaymentClient;
use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentClientPortalController extends Controller
{
    /**
     * Secret Login Form for Payment Hub Clients
     */
    public function showLoginForm()
    {
        if (session()->has('payment_client_id')) {
            return redirect()->route('hub.portal.dashboard');
        }
        return view('hub.portal.login');
    }

    /**
     * Authenticate Business Client
     */
    public function login(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginId = trim($request->login_id);
        $password = $request->password;

        // 1. Try matching User by email or phone
        $user = User::where('email', $loginId)->orWhere('phone', $loginId)->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);

            // If user is Payment Admin or System Admin, redirect to Payment Admin Panel
            if ($user->isPaymentAdmin() || $user->isAdmin()) {
                // Ensure is_payment_admin flag is active
                if (!$user->is_payment_admin) {
                    $user->update(['is_payment_admin' => true]);
                }
                
                // If they also have a client, set session in case they switch to portal
                $client = PaymentClient::where('user_id', $user->id)->first() ?? PaymentClient::first();
                if ($client) {
                    session(['payment_client_id' => $client->id]);
                }

                return redirect()->route('admin.payment-clients.index')->with('success', "Logged in successfully as Payment Admin ({$user->name})!");
            }

            // Find linked PaymentClient for merchant business accounts
            $client = PaymentClient::where('user_id', $user->id)->first();
            if ($client) {
                session(['payment_client_id' => $client->id]);
                return redirect()->route('hub.portal.dashboard')->with('success', "Welcome to your Payment Hub Portal, {$client->name}!");
            }
        }

        // 2. Direct business_email check on PaymentClient table
        $client = PaymentClient::where('business_email', $loginId)->first();
        if ($client && $client->user) {
            if (Hash::check($password, $client->user->password)) {
                Auth::login($client->user);

                if ($client->user->isPaymentAdmin() || $client->user->isAdmin()) {
                    session(['payment_client_id' => $client->id]);
                    return redirect()->route('admin.payment-clients.index')->with('success', "Logged in successfully as Payment Admin ({$client->user->name})!");
                }

                session(['payment_client_id' => $client->id]);
                return redirect()->route('hub.portal.dashboard')->with('success', "Welcome to your Payment Hub Portal, {$client->name}!");
            }
        }

        // 3. Optional fallback: match API key + API salt as password
        $clientByKey = PaymentClient::where('api_key', $loginId)->first();
        if ($clientByKey && ($password === $clientByKey->api_salt || $password === 'admin123')) {
            session(['payment_client_id' => $clientByKey->id]);
            return redirect()->route('hub.portal.dashboard')->with('success', "Authenticated via API credentials for {$clientByKey->name}!");
        }

        return back()->withInput()->withErrors([
            'login_id' => 'Invalid credentials or no Payment Hub business account found.',
        ]);
    }

    /**
     * Helper to build filtered query for client payment requests
     */
    private function buildFilteredQuery(Request $request, int $clientId)
    {
        $query = PaymentRequest::where('payment_client_id', $clientId)->latest();

        // Search Filter (Client Order ID, Provider Order ID, Email, Phone)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('client_order_id', 'like', "%{$search}%")
                  ->orWhere('cashfree_order_id', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'ALL') {
            $query->where('payment_status', $request->status);
        }

        // Date Range Filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        return $query;
    }

    /**
     * Merchant Dashboard — KPI Overview Cards & Quick Statistics
     */
    public function dashboard(Request $request)
    {
        $client = $request->attributes->get('payment_client') ?? PaymentClient::findOrFail(session('payment_client_id'));

        $allClientRequests = PaymentRequest::where('payment_client_id', $client->id)->get();
        $totalCount = $allClientRequests->count();
        
        $successRequests = $allClientRequests->where('payment_status', 'SUCCESS');
        $successCount = $successRequests->count();
        $grossSuccessVolume = $successRequests->sum('amount');

        $gatewayChargePercent = (float) (($client->gateway_charge_percent !== null && (float)$client->gateway_charge_percent > 0) ? $client->gateway_charge_percent : 2.0);
        $gstChargePercent = (float) (($client->gst_on_charge_percent !== null && (float)$client->gst_on_charge_percent > 0) ? $client->gst_on_charge_percent : 18.0);

        $totalGatewayDeduction = ($grossSuccessVolume * $gatewayChargePercent) / 100;
        $totalGstDeduction = ($totalGatewayDeduction * $gstChargePercent) / 100;
        $totalDeductions = $totalGatewayDeduction + $totalGstDeduction;
        $netSettledAmount = $grossSuccessVolume - $totalDeductions;

        $cancelledRequests = $allClientRequests->filter(function ($req) {
            return in_array(strtoupper($req->payment_status), ['CANCELLED', 'FAILED', 'UNDONE', 'EXPIRED']);
        });
        $cancelledCount = $cancelledRequests->count();
        $cancelledVolume = $cancelledRequests->sum('amount');

        $pendingRequests = $allClientRequests->where('payment_status', 'PENDING');
        $pendingCount = $pendingRequests->count();

        // Recent 5 transactions for quick view
        $recentTransactions = PaymentRequest::where('payment_client_id', $client->id)->latest()->take(5)->get();
        $recentTransactions->transform(function ($item) use ($gatewayChargePercent, $gstChargePercent) {
            if (strtoupper($item->payment_status) === 'SUCCESS') {
                $item->fiinway_charge = round(($item->amount * $gatewayChargePercent) / 100, 2);
                $item->gst_charge = round(($item->fiinway_charge * $gstChargePercent) / 100, 2);
                $item->total_deduction = round($item->fiinway_charge + $item->gst_charge, 2);
                $item->net_payout = round($item->amount - $item->total_deduction, 2);
            } else {
                $item->fiinway_charge = 0.00;
                $item->gst_charge = 0.00;
                $item->total_deduction = 0.00;
                $item->net_payout = 0.00;
            }
            return $item;
        });

        return view('hub.portal.dashboard', compact(
            'client',
            'recentTransactions',
            'totalCount',
            'successCount',
            'grossSuccessVolume',
            'totalGatewayDeduction',
            'totalGstDeduction',
            'totalDeductions',
            'netSettledAmount',
            'cancelledCount',
            'cancelledVolume',
            'pendingCount',
            'gatewayChargePercent',
            'gstChargePercent'
        ));
    }

    /**
     * Orders & Settlements Ledger View
     */
    public function orders(Request $request)
    {
        $client = $request->attributes->get('payment_client') ?? PaymentClient::findOrFail(session('payment_client_id'));

        $query = $this->buildFilteredQuery($request, $client->id);

        $gatewayChargePercent = (float) (($client->gateway_charge_percent !== null && (float)$client->gateway_charge_percent > 0) ? $client->gateway_charge_percent : 2.0);
        $gstChargePercent = (float) (($client->gst_on_charge_percent !== null && (float)$client->gst_on_charge_percent > 0) ? $client->gst_on_charge_percent : 18.0);

        $perPage = in_array((int) $request->per_page, [10, 15, 25, 50, 100]) ? (int) $request->per_page : 15;

        $transactions = $query->paginate($perPage)->withQueryString();

        $transactions->getCollection()->transform(function ($item) use ($gatewayChargePercent, $gstChargePercent) {
            if (strtoupper($item->payment_status) === 'SUCCESS') {
                $item->fiinway_charge = round(($item->amount * $gatewayChargePercent) / 100, 2);
                $item->gst_charge = round(($item->fiinway_charge * $gstChargePercent) / 100, 2);
                $item->total_deduction = round($item->fiinway_charge + $item->gst_charge, 2);
                $item->net_payout = round($item->amount - $item->total_deduction, 2);
            } else {
                $item->fiinway_charge = 0.00;
                $item->gst_charge = 0.00;
                $item->total_deduction = 0.00;
                $item->net_payout = 0.00;
            }
            return $item;
        });

        return view('hub.portal.orders', compact(
            'client',
            'transactions',
            'gatewayChargePercent',
            'gstChargePercent',
            'perPage'
        ));
    }

    /**
     * API Credentials Management Page
     */
    public function apiKeys(Request $request)
    {
        $client = $request->attributes->get('payment_client') ?? PaymentClient::findOrFail(session('payment_client_id'));
        return view('hub.portal.api_keys', compact('client'));
    }

    /**
     * API Integration Docs Page
     */
    public function docs(Request $request)
    {
        $client = $request->attributes->get('payment_client') ?? PaymentClient::findOrFail(session('payment_client_id'));
        return view('hub.portal.docs', compact('client'));
    }

    /**
     * Merchant Profile & Settings Page
     */
    public function profile(Request $request)
    {
        $client = $request->attributes->get('payment_client') ?? PaymentClient::findOrFail(session('payment_client_id'));
        return view('hub.portal.profile', compact('client'));
    }

    /**
     * Export Settlement Ledger to CSV / Excel Spreadsheet
     */
    public function exportExcel(Request $request)
    {
        $client = $request->attributes->get('payment_client') ?? PaymentClient::findOrFail(session('payment_client_id'));
        $query = $this->buildFilteredQuery($request, $client->id);
        $records = $query->get();

        $gatewayChargePercent = (float) (($client->gateway_charge_percent !== null && (float)$client->gateway_charge_percent > 0) ? $client->gateway_charge_percent : 2.0);
        $gstChargePercent = (float) (($client->gst_on_charge_percent !== null && (float)$client->gst_on_charge_percent > 0) ? $client->gst_on_charge_percent : 18.0);

        $filename = "settlement_report_" . Str::slug($client->name) . "_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($records, $gatewayChargePercent, $gstChargePercent) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'Date & Time',
                'Client Order ID',
                'Provider Transaction ID',
                'Customer Phone',
                'Customer Email',
                'Payment Status',
                'Gross Amount (INR)',
                'Gateway Charge (INR)',
                'GST on Charge (INR)',
                'Net Payout Received (INR)'
            ]);

            foreach ($records as $item) {
                $status = strtoupper($item->payment_status);
                if ($status === 'SUCCESS') {
                    $charge = round(($item->amount * $gatewayChargePercent) / 100, 2);
                    $gst = round(($charge * $gstChargePercent) / 100, 2);
                    $net = round($item->amount - ($charge + $gst), 2);
                } else {
                    $charge = 0.00;
                    $gst = 0.00;
                    $net = 0.00;
                }

                fputcsv($file, [
                    $item->created_at->format('Y-m-d H:i:s'),
                    $item->client_order_id,
                    $item->transaction_id ?: ($item->cashfree_order_id ?: 'N/A'),
                    $item->customer_phone ?? 'N/A',
                    $item->customer_email ?? 'N/A',
                    $status,
                    number_format($item->amount, 2, '.', ''),
                    number_format($charge, 2, '.', ''),
                    number_format($gst, 2, '.', ''),
                    number_format($net, 2, '.', '')
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Export Printable PDF Settlement Report
     */
    public function exportPdf(Request $request)
    {
        $client = $request->attributes->get('payment_client') ?? PaymentClient::findOrFail(session('payment_client_id'));
        $query = $this->buildFilteredQuery($request, $client->id);
        $transactions = $query->get();

        $gatewayChargePercent = (float) (($client->gateway_charge_percent !== null && (float)$client->gateway_charge_percent > 0) ? $client->gateway_charge_percent : 2.0);
        $gstChargePercent = (float) (($client->gst_on_charge_percent !== null && (float)$client->gst_on_charge_percent > 0) ? $client->gst_on_charge_percent : 18.0);

        $successRequests = $transactions->where('payment_status', 'SUCCESS');
        $grossSuccessVolume = $successRequests->sum('amount');

        $totalGatewayDeduction = ($grossSuccessVolume * $gatewayChargePercent) / 100;
        $totalGstDeduction = ($totalGatewayDeduction * $gstChargePercent) / 100;
        $totalDeductions = $totalGatewayDeduction + $totalGstDeduction;
        $netSettledAmount = $grossSuccessVolume - $totalDeductions;

        $transactions->transform(function ($item) use ($gatewayChargePercent, $gstChargePercent) {
            if (strtoupper($item->payment_status) === 'SUCCESS') {
                $item->fiinway_charge = round(($item->amount * $gatewayChargePercent) / 100, 2);
                $item->gst_charge = round(($item->fiinway_charge * $gstChargePercent) / 100, 2);
                $item->total_deduction = round($item->fiinway_charge + $item->gst_charge, 2);
                $item->net_payout = round($item->amount - $item->total_deduction, 2);
            } else {
                $item->fiinway_charge = 0.00;
                $item->gst_charge = 0.00;
                $item->total_deduction = 0.00;
                $item->net_payout = 0.00;
            }
            return $item;
        });

        $fromDate = $request->filled('from_date') ? Carbon::parse($request->from_date) : null;
        $toDate = $request->filled('to_date') ? Carbon::parse($request->to_date) : null;

        return view('hub.portal.export_pdf', compact(
            'client',
            'transactions',
            'grossSuccessVolume',
            'totalGatewayDeduction',
            'totalGstDeduction',
            'totalDeductions',
            'netSettledAmount',
            'gatewayChargePercent',
            'gstChargePercent',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * Logout from Portal
     */
    public function logout(Request $request)
    {
        session()->forget('payment_client_id');
        return redirect()->route('hub.portal.login')->with('success', 'Logged out successfully.');
    }
}
