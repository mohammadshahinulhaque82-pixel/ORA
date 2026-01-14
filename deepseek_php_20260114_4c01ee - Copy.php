<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\TeamMember;
use App\Models\BlogPost;
use App\Models\Portfolio;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Hero settings
        $heroSettings = Setting::where('group', 'hero')->pluck('value', 'key');
        
        // Services
        $featuredServices = Service::with('packages')
            ->active()
            ->featured()
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        // Testimonials
        $testimonials = Testimonial::with('service')
            ->active()
            ->approved()
            ->featured()
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Team members
        $teamMembers = TeamMember::active()
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        // Blog posts
        $blogPosts = BlogPost::with('author')
            ->published()
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        // Portfolio projects
        $portfolioProjects = Portfolio::active()
            ->featured()
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        // Statistics
        $stats = [
            'projects_completed' => Portfolio::count(),
            'happy_customers' => Testimonial::count(),
            'services_offered' => Service::active()->count(),
            'years_experience' => 5 // You can calculate this dynamically
        ];

        return view('frontend.home', compact(
            'heroSettings',
            'featuredServices',
            'testimonials',
            'teamMembers',
            'blogPosts',
            'portfolioProjects',
            'stats'
        ));
    }

    public function services()
    {
        $services = Service::with(['packages' => function($query) {
            $query->active()->orderBy('price');
        }])
        ->active()
        ->orderBy('sort_order')
        ->get();

        $categories = ServiceCategory::withCount(['services' => function($query) {
            $query->active();
        }])
        ->active()
        ->orderBy('sort_order')
        ->get();

        return view('frontend.services.index', compact('services', 'categories'));
    }

    public function serviceDetail($slug)
    {
        $service = Service::with(['packages' => function($query) {
            $query->active()->orderBy('price');
        }])
        ->where('slug', $slug)
        ->active()
        ->firstOrFail();

        $relatedServices = Service::where('id', '!=', $service->id)
            ->active()
            ->inRandomOrder()
            ->take(3)
            ->get();

        $serviceTestimonials = Testimonial::where('service_id', $service->id)
            ->active()
            ->approved()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('frontend.services.detail', compact('service', 'relatedServices', 'serviceTestimonials'));
    }

    public function about()
    {
        $teamMembers = TeamMember::active()
            ->orderBy('sort_order')
            ->get();

        $companyStats = [
            'established' => 2019,
            'clients' => 450,
            'projects' => 580,
            'team' => $teamMembers->count(),
            'coverage' => 8 // states covered
        ];

        return view('frontend.about', compact('teamMembers', 'companyStats'));
    }

    public function contact()
    {
        $contactInfo = Setting::where('group', 'contact')->pluck('value', 'key');
        $serviceAreas = ServiceArea::active()->get();
        
        return view('frontend.contact', compact('contactInfo', 'serviceAreas'));
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'g-recaptcha-response' => 'required|recaptcha'
        ]);

        $contactMessage = ContactMessage::create($validated);

        // Send email notification to admin
        \Mail::to(config('mail.from.address'))->send(new \App\Mail\ContactFormSubmitted($contactMessage));

        // Send auto-reply to customer
        \Mail::to($validated['email'])->send(new \App\Mail\ContactAutoReply($validated));

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your message. We will contact you soon!'
        ]);
    }

    public function portfolio()
    {
        $projects = Portfolio::with('services')
            ->active()
            ->orderBy('sort_order')
            ->paginate(12);

        $categories = Service::active()->pluck('title', 'id');

        return view('frontend.portfolio', compact('projects', 'categories'));
    }

    public function portfolioDetail($slug)
    {
        $project = Portfolio::where('slug', $slug)
            ->active()
            ->firstOrFail();

        $relatedProjects = Portfolio::where('id', '!=', $project->id)
            ->active()
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('frontend.portfolio.detail', compact('project', 'relatedProjects'));
    }

    public function blog()
    {
        $posts = BlogPost::with(['author', 'categories'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        $categories = BlogCategory::withCount(['posts' => function($query) {
            $query->published();
        }])
        ->active()
        ->orderBy('sort_order')
        ->get();

        $recentPosts = BlogPost::published()
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $popularPosts = BlogPost::published()
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        return view('frontend.blog.index', compact('posts', 'categories', 'recentPosts', 'popularPosts'));
    }

    public function blogDetail($slug)
    {
        $post = BlogPost::with(['author', 'categories'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment view count
        $post->increment('views');

        $relatedPosts = BlogPost::where('id', '!=', $post->id)
            ->whereHas('categories', function($query) use ($post) {
                $query->whereIn('blog_categories.id', $post->categories->pluck('id'));
            })
            ->published()
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $categories = BlogCategory::withCount(['posts' => function($query) {
            $query->published();
        }])
        ->active()
        ->orderBy('sort_order')
        ->get();

        $recentPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        return view('frontend.blog.detail', compact('post', 'relatedPosts', 'categories', 'recentPosts'));
    }

    public function faq()
    {
        $faqs = FAQ::active()
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        $categories = FAQ::active()
            ->distinct('category')
            ->pluck('category')
            ->filter();

        return view('frontend.faq', compact('faqs', 'categories'));
    }

    public function team()
    {
        $teamMembers = TeamMember::with('services')
            ->active()
            ->orderBy('sort_order')
            ->get();

        return view('frontend.team', compact('teamMembers'));
    }

    public function testimonials()
    {
        $testimonials = Testimonial::with('service')
            ->active()
            ->approved()
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('frontend.testimonials', compact('testimonials'));
    }

    public function subscribeNewsletter(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
            'name' => 'nullable|string|max:255'
        ]);

        $token = Str::random(32);
        
        $subscriber = NewsletterSubscriber::create([
            'email' => $validated['email'],
            'name' => $validated['name'] ?? null,
            'unsubscribe_token' => $token
        ]);

        // Send welcome email
        \Mail::to($subscriber->email)->send(new \App\Mail\NewsletterWelcome($subscriber));

        return response()->json([
            'success' => true,
            'message' => 'Successfully subscribed to our newsletter!'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $services = Service::active()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('long_description', 'like', "%{$query}%");
            })
            ->paginate(10);

        $blogPosts = BlogPost::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->paginate(10);

        return view('frontend.search', compact('services', 'blogPosts', 'query'));
    }

    public function sitemap()
    {
        $sitemap = App::make("sitemap");

        // Add static URLs
        $sitemap->add(URL::to('/'), now(), '1.0', 'daily');
        $sitemap->add(URL::route('services'), now(), '0.9', 'weekly');
        $sitemap->add(URL::route('about'), now(), '0.8', 'monthly');
        $sitemap->add(URL::route('contact'), now(), '0.8', 'monthly');
        $sitemap->add(URL::route('blog'), now(), '0.9', 'daily');
        $sitemap->add(URL::route('portfolio'), now(), '0.8', 'weekly');

        // Add services
        $services = Service::active()->get();
        foreach ($services as $service) {
            $sitemap->add(URL::route('services.detail', $service->slug), 
                $service->updated_at, '0.8', 'monthly');
        }

        // Add blog posts
        $posts = BlogPost::published()->get();
        foreach ($posts as $post) {
            $sitemap->add(URL::route('blog.detail', $post->slug), 
                $post->updated_at, '0.7', 'monthly');
        }

        // Add portfolio
        $projects = Portfolio::active()->get();
        foreach ($projects as $project) {
            $sitemap->add(URL::route('portfolio.detail', $project->slug), 
                $project->updated_at, '0.7', 'monthly');
        }

        return $sitemap->render('xml');
    }
}