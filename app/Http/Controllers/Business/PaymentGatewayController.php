<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentClient;
use Illuminate\Support\Str;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $application = PaymentClient::where('user_id', auth()->id())->first();
        
        return view('business.payment_gateway.index', compact('application'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'legal_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'pan_number' => 'required|string|max:255',
            'gstin' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'website_url' => 'required|url|max:255',
            'registered_address' => 'required|string',
            'operating_address' => 'nullable|string',
            'business_email' => 'required|email|max:255',
            'business_mobile' => 'required|string|max:20',
            'expected_monthly_volume' => 'required|string|max:255',
            'expected_avg_value' => 'required|string|max:255',
            'purpose' => 'required|string',
        ]);

        // Check if already applied
        if (PaymentClient::where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'You have already applied for a payment gateway account.');
        }

        PaymentClient::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'legal_name' => $request->legal_name,
            'business_type' => $request->business_type,
            'pan_number' => $request->pan_number,
            'gstin' => $request->gstin,
            'registration_number' => $request->registration_number,
            'category' => $request->category,
            'description' => $request->description,
            'website_url' => $request->website_url,
            'registered_address' => $request->registered_address,
            'operating_address' => $request->operating_address ?? $request->registered_address,
            'business_email' => $request->business_email,
            'business_mobile' => $request->business_mobile,
            'expected_monthly_volume' => $request->expected_monthly_volume,
            'expected_avg_value' => $request->expected_avg_value,
            'purpose' => $request->purpose,
            'approval_status' => 'pending',
            'is_active' => false,
            // keys and salts generated upon approval
            'api_key' => null,
            'api_salt' => null,
            'gateway_charge_percent' => 0,
            'gst_on_charge_percent' => 18,
        ]);

        return redirect()->route('business.payment-gateway.index')->with('success', 'Your application has been submitted successfully and is under review.');
    }
}
