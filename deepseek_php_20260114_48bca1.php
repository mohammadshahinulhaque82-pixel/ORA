<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function create($serviceSlug = null)
    {
        $services = Service::active()->get();
        $selectedService = null;

        if ($serviceSlug) {
            $selectedService = Service::where('slug', $serviceSlug)->active()->first();
        }

        return view('frontend.booking.create', compact('services', 'selectedService'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'customer_city' => 'required|string|max:100',
            'customer_state' => 'required|string|max:100',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|date_format:H:i',
            'additional_notes' => 'nullable|string|max:1000',
            'g-recaptcha-response' => 'required|recaptcha'
        ]);

        DB::beginTransaction();
        try {
            $service = Service::findOrFail($validated['service_id']);
            
            $booking = Booking::create([
                'service_id' => $service->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
                'customer_city' => $validated['customer_city'],
                'customer_state' => $validated['customer_state'],
                'preferred_date' => $validated['preferred_date'],
                'preferred_time' => $validated['preferred_time'],
                'additional_notes' => $validated['additional_notes'],
                'total_amount' => $service->price,
                'status' => 'pending'
            ]);

            // Send confirmation email to customer
            \Mail::to($booking->customer_email)->send(new \App\Mail\BookingConfirmation($booking));
            
            // Send notification email to admin
            \Mail::to(config('mail.from.address'))->send(new \App\Mail\NewBookingNotification($booking));

            DB::commit();

            return redirect()->route('booking.success', $booking->booking_code)
                ->with('success', 'Booking submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Booking failed. Please try again.');
        }
    }

    public function success($bookingCode)
    {
        $booking = Booking::where('booking_code', $bookingCode)->firstOrFail();
        return view('frontend.booking.success', compact('booking'));
    }

    public function checkStatus()
    {
        return view('frontend.booking.check-status');
    }

    public function getStatus(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|string',
            'customer_email' => 'required|email'
        ]);

        $booking = Booking::where('booking_code', $request->booking_code)
            ->where('customer_email', $request->customer_email)
            ->first();

        if (!$booking) {
            return back()->with('error', 'Booking not found. Please check your details.');
        }

        return view('frontend.booking.status', compact('booking'));
    }
}