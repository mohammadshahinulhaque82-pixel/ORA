<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'total_services' => Service::count(),
            'total_revenue' => Booking::where('status', 'completed')->sum('total_amount'),
            'monthly_revenue' => Booking::where('status', 'completed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_amount')
        ];

        $recentBookings = Booking::with('service')
            ->latest()
            ->take(10)
            ->get();

        $topServices = Service::withCount(['bookings' => function ($query) {
            $query->where('status', 'completed');
        }])
        ->orderBy('bookings_count', 'desc')
        ->take(5)
        ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'topServices'));
    }
}