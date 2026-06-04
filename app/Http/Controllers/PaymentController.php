<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Mail\PaymentConfirmation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function show(Service $service): View
    {
        abort_unless($service->is_approved && $service->is_active, 404);
        abort_unless(auth()->check(), 403);
        return view('payment.show', compact('service'));
    }

    public function process(Request $request, Service $service): RedirectResponse
    {
        abort_unless(auth()->check(), 403);

        $validated = $request->validate([
            'payment_method' => ['required', 'in:card,sbp,wallet'],
            'card_number'    => ['required_if:payment_method,card', 'nullable'],
            'card_name'      => ['required_if:payment_method,card', 'nullable'],
            'card_expiry'    => ['required_if:payment_method,card', 'nullable'],
            'card_cvv'       => ['required_if:payment_method,card', 'nullable'],
        ]);

        $orderNumber = 'TF-' . strtoupper(substr(md5(uniqid()), 0, 8));

        try {
            Mail::to(auth()->user()->email)
                ->send(new PaymentConfirmation(auth()->user(), $service, $orderNumber, $validated['payment_method']));
        } catch (\Exception $e) {
            \Log::error('Payment email failed: ' . $e->getMessage());
        }

        return redirect()->route('payment.success', ['service' => $service->slug, 'order' => $orderNumber]);
    }

    public function success(Service $service): View
    {
        $order = request('order', 'TF-' . strtoupper(substr(md5(uniqid()), 0, 8)));
        return view('payment.success', compact('service', 'order'));
    }
}
