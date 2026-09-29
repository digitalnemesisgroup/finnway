<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentClientController extends Controller
{
    public function index()
    {
        // Auto-disable any keys that have passed their expiry date
        PaymentClient::where('is_active', true)
            ->where('approval_status', 'approved')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['is_active' => false]);

        $pendingClients = PaymentClient::where('approval_status', 'pending')->latest()->get();
        $clients = PaymentClient::where('approval_status', '!=', 'pending')->latest()->get();
        return view('admin.payment_clients.index', compact('clients', 'pendingClients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'legal_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'pan_number' => 'required|string|max:255',
            'business_email' => 'required|email|max:255',
        ]);

        PaymentClient::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'legal_name' => $request->legal_name,
            'business_type' => $request->business_type,
            'pan_number' => $request->pan_number,
            'business_email' => $request->business_email,
            'approval_status' => 'pending',
            'is_active' => false,
            'expected_monthly_volume' => 'Unknown',
            'expected_avg_value' => 'Unknown',
            'purpose' => 'Manually added by Admin',
            'website_url' => 'https://' . Str::slug($request->name) . '.com',
            'registered_address' => 'Pending Verification',
            'business_mobile' => '0000000000',
            'category' => 'Other',
        ]);

        return back()->with('success', 'Business application added and is now pending approval.');
    }


    public function toggle(PaymentClient $client)
    {
        // Don't allow toggling the internal client
        if ($client->id === 1) {
            return back()->with('error', 'Cannot disable the internal marketplace client.');
        }

        $client->update(['is_active' => !$client->is_active]);

        $status = $client->is_active ? 'activated' : 'made dormant';
        return back()->with('success', "API Client has been {$status}.");
    }

    public function destroy(PaymentClient $client)
    {
        if ($client->id === 1) {
            return back()->with('error', 'Cannot delete the internal marketplace client.');
        }

        $client->delete();

        return back()->with('success', 'API Client deleted successfully.');
    }

    public function approve(Request $request, PaymentClient $client)
    {
        $request->validate([
            'gateway_charge_percent' => 'required|numeric|min:0|max:100',
            'gst_on_charge_percent'  => 'required|numeric|min:0|max:100',
            'starts_at'              => 'required|date',
            'expires_at'             => 'required|date|after:starts_at',
        ]);

        $client->update([
            'gateway_charge_percent' => $request->gateway_charge_percent,
            'gst_on_charge_percent'  => $request->gst_on_charge_percent,
            'starts_at'              => $request->starts_at,
            'expires_at'             => $request->expires_at,
            'approval_status'        => 'approved',
            'is_active'              => true,
            'api_key'                => 'pk_' . Str::random(24),
            'api_salt'               => Str::random(64),
        ]);

        return back()->with('success', 'Application approved, API keys generated, and validity set.');
    }

    public function reject(PaymentClient $client)
    {
        $client->update([
            'approval_status' => 'rejected',
            'is_active' => false,
        ]);

        return back()->with('success', 'Application rejected.');
    }

    public function docs()
    {
        return view('admin.payment_clients.docs');
    }
}
