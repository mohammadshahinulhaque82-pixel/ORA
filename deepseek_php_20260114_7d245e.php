<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\TeamMember;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::active()->featured()->orderBy('sort_order')->take(6)->get();
        $testimonials = Testimonial::active()->latest()->take(6)->get();
        $teamMembers = TeamMember::active()->orderBy('sort_order')->take(4)->get();
        $blogPosts = BlogPost::published()->latest()->take(3)->get();

        return view('frontend.home', compact('services', 'testimonials', 'teamMembers', 'blogPosts'));
    }

    public function services()
    {
        $services = Service::active()->orderBy('sort_order')->get();
        return view('frontend.services', compact('services'));
    }

    public function serviceDetail($slug)
    {
        $service = Service::where('slug', $slug)->active()->firstOrFail();
        $relatedServices = Service::where('id', '!=', $service->id)
            ->active()
            ->take(3)
            ->get();

        return view('frontend.service-detail', compact('service', 'relatedServices'));
    }

    public function about()
    {
        $teamMembers = TeamMember::active()->orderBy('sort_order')->get();
        return view('frontend.about', compact('teamMembers'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'g-recaptcha-response' => 'required|recaptcha'
        ]);

        // Send email notification
        \Mail::to(config('mail.from.address'))->send(new \App\Mail\ContactFormSubmitted($validated));

        return back()->with('success', 'Thank you for your message. We will contact you soon!');
    }
}