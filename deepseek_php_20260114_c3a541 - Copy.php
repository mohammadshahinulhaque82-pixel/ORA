<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'long_description',
        'icon',
        'image',
        'features',
        'base_price',
        'price_unit',
        'duration',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active'
    ];

    protected $casts = [
        'features' => 'array',
        'base_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });

        static::updating(function ($service) {
            if ($service->isDirty('title') && empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });
    }

    // Relationships
    public function packages()
    {
        return $this->hasMany(ServicePackage::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_service')
            ->withPivot('quantity', 'unit_price', 'total_price', 'notes', 'package_id')
            ->withTimestamps();
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        if ($this->base_price) {
            return $this->price_unit . ' ' . number_format($this->base_price, 2);
        }
        return 'Price on request';
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/services/' . $this->image) : asset('images/default-service.jpg');
    }

    public function getShortDescriptionAttribute()
    {
        return Str::limit(strip_tags($this->description), 150);
    }

    // Methods
    public function getPopularPackages()
    {
        return $this->packages()->where('is_popular', true)->active()->get();
    }

    public function getFeaturesArray()
    {
        return $this->features ?: [];
    }
}